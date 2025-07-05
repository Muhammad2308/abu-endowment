<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\DeviceSession;

class SessionController extends Controller
{
    /**
     * Create device session after verification
     */
    public function create(Request $request)
    {
        $request->validate([
            'donorData' => 'required|array',
            'deviceInfo' => 'required|array',
            'verificationData' => 'nullable|array'
        ]);

        $donorData = $request->donorData;
        $deviceInfo = $request->deviceInfo;
        
        // Create or update user
        $user = User::updateOrCreate(
            ['email' => $donorData['email']],
            [
                'name' => $donorData['name'],
                'phone' => $donorData['phone'],
                'donor_type' => $donorData['donor_type'] ?? 'alumni',
                // Add other fields as needed
            ]
        );
        
        // Create device session
        $sessionToken = Str::random(64);
        DeviceSession::create([
            'user_id' => $user->id,
            'session_token' => $sessionToken,
            'device_fingerprint' => $deviceInfo['fingerprint'] ?? md5($deviceInfo['userAgent'] . $request->ip()),
            'user_agent' => $deviceInfo['userAgent'],
            'ip_address' => $request->ip(),
            'expires_at' => now()->addDays(30)
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Session created successfully',
            'user' => $user,
            'session_token' => $sessionToken
        ]);
    }

    /**
     * Check if device is recognized
     */
    public function check(Request $request)
    {
        $request->validate([
            'deviceInfo' => 'required|array'
        ]);

        $deviceInfo = $request->deviceInfo;
        
        $session = DeviceSession::where('device_fingerprint', $deviceInfo['fingerprint'])
            ->where('expires_at', '>', now())
            ->with('user')
            ->first();
        
        if ($session) {
            return response()->json([
                'valid' => true,
                'user' => $session->user
            ]);
        }
        
        return response()->json([
            'valid' => false
        ]);
    }

    /**
     * Login with device session
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required_without:email',
            'email' => 'required_without:phone|email',
            'deviceInfo' => 'required|array'
        ]);

        $deviceInfo = $request->deviceInfo;
        
        // Find user by phone or email
        $user = null;
        if ($request->phone) {
            $user = User::where('phone', $request->phone)->first();
        } else {
            $user = User::where('email', $request->email)->first();
        }
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
        
        // Create new device session
        $sessionToken = Str::random(64);
        DeviceSession::create([
            'user_id' => $user->id,
            'session_token' => $sessionToken,
            'device_fingerprint' => $deviceInfo['fingerprint'],
            'user_agent' => $deviceInfo['userAgent'],
            'ip_address' => $request->ip(),
            'expires_at' => now()->addDays(30)
        ]);
        
        return response()->json([
            'user' => $user,
            'session_token' => $sessionToken
        ]);
    }

    /**
     * Logout device session
     */
    public function logout(Request $request)
    {
        $sessionToken = $request->header('X-Device-Session');
        
        if ($sessionToken) {
            DeviceSession::where('session_token', $sessionToken)->delete();
        }
        
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
} 