<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\DonorSession;
use App\Http\Resources\DonorResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DonorsController extends Controller
{
    /**
     * Store a newly created donor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Only create donor if donor_type, name, and surname are provided
        if (!$request->has('donor_type') || empty($request->donor_type)) {
            return response()->json([
                'success' => false,
                'message' => 'Donor type, name, and surname are required to create a donor'
            ], 422);
        }

        // Validate input based on donor type
        $donorType = $request->input('donor_type');
        
        $validationRules = [
            'donor_type' => 'required|string|in:supporter,addressable_alumni,non_addressable_alumni,Individual,Organization,NGO',
            'name' => 'required|string|max:255', // ✅ Required when creating donor
            'surname' => 'required|string|max:255', // ✅ Required when creating donor
            'other_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:donors,email',
            'phone' => 'nullable|string|max:20|unique:donors,phone', // ✅ Make optional, unique only if provided
            'session_id' => 'nullable|exists:donor_sessions,id',
        ];
        
        // Add conditional validation based on donor type
        if (in_array($donorType, ['addressable_alumni', 'non_addressable_alumni'])) {
            $validationRules['reg_number'] = 'required|string|max:255|unique:donors,reg_number';
            $validationRules['faculty_id'] = 'required|exists:faculties,id';
            $validationRules['department_id'] = 'required|exists:departments,id';
            $validationRules['entry_year'] = 'nullable|integer|min:1950|max:' . date('Y');
            $validationRules['graduation_year'] = 'nullable|integer|min:1950|max:' . date('Y');
        } else {
            $validationRules['reg_number'] = 'nullable|string|max:255';
            $validationRules['faculty_id'] = 'nullable|exists:faculties,id';
            $validationRules['department_id'] = 'nullable|exists:departments,id';
        }
        
        // Common validation for all donor types - make optional for minimal registration
        $validationRules['nationality'] = 'nullable|string|max:255'; // ✅ Make optional
        $validationRules['state'] = 'nullable|string|max:255'; // ✅ Make optional
        $validationRules['lga'] = 'nullable|string|max:255'; // ✅ Make optional
        $validationRules['address'] = 'nullable|string';
        
        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Prepare data for creation
            $donorData = $request->all();
            
            // Handle different donor types
            if ($donorType === 'supporter') {
                // For supporters, reg_number is not required
                $donorData['reg_number'] = null;
                $donorData['faculty_id'] = null;
                $donorData['department_id'] = null;
                $donorData['entry_year'] = null;
                $donorData['graduation_year'] = null;
            } else {
                // For alumni, ensure reg_number is unique and generate if needed
                if (empty($donorData['reg_number'])) {
                    $donorData['reg_number'] = 'REG-' . strtoupper(substr($donorData['surname'], 0, 3)) . '-' . date('Y') . '-' . str_pad(Donor::count() + 1, 4, '0', STR_PAD_LEFT);
                }
            }
            
            // Set defaults for optional fields if not provided (for minimal registration)
            if (empty($donorData['surname'])) {
                $donorData['surname'] = ''; // Default to empty string
            }
            if (empty($donorData['phone'])) {
                $donorData['phone'] = null; // Default to null
            }
            if (empty($donorData['nationality'])) {
                $donorData['nationality'] = 'Nigerian'; // Default nationality
            }
            if (empty($donorData['state'])) {
                $donorData['state'] = null;
            }
            if (empty($donorData['lga'])) {
                $donorData['lga'] = null;
            }

            // Create donor
            $donor = Donor::create($donorData);
            $this->linkDonorToSession($donor, $request->input('session_id'), $donor->email);
            
            Log::info('Donor created successfully', [
                'donor_id' => $donor->id,
                'email' => $donor->email,
                'donor_type' => $donor->donor_type,
                'has_surname' => !empty($donor->surname),
                'has_phone' => !empty($donor->phone)
            ]);

            return response()->json([
                'message' => 'Registration successful!',
                'donor' => new DonorResource($donor)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Donor registration error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Registration failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified donor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $donor = Donor::find($id);

        if (!$donor) {
            return response()->json([
                'success' => false,
                'message' => 'Donor not found'
            ], 404);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|unique:donors,phone,' . $id,
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $donor->update($request->only([
                'name', 'surname', 'other_name', 'phone', 
                'state', 'lga', 'nationality', 'address'
            ]));

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'donor' => new DonorResource($donor)
            ]);
        } catch (\Exception $e) {
            Log::error('Donor update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Update failed. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search for a donor by email.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function searchByEmail($email)
    {
        $donor = Donor::where('email', $email)->first();

        if ($donor) {
            return response()->json([
                'exists' => true,
                'donor' => new DonorResource($donor)
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'Donor not found'
        ], 404);
    }

    /**
     * Search for addressable alumni by registration number.
     *
     * @param  string  $regNumber
     * @return \Illuminate\Http\Response
     */
    public function searchAddressableAlumni($regNumber)
    {
        $donor = Donor::with(['faculty', 'department'])
            ->where('reg_number', $regNumber)
            ->where('donor_type', 'addressable_alumni')
            ->first();

        if ($donor) {
            return response()->json([
                'success' => true,
                'donor' => new DonorResource($donor)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Addressable alumni not found with this registration number'
        ], 404);
    }

    /**
     * Check if a device has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkByDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the device is associated with any donor
        // This is a placeholder - you may need to adjust based on your actual implementation
        $donor = Donor::where('device_id', $request->device_id)->first();

        if ($donor) {
            return response()->json([
                'exists' => true,
                'donor' => new DonorResource($donor)
            ]);
        }

        return response()->json([
            'exists' => false
        ]);
    }

    /**
     * Upload profile image for a donor
     * 
     * POST /api/donors/{id}/profile-image
     */
    public function uploadProfileImage(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $donor = Donor::findOrFail($id);

            // Delete old image if exists
            if ($donor->profile_image) {
                Storage::disk('public')->delete($donor->profile_image);
            }

            // Store new image
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $donor->profile_image = $path;
            $donor->save();

            // Load relationships for response
            $donor->load(['faculty', 'department']);

            return response()->json([
                'success' => true,
                'message' => 'Profile image updated successfully',
                'data' => [
                    'donor' => [
                        'id' => $donor->id,
                        'name' => $donor->name,
                        'surname' => $donor->surname,
                        'other_name' => $donor->other_name,
                        'email' => $donor->email,
                        'phone' => $donor->phone,
                        'profile_image' => $donor->profile_image ? Storage::url($donor->profile_image) : null,
                        'profile_image_path' => $donor->profile_image,
                    ]
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Donor not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error uploading profile image', [
                'donor_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error uploading profile image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Link a donor to a session immediately after creation/update.
     */
    private function linkDonorToSession(Donor $donor, $sessionId = null, ?string $email = null): void
    {
        try {
            $sessionQuery = DonorSession::query();

            if ($sessionId) {
                $sessionQuery->where('id', $sessionId);
            } elseif ($email) {
                $sessionQuery->where('username', $email);
            } else {
                return;
            }

            $session = $sessionQuery->first();

            if (!$session) {
                return;
            }

            if ($session->donor_id === $donor->id) {
                return;
            }

            $session->update(['donor_id' => $donor->id]);

            Log::info('Linked donor to session', [
                'donor_id' => $donor->id,
                'session_id' => $session->id,
                'username' => $session->username,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to link donor to session', [
                'donor_id' => $donor->id,
                'session_id' => $sessionId,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}