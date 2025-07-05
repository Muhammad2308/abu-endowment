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
     * Update donor information
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'surname' => 'sometimes|string|max:255',
            'other_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:donors,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'state' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'lga' => 'sometimes|string|max:255',
            'nationality' => 'sometimes|string|max:255',
            'entry_year' => 'sometimes|integer|min:1950|max:2050',
            'graduation_year' => 'sometimes|integer|min:1950|max:2050',
            'faculty_id' => 'sometimes|exists:faculties,id',
            'department_id' => 'sometimes|exists:departments,id',
            'donor_type' => 'sometimes|string|in:addressable_alumni,non_addressable_alumni,staff,anonymous',
            'ranking' => 'sometimes|integer|min:1|max:100',
        ]);

        $donor = Donor::findOrFail($id);
        $donor->update($request->only([
            'name', 'surname', 'other_name', 'email', 'phone', 'address',
            'state', 'city', 'lga', 'nationality', 'entry_year', 'graduation_year',
            'faculty_id', 'department_id', 'donor_type', 'ranking'
        ]));

        // Load relationships for response
        $donor->load(['faculty', 'department']);

        return new DonorResource($donor);
    }
}
