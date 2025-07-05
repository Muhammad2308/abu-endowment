<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Projects (public)
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);

// Donations (public)
Route::post('/donations', [DonorController::class, 'makeDonation']);

// Donor types
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

// Projects with photos for slider
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

// This should be outside any Route::middleware('auth:sanctum') group
Route::get('/donors/search/{reg_number}', [DonorController::class, 'searchByRegNumber']);
Route::get('/donors/search/phone/{phone}', [DonorController::class, 'searchByPhone']);
Route::get('/donors/search/email/{email}', [DonorController::class, 'searchByEmail']);

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Donor management
    Route::apiResource('donors', DonorController::class);
    // Route::get('/donors/search/{reg_number}', [DonorController::class, 'searchByRegNumber']);

    Route::get('/donors/faculty/{faculty_id}', [DonorController::class, 'getByFaculty']);
    Route::get('/donors/department/{department_id}', [DonorController::class, 'getByDepartment']);
    
    // Donations (protected)
    Route::get('/donations/history', [DonorController::class, 'donationHistory']);
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

// Verification routes
Route::post('/verification/send-sms', [VerificationController::class, 'sendSMS']);
Route::post('/verification/send-email', [VerificationController::class, 'sendEmail']);
Route::post('/verification/verify-sms', [VerificationController::class, 'verifySMS']);
Route::post('/verification/verify-email', [VerificationController::class, 'verifyEmail']);

// Session routes
Route::post('/session/create', [SessionController::class, 'create']);
Route::post('/session/check', [SessionController::class, 'check']);
Route::post('/session/login', [SessionController::class, 'login']);
Route::post('/session/logout', [SessionController::class, 'logout']);

// Donor update route
Route::put('/donors/{id}', [DonorController::class, 'update']);

// Payment routes
Route::post('/payments/initialize', [PaymentController::class, 'initialize']);
Route::get('/payments/verify/{reference}', [PaymentController::class, 'verify']);
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);
