<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Storage;

// Serve storage files - this route must be registered before Laravel's default storage route
// This ensures our custom route takes precedence
Route::match(['GET', 'OPTIONS', 'HEAD'], '/storage/{path}', function ($path) {
    // Handle OPTIONS request for CORS preflight
    if (request()->isMethod('OPTIONS')) {
        return response('', 200)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS, HEAD')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }
    
    // Decode the path in case it's URL encoded
    $decodedPath = urldecode($path);
    
    // Prevent directory traversal attacks
    if (str_contains($decodedPath, '..') || str_contains($decodedPath, "\0")) {
        abort(403, 'Invalid path');
    }
    
    // Build the full file path
    $filePath = storage_path('app/public/' . $decodedPath);
    
    // Normalize path separators for Windows
    $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $filePath);
    
    // Check if file exists using direct file system check
    if (!file_exists($filePath) || !is_file($filePath)) {
        \Log::warning('Storage file not found', [
            'requested_path' => $path,
            'decoded_path' => $decodedPath,
            'full_path' => $filePath,
            'exists' => file_exists($filePath),
            'is_file' => is_file($filePath),
        ]);
        abort(404, 'File not found');
    }
    
    // Determine MIME type
    $mimeType = mime_content_type($filePath);
    if (!$mimeType) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
        ];
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
    }
    
    // Use response()->file() which properly handles file serving with correct headers
    return response()->file($filePath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, OPTIONS, HEAD',
        'Access-Control-Allow-Headers' => 'Content-Type',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->where('path', '.*')->name('storage.serve');

// Welcome page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// About page
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');

// Projects page
Route::view('/projects', 'projects')->name('projects');

// Admin root route - redirect based on authentication status
Route::get('/admin', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
})->name('admin.index');

// Admin routes
Route::prefix('admin')->group(function () {
    // Admin login (public)
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
    
    // Protected admin routes
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // The alumni upload is now a Livewire component on the dashboard
        
        // Faculty Management

        
        // Department Management
        Route::get('/departments', [\App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('admin.department.index');
        
        // Projects management
        Route::get('/projects', [ProjectController::class, 'index'])->name('admin.projects');
        Route::get('/projects/donations', [\App\Http\Controllers\ProjectController::class, 'donationsOverview'])->name('admin.projects.donations');
        
        // Project Categories
        Route::get('/project-categories', [\App\Http\Controllers\Admin\ProjectCategoryController::class, 'index'])->name('admin.project-categories.index');
        
        // Donor statistics
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
        
        // Email/SMS management
        // Email/SMS management
        Route::prefix('notifications')->name('admin.notifications.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('index');
            Route::get('/templates', [\App\Http\Controllers\Admin\NotificationController::class, 'templates'])->name('templates');
            Route::get('/templates/create', [\App\Http\Controllers\Admin\NotificationController::class, 'createTemplate'])->name('templates.create');
            Route::get('/templates/{id}/edit', [\App\Http\Controllers\Admin\NotificationController::class, 'editTemplate'])->name('templates.edit');
            Route::get('/send', [\App\Http\Controllers\Admin\NotificationController::class, 'send'])->name('send');
            Route::get('/logs', [\App\Http\Controllers\Admin\NotificationController::class, 'logs'])->name('logs');
        });
        
        // Donor Management
        Route::get('/donors', [\App\Http\Controllers\Admin\DonorController::class, 'index'])->name('admin.donors.index');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        
        // Role Management
        Route::get('/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index');
        
        // Permission Management
        Route::get('/permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('admin.permissions.index');
    });
});

// Donations (public)
Route::post('/donations', [DonorController::class, 'makeDonation']);

// If you want donation history/summary to remain protected, leave them inside the group:
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/donations/history', [DonorController::class, 'donationHistory']);
    Route::get('/donations/summary', [DonorController::class, 'donationSummary']);
});

// Redirect /login to /admin/login for session expiry or direct access
Route::get('/login', function () {
    return redirect()->route('admin.login');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});
