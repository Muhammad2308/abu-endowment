<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonorSession;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller
{
    /**
     * Show donor registration form
     */
    public function showRegistrationForm()
    {
        $faculties = Faculty::all();
        return view('auth.register', compact('faculties'));
    }

    /**
     * Handle donor registration
     */
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'donor_type' => 'required|string|in:Individual,Alumni,Supporter,Staff,Corporate,Organization,NGO',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:donors,email',
            'phone' => 'nullable|string|unique:donors,phone',
            'password' => 'required|string|min:6|confirmed',
            'faculty_id' => 'nullable|exists:faculties,id',
            'department_id' => 'nullable|exists:departments,id',
            'entry_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'graduation_year' => 'nullable|integer|min:1950|max:' . date('Y'),
            'reg_number' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'lga' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'nationality' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        try {
            // Create donor
            $donorData = $request->except(['password', 'password_confirmation']);
            $donorData['nationality'] = $donorData['nationality'] ?? 'Nigerian';

            $donor = Donor::create($donorData);

            // Create donor session
            $session = DonorSession::create([
                'username' => $request->email,
                'password' => $request->password,
                'donor_id' => $donor->id,
                'auth_provider' => 'email',
            ]);

            Log::info('Donor registered successfully', [
                'donor_id' => $donor->id,
                'email' => $donor->email,
                'session_id' => $session->id,
            ]);

            // Redirect to login or dashboard
            session(['success_message' => 'Registration successful! Please log in.']);
            return redirect()->route('login.form')->with('success', 'Registration successful! Please log in.');
        } catch (\Exception $e) {
            Log::error('Donor registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($validator);
        }

        try {
            $session = DonorSession::where('username', $request->email)
                ->where('auth_provider', 'email')
                ->first();

            if (!$session || !password_verify($request->password, $session->password)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['email' => 'Invalid credentials']);
            }

            // Store session info in session
            session([
                'donor_id' => $session->donor_id,
                'session_id' => $session->id,
                'email' => $request->email,
            ]);

            Log::info('Donor login successful', [
                'donor_id' => $session->donor_id,
                'email' => $request->email,
            ]);

            return redirect()->route('donor.dashboard')->with('success', 'Login successful!');
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Login failed. Please try again.']);
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        session()->flush();
        return redirect()->route('welcome')->with('success', 'Logged out successfully');
    }

    /**
     * Get departments for faculty (AJAX)
     */
    public function getDepartments($facultyId)
    {
        $departments = Department::where('faculty_id', $facultyId)->get();
        return response()->json($departments);
    }
}
