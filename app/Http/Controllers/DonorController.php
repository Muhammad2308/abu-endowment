<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donor; // Make sure you have a Donation model
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
        $donor = \App\Models\Donor::where('reg_number', $reg_number)->first();
        if ($donor) {
            $donor->full_name = trim("{$donor->surname} {$donor->name} {$donor->other_name}");
            if ($donor->department_id) {
                $department = \App\Models\Department::find($donor->department_id);
                $donor->department_name = $department ? $department->name : null;
                $donor->faculty_name = $department && $department->faculty ? $department->faculty->name : null;
            }
            return response()->json($donor);
        }
        return response()->json(['message' => 'Not found'], 404);
    }

    public function searchByPhone($phone)
    {
        $donor = \App\Models\Donor::where('phone', $phone)->first();
        if ($donor) {
            $donor->full_name = trim("{$donor->surname} {$donor->name} {$donor->other_name}");
            if ($donor->department_id) {
                $department = \App\Models\Department::find($donor->department_id);
                $donor->department_name = $department ? $department->name : null;
                $donor->faculty_name = $department && $department->faculty ? $department->faculty->name : null;
            }
            return response()->json($donor);
        }
        return response()->json(['message' => 'Not found'], 404);
    }

    public function searchByEmail($email)
    {
        $donor = \App\Models\Donor::where('email', $email)->first();
        if ($donor) {
            $donor->full_name = trim("{$donor->surname} {$donor->name} {$donor->other_name}");
            if ($donor->department_id) {
                $department = \App\Models\Department::find($donor->department_id);
                $donor->department_name = $department ? $department->name : null;
                $donor->faculty_name = $department && $department->faculty ? $department->faculty->name : null;
            }
            return response()->json($donor);
        }
        return response()->json(['message' => 'Not found'], 404);
    }
}
