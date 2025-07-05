<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor; // Make sure you have a Donation model
use App\Http\Resources\DonorResource;
use Illuminate\Support\Facades\Validator;

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
            
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'address' => 'required|string|max:500',
                'state' => 'required|string|max:100',
                'city' => 'required|string|max:100', // Frontend sends 'city'
                'ranking' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Parse the full name into components
            $nameComponents = $this->parseName($request->name);
            
            // Update the donor with parsed name components
            $donor->update([
                'name' => $nameComponents['name'],
                'surname' => $nameComponents['surname'],
                'other_name' => $nameComponents['other_names'],
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'state' => $request->state,
                'lga' => $request->city, // Map 'city' from frontend to 'lga' in database
                'ranking' => $request->ranking ?? $donor->ranking,
            ]);

            // Load relationships for response
            $donor->load(['faculty', 'department']);

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
                    'city' => $donor->lga, // Map 'lga' back to 'city' for frontend
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
            return response()->json([
                'success' => false,
                'message' => 'Donor not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating donor: ' . $e->getMessage()
            ], 500);
        }
    }
}
