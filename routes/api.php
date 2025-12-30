<?php

use App\Http\Controllers\DonorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonorMessageController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\AlumniController;
use App\Http\Controllers\Api\FacultiesVisionController;
use App\Http\Controllers\Api\DepartmentVisionController;
use App\Http\Controllers\Api\DonorsController;
use App\Http\Controllers\Api\DonorSessionController;

// Public routes (no authentication required) - like search endpoints
Route::post('/donors', [DonorController::class, 'store']); // Create donor account
Route::get('/donors/search/{reg_number}', [DonorController::class, 'searchByRegNumber'])
    ->where('reg_number', '.*');
Route::get('/donors/search/phone/{phone}', [DonorController::class, 'searchByPhone'])
    ->where('phone', '.*');
Route::get('/donors/search/email/{email}', [DonorController::class, 'searchByEmail'])
    ->where('email', '.*');
Route::put('/donors/{id}', [DonorController::class, 'update']); // Make this public like search
// Route::get('/donors', [DonorController::class, 'index']); // Moved to messaging section for consistency

// Session routes (public)
Route::post('/session/create', [SessionController::class, 'create']);
Route::post('/session/check', [SessionController::class, 'check']);
Route::post('/session/login', [SessionController::class, 'login']);
Route::post('/session/login-with-donor', [SessionController::class, 'loginWithDonor']); // New donor-based login
Route::post('/session/logout', [SessionController::class, 'logout']);

// Verification routes (public)
Route::post('/verification/send-sms', [VerificationController::class, 'sendSMS']);
Route::post('/verification/send-email', [VerificationController::class, 'sendEmail']);
Route::post('/verification/verify-sms', [VerificationController::class, 'verifySMS']);
Route::post('/verification/verify-email', [VerificationController::class, 'verifyEmail']);

// Projects (public)
Route::get('/projects', [ProjectController::class, 'index']);

// Donations (public - no authentication required)
Route::post('/donations', [DonorController::class, 'makeDonation']);

// Donor types (public)
Route::get('/donor-types', function () {
    return response()->json([
        'types' => [
            'addressable_alumni' => 'Addressable Alumni',
            'non_addressable_alumni' => 'Non-addressable Alumni',
            'staff' => 'Staff',
            'anonymous' => 'Anonymous'
        ]
    ]);
});

// Projects with photos for slider (public)
Route::get('/projects-with-photos', function () {
    return \App\Models\Project::where('status', 'active')->with(['photos'])->orderBy('created_at', 'desc')->get()->map(function ($project) {
        return [
            'id' => $project->id,
            'project_title' => $project->project_title,
            'project_description' => $project->project_description,
            'icon_image_url' => $project->icon_image_url,
            'target' => $project->target ?? 0, // Fundraising target in naira
            'raised' => $project->raised ?? 0, // Amount raised in naira
            'status' => $project->status,
            'created_at' => $project->created_at,
            'photos' => $project->photos->map(function ($photo) {
                return [
                    'image_url' => $photo->image_url,
                    'title' => $photo->title,
                    'description' => $photo->description,
                ];
            }),
        ];
    });
});

// Faculty and Department vision for cascading dropdowns (public)
Route::get('/faculty-vision', function (Request $request) {
    $entryYear = $request->query('entry_year');
    $graduationYear = $request->query('graduation_year');
    
    if (!$entryYear || !$graduationYear) {
        return response()->json([
            'success' => false,
            'message' => 'Entry year and graduation year are required'
        ], 400);
    }
    
    $faculties = \App\Models\FacultyVision::where('start_year', '<=', $entryYear)
                                         ->where('end_year', '>=', $graduationYear)
                                         ->get();
    
    return response()->json([
        'success' => true,
        'data' => $faculties->map(function ($faculty) {
            return [
                'id' => $faculty->id,
                'name' => $faculty->name,
                'start_year' => $faculty->start_year,
                'end_year' => $faculty->end_year
            ];
        })
    ]);
});

Route::get('/department-vision', function (Request $request) {
    $facultyId = $request->query('faculty_id');
    $entryYear = $request->query('entry_year');
    $graduationYear = $request->query('graduation_year');
    
    if (!$facultyId || !$entryYear || !$graduationYear) {
        return response()->json([
            'success' => false,
            'message' => 'Faculty ID, entry year, and graduation year are required'
        ], 400);
    }
    
    $departments = \App\Models\DepartmentVision::where('faculty_id', $facultyId)
                                              ->where('start_year', '<=', $entryYear)
                                              ->where('end_year', '>=', $graduationYear)
                                              ->get();
    
    return response()->json([
        'success' => true,
        'data' => $departments->map(function ($department) {
            return [
                'id' => $department->id,
                'name' => $department->name,
                'start_year' => $department->start_year,
                'end_year' => $department->end_year
            ];
        })
    ]);
});

// Add donation history as a public route
Route::get('/donations/history', [DonorController::class, 'donationHistory']);

// Alumni contacts for contact tab (public)
Route::get('/alumni/contacts', [DonorController::class, 'getAlumniContacts']);

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'API is working correctly',
        'timestamp' => now()->toISOString(),
        'device_sessions_count' => \App\Models\DeviceSession::count(),
        'donors_count' => \App\Models\Donor::count()
    ]);
});

// Public Faculty/Department data (no authentication required)
Route::get('/faculties', [\App\Http\Controllers\Api\FacultyController::class, 'index']);

// Statistics routes (public for admin dashboard)
Route::prefix('statistics')->group(function () {
    Route::get('/donors', [\App\Http\Controllers\Api\StatisticsController::class, 'donors']);
    Route::get('/donor-types', [\App\Http\Controllers\Api\StatisticsController::class, 'donorTypes']);
    Route::get('/departments', [\App\Http\Controllers\Api\StatisticsController::class, 'departments']);
    Route::get('/states', [\App\Http\Controllers\Api\StatisticsController::class, 'states']);
    Route::get('/lgas', [\App\Http\Controllers\Api\StatisticsController::class, 'lgas']);
    Route::get('/gender', [\App\Http\Controllers\Api\StatisticsController::class, 'gender']);
    Route::get('/summary', [\App\Http\Controllers\Api\StatisticsController::class, 'summary']);
});
Route::get('/faculties/{id}/departments', [\App\Http\Controllers\Api\FacultyController::class, 'departments']);
Route::get('/public/faculties', [\App\Http\Controllers\Api\FacultyController::class, 'index']);
Route::get('/public/faculties/{id}/departments', [\App\Http\Controllers\Api\FacultyController::class, 'departments']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Donor management
    Route::apiResource('donors', DonorController::class)->except(['update']); // Exclude update as it's public
    Route::get('/donors/faculty/{faculty_id}', [DonorController::class, 'getByFaculty']);
    Route::get('/donors/department/{department_id}', [DonorController::class, 'getByDepartment']);
    
    // Donations (summary requires auth)
    Route::get('/donations/summary', [DonorController::class, 'donationSummary']);
    
    // Rankings
    Route::get('/rankings', [RankingController::class, 'index']);
    Route::get('/rankings/top-donors', [RankingController::class, 'topDonors']);
    Route::get('/rankings/faculty', [RankingController::class, 'facultyRankings']);
    Route::get('/rankings/department', [RankingController::class, 'departmentRankings']);
    
    // Protected Faculty and Department data (authenticated users)
    // These are duplicates but with authentication - kept for backward compatibility
    
    Route::get('/departments', function () {
        return \App\Models\Department::with('visions')->get();
    });
    
    Route::get('/faculties/{faculty}/visions', function (\App\Models\Faculty $faculty) {
        return $faculty->visions;
    });
    
    Route::get('/departments/{department}/visions', function (\App\Models\Department $department) {
        return $department->visions;
    });
    
});

// Admin routes (protected by role middleware)
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::apiResource('projects', ProjectController::class)->except(['index', 'show']);
    Route::post('/projects/{project}/photos', [ProjectController::class, 'addPhoto']);
    Route::delete('/projects/{project}/photos/{photo}', [ProjectController::class, 'removePhoto']);
    
    Route::get('/donors/upload', [DonorController::class, 'uploadForm']);
    Route::post('/donors/upload', [DonorController::class, 'uploadAlumni']);
    
    Route::get('/statistics', [DonorController::class, 'statistics']);
    Route::post('/notifications/send', [DonorController::class, 'sendNotifications']);
});

// Device session management (public - no authentication required)
Route::post('/devices/register', [DeviceController::class, 'register']);
Route::post('/devices/test-register', function(Request $request) {
    // Test endpoint to debug device registration
    return response()->json([
        'received_data' => $request->all(),
        'headers' => $request->headers->all(),
        'method' => $request->method(),
        'content_type' => $request->header('Content-Type'),
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent()
    ]);
});
Route::post('/sessions/check', [DeviceController::class, 'checkSession']);
Route::get('/devices/check/{fingerprint}', [DeviceController::class, 'checkDevice']);
Route::get('/device/check', [DeviceController::class, 'check']); // Keep existing
Route::post('/device/session', [DeviceController::class, 'createSession']); // Keep existing
Route::get('/device/donor-info', [DeviceController::class, 'getDonorInfo']); // Keep existing

// Payment routes (public - no authentication required)
Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
Route::get('/payments/verify/{reference}', [PaymentController::class, 'verify']);
Route::get('/payments/test', [PaymentController::class, 'test']); // Test configuration

// Webhook (no CSRF protection needed)
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

use App\Http\Controllers\Api\MessageController;

Route::get('/donor/{donor}/messages', [DonorMessageController::class, 'index']);

// Messaging API (Authenticated)
Route::prefix('messages')->group(function () {
    Route::get('/received', [MessageController::class, 'getReceivedMessages']);
    Route::get('/sent', [MessageController::class, 'getSentMessages']);
    Route::post('/', [MessageController::class, 'send']);
    Route::put('/{id}/read', [MessageController::class, 'markAsRead']);
    Route::get('/unread-count', [MessageController::class, 'getUnreadCount']);
    Route::get('/conversation', [MessageController::class, 'getConversation']); // Keeping for internal use
});

// Alumni Directory (Authenticated)
Route::get('/donors', [DonorController::class, 'index']);

Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::get('/sms-messages', [SmsController::class, 'getMessages']);

Route::get('alumni/lookup', [AlumniController::class, 'lookup']); // ?reg_number=
Route::get('alumni/search', [AlumniController::class, 'search']); // ?query=
Route::get('faculties-visions', [FacultiesVisionController::class, 'index']); // ?year=
Route::get('department-visions', [DepartmentVisionController::class, 'index']); // ?year=&faculty_id=
Route::post('donors', [DonorsController::class, 'store']);
Route::put('donors/{id}', [DonorsController::class, 'update']);
Route::post('donors/check-device', [DonorsController::class, 'checkByDevice']);
Route::get('donors/search/email/{email}', [DonorsController::class, 'searchByEmail']);
Route::get('donors/search/addressable-alumni/{regNumber}', [DonorsController::class, 'searchAddressableAlumni']);

// Donor Sessions routes (public - standard login/logout functionality)
Route::post('/donor-sessions/register', [DonorSessionController::class, 'register']);
Route::post('/donor-sessions/login', [DonorSessionController::class, 'login']);
Route::post('/donor-sessions/google-login', [DonorSessionController::class, 'googleLogin']);
Route::post('/donor-sessions/google-register', [DonorSessionController::class, 'googleRegister']);

// Password Reset Routes (link flow)
Route::post('/donor-sessions/forgot-password', [DonorSessionController::class, 'forgotPassword']);
Route::get('/donor-sessions/reset/{token}', [DonorSessionController::class, 'getResetToken']);
Route::post('/donor-sessions/reset/{token}', [DonorSessionController::class, 'resetPasswordWithToken']);

// Debug route for Google token verification (remove in production)
Route::get('/test-google-token', function (Request $request) {
    $token = $request->get('token');
    if (!$token) {
        return response()->json(['error' => 'Token required. Use: ?token=YOUR_TOKEN'], 400);
    }

    $googleAuth = app(\App\Services\GoogleAuthService::class);
    $result = $googleAuth->verifyToken($token);

    return response()->json([
        'success' => $result !== false,
        'data' => $result,
        'debug' => [
            'client_id_from_config' => config('services.google.client_id'),
            'client_id_from_env' => env('GOOGLE_CLIENT_ID'),
            'client_id_empty' => empty(config('services.google.client_id')),
        ],
    ]);
});

Route::post('/donor-sessions/logout', [DonorSessionController::class, 'logout']);
Route::post('/donor-sessions/me', [DonorSessionController::class, 'me']);
Route::get('/donor-sessions/check-device', [DonorSessionController::class, 'checkDevice']);

// Profile update routes (require authentication - using session_id for now)
Route::put('/donor-sessions/{session_id}/username', [DonorSessionController::class, 'updateUsername']);
Route::put('/donor-sessions/{session_id}/password', [DonorSessionController::class, 'updatePassword']);
Route::post('/donor-sessions/profile', [DonorSessionController::class, 'createOrUpdateProfile']); // ✅ Create/update donor profile for authenticated user
Route::post('/donors/{id}/profile-image', [DonorsController::class, 'uploadProfileImage']);
