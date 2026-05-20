<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        \App\Models\Donor::observe(\App\Observers\DonorObserver::class);

        $this->ensureStorageDirectories();
    }

    private function ensureStorageDirectories(): void
    {
        $dirs = [
            storage_path('app/public/projects/icons'),
            storage_path('app/public/projects/photos'),
            storage_path('app/livewire-tmp'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
        }

        // Create public/storage symlink if it doesn't exist
        $link   = public_path('storage');
        $target = storage_path('app/public');
        if (!file_exists($link) && !is_link($link)) {
            symlink($target, $link);
        }
    }
}
