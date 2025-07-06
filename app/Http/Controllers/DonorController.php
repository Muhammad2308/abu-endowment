<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor; // Make sure you have a Donation model
use App\Http\Resources\DonorResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DonorController extends Controller
{
    public function makeDonation(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            // Add other fields as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Save donation (customize as needed)
        $donation = Donor::create([
            'amount' => $request->amount,
            'name' => $request->name,
            'email' => $request->email,
            // Add other fields as needed
        ]);

        // Return success response
        return response()->json([
            'message' => 'Donation successful!',
            'donation' => $donation
        ], 201);
    }

    public function searchByRegNumber($reg_number)
    {
        $donor = Donor::with(['faculty', 'department'])
            ->where('reg_number', $reg_number)
            ->first();

        if (!$donor) {
            return response()->json(['message' => 'Donor not found'], 404);
        }

        return new DonorResource($donor);
    }

    public function searchByPhone($phone)
    {
        $donor = Donor::with(['faculty', 'department'])
            ->where('phone', $phone)
            ->first();

        if (!$donor) {
            return response()->json(['message' => 'Donor not found'], 404);
        }

        return new DonorResource($donor);
    }

    public function searchByEmail($email)
    {
        $donor = Donor::with(['faculty', 'department'])
            ->where('email', $email)
            ->first();

        if (!$donor) {
            return response()->json(['message' => 'Donor not found'], 404);
        }

        return new DonorResource($donor);
    }

    // Additional method for registration number search (alias)
    public function searchByRegistrationNumber($regNumber)
    {
        return $this->searchByRegNumber($regNumber);
    }

    /**
     * Parse full name into components (surname, name, other_names)
     */
    private function parseName($fullName)
    {
        $nameParts = array_filter(explode(' ', trim($fullName)));
        
        if (empty($nameParts)) {
            return [
                'surname' => '',
                'name' => '',
                'other_names' => ''
            ];
        }
        
        if (count($nameParts) === 1) {
            return [
                'surname' => '',
                'name' => $nameParts[0],
                'other_names' => ''
            ];
        }
        
        if (count($nameParts) === 2) {
            return [
                'surname' => $nameParts[0],
                'name' => $nameParts[1],
                'other_names' => ''
            ];
        }
        
        // For 3 or more parts: first is surname, last is name, middle are other_names
        $surname = array_shift($nameParts);
        $name = array_pop($nameParts);
        $other_names = implode(' ', $nameParts);
        
        return [
            'surname' => $surname,
            'name' => $name,
            'other_names' => $other_names
        ];
    }

    /**
     * Update the specified donor
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the donor
            $donor = Donor::findOrFail($id);
            
            // Log the incoming request for debugging
            Log::info('Donor update request', [
                'id' => $id,
                'data' => $request->all()
            ]);
            
            // Format phone number before validation
            $phone = $this->formatPhoneNumber($request->phone);
            
            // Log formatted phone number
            Log::info('Formatted phone number', [
                'original' => $request->phone,
                'formatted' => $phone
            ]);
            
            // Validate the request
            $validator = Validator::make(array_merge($request->all(), ['phone' => $phone]), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20|regex:/^\+[0-9]{10,15}$/',
                'address' => 'required|string|max:500',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100', // Frontend sends 'city'
                'ranking' => 'nullable|integer|min:1',
            ], [
                'phone.regex' => 'Phone number must be in international format (e.g., +2348012345678)',
            ]);

            if ($validator->fails()) {
                Log::warning('Donor update validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Parse the full name into components
            $nameComponents = $this->parseName($request->name);
            
            // Log name parsing result
            Log::info('Name parsing result', [
                'original' => $request->name,
                'parsed' => $nameComponents
            ]);
            
            // Update the donor with parsed name components
            $donor->update([
                'name' => $nameComponents['name'],
                'surname' => $nameComponents['surname'],
                'other_name' => $nameComponents['other_names'],
                'email' => $request->email,
                'phone' => $phone, // Use formatted phone number
                'address' => $request->address,
                'state' => $request->state,
                'lga' => $request->city, // Map frontend 'city' to database 'lga'
                'ranking' => $request->ranking ?? $donor->ranking,
            ]);

            // Load relationships for response
            $donor->load(['faculty', 'department']);

            Log::info('Donor updated successfully', [
                'id' => $donor->id,
                'name' => $donor->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donor updated successfully',
                'data' => [
                    'id' => $donor->id,
                    'name' => $donor->name,
                    'surname' => $donor->surname,
                    'other_names' => $donor->other_name,
                    'full_name' => trim(implode(' ', array_filter([$donor->surname, $donor->name, $donor->other_name]))),
                    'email' => $donor->email,
                    'phone' => $donor->phone,
                    'address' => $donor->address,
                    'state' => $donor->state,
                    'city' => $donor->lga, // Map database 'lga' back to 'city' for frontend
                    'ranking' => $donor->ranking,
                    'donor_type' => $donor->donor_type,
                    'faculty_name' => $donor->faculty?->name,
                    'department_name' => $donor->department?->name,
                    'faculty' => $donor->faculty,
                    'department' => $donor->department,
                    'graduation_year' => $donor->graduation_year,
                    'entry_year' => $donor->entry_year,
                    'registration_number' => $donor->registration_number,
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Donor not found', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Donor not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error updating donor', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating donor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format phone number to international format
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // If it doesn't start with +, assume it's a Nigerian number
        if (!str_starts_with($phone, '+')) {
            // Remove leading 0 if present
            if (str_starts_with($phone, '0')) {
                $phone = substr($phone, 1);
            }
            
            // Add Nigerian country code if not present
            if (!str_starts_with($phone, '234')) {
                $phone = '234' . $phone;
            }
            
            $phone = '+' . $phone;
        }

        return $phone;
    }

    /**
     * Store a newly created donor (for non-addressable alumni)
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creating new non-addressable alumni donor', [
                'data' => $request->all()
            ]);

            // Format phone number before validation
            $phone = $this->formatPhoneNumber($request->phone);
            
            // Validate the request for non-addressable alumni
            $validator = Validator::make(array_merge($request->all(), ['phone' => $phone]), [
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'other_name' => 'nullable|string|max:255',
                'gender' => 'required|in:male,female',
                'country' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100',
                'address' => 'required|string|max:500',
                'email' => 'required|email|max:255|unique:donors,email',
                'phone' => 'required|string|max:20|regex:/^\+[0-9]{10,15}$/|unique:donors,phone',
                'donor_type' => 'required|string|in:non_addressable_alumni',
            ], [
                'phone.regex' => 'Phone number must be in international format (e.g., +2348012345678)',
                'email.unique' => 'This email is already registered',
                'phone.unique' => 'This phone number is already registered',
            ]);

            if ($validator->fails()) {
                Log::warning('Non-addressable alumni creation validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create the donor with minimal fields for non-addressable alumni
            $donor = Donor::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'other_name' => $request->other_name,
                'gender' => $request->gender,
                'country' => $request->country,
                'email' => $request->email,
                'phone' => $phone,
                'address' => $request->address,
                'state' => $request->state,
                'lga' => $request->city, // Map frontend 'city' to database 'lga'
                'donor_type' => 'non_addressable_alumni',
                'nationality' => $request->country,
                // Set other fields to null for non-addressable alumni
                'graduation_year' => null,
                'entry_year' => null,
                'reg_number' => null,
                'faculty_id' => null,
                'department_id' => null,
                'ranking' => null,
            ]);

            Log::info('Non-addressable alumni donor created successfully', [
                'id' => $donor->id,
                'name' => $donor->name,
                'email' => $donor->email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Non-addressable alumni account created successfully',
                'data' => [
                    'id' => $donor->id,
                    'name' => $donor->name,
                    'surname' => $donor->surname,
                    'other_names' => $donor->other_name,
                    'full_name' => trim(implode(' ', array_filter([$donor->surname, $donor->name, $donor->other_name]))),
                    'gender' => $donor->gender,
                    'country' => $donor->country,
                    'email' => $donor->email,
                    'phone' => $donor->phone,
                    'address' => $donor->address,
                    'state' => $donor->state,
                    'city' => $donor->lga,
                    'donor_type' => $donor->donor_type,
                    'faculty_name' => null,
                    'department_name' => null,
                    'faculty' => null,
                    'department' => null,
                    'graduation_year' => null,
                    'entry_year' => null,
                    'registration_number' => null,
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating non-addressable alumni donor', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating non-addressable alumni account: ' . $e->getMessage()
            ], 500);
        }
    }
}
