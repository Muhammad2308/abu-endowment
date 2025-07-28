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

// Public routes (no authentication required) - like search endpoints
Route::post('/donors', [DonorController::class, 'store']); // Create donor account
Route::get('/donors/search/{reg_number}', [DonorController::class, 'searchByRegNumber']);
Route::get('/donors/search/phone/{phone}', [DonorController::class, 'searchByPhone']);
Route::get('/donors/search/email/{email}', [DonorController::class, 'searchByEmail']);
Route::put('/donors/{id}', [DonorController::class, 'update']); // Make this public like search

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
    return \App\Models\Project::with(['photos'])->orderBy('created_at', 'desc')->get()->map(function ($project) {
        return [
            'id' => $project->id,
            'project_title' => $project->project_title,
            'project_description' => $project->project_description,
            'icon_image_url' => $project->icon_image_url,
            'created_at' => $project->created_at,
            'photos' => $project->photos->map(function ($photo) {
                return [
                    'image_url' => $photo->image_url,
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
    
    // Donations
    Route::post('/donations', [DonorController::class, 'makeDonation']);
    Route::get('/donations/summary', [DonorController::class, 'donationSummary']);
    
    // Rankings
    Route::get('/rankings', [RankingController::class, 'index']);
    Route::get('/rankings/top-donors', [RankingController::class, 'topDonors']);
    Route::get('/rankings/faculty', [RankingController::class, 'facultyRankings']);
    Route::get('/rankings/department', [RankingController::class, 'departmentRankings']);
    
    // Faculty and Department data
    Route::get('/faculties', function () {
        return \App\Models\Faculty::with('visions')->get();
    });
    
    Route::get('/departments', function () {
        return \App\Models\Department::with('visions')->get();
    });
    
    Route::get('/faculties/{faculty}/visions', function (\App\Models\Faculty $faculty) {
        return $faculty->visions;
    });
    
    Route::get('/departments/{department}/visions', function (\App\Models\Department $department) {
        return $department->visions;
    });
    
    // Payment routes (protected)
    Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
    Route::get('/payments/verify/{reference}', [PaymentController::class, 'verify']);
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
Route::get('/device/check', [DeviceController::class, 'check']);
Route::post('/device/session', [DeviceController::class, 'createSession']);
Route::get('/device/donor-info', [DeviceController::class, 'getDonorInfo']);

// Payment routes (public - no authentication required)
Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
Route::get('/payments/verify/{reference}', [PaymentController::class, 'verify']);
Route::get('/payments/test', [PaymentController::class, 'test']); // Test configuration

// Webhook (no CSRF protection needed)
Route::post('/payments/webhook', [PaymentController::class, 'webhook'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::get('/donor/{donor}/messages', [DonorMessageController::class, 'index']);
Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::get('/sms-messages', [SmsController::class, 'getMessages']);
