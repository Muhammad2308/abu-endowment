<?php

namespace App\Observers;

use App\Models\Donor;
use App\Models\DonorSession;
use App\Mail\AlumniWelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DonorObserver
{
    /**
     * Handle the Donor "created" event.
     */
    public function created(Donor $donor): void
    {
        // Only create session and send email if email is present
        if ($donor->email) {
            try {
                // Generate default password: surname (lowercase) + 1234
                // Fallback to 'password1234' if surname is missing (unlikely due to validation)
                $surname = $donor->surname ? strtolower($donor->surname) : 'password';
                $plainPassword = $surname . '1234';
                
                // Create DonorSession
                // Check if session already exists to avoid duplicates (though created event implies new donor)
                $donorSession = DonorSession::firstOrCreate(
                    ['donor_id' => $donor->id],
                    [
                        'username' => $donor->email,
                        'password' => $plainPassword, // Model mutator will hash this
                        'auth_provider' => 'email', // Default provider
                    ]
                );

                // Send Welcome Email
                Mail::to($donor->email)->send(new AlumniWelcomeMail($donor, $donor->email, $plainPassword));
                
                Log::info("Created session and sent welcome email for donor: {$donor->email}");

            } catch (\Exception $e) {
                Log::error("Failed to create session or send email for donor {$donor->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Donor "updated" event.
     */
    public function updated(Donor $donor): void
    {
        //
    }

    /**
     * Handle the Donor "deleted" event.
     */
    public function deleted(Donor $donor): void
    {
        //
    }

    /**
     * Handle the Donor "restored" event.
     */
    public function restored(Donor $donor): void
    {
        //
    }

    /**
     * Handle the Donor "force deleted" event.
     */
    public function forceDeleted(Donor $donor): void
    {
        //
    }
}
