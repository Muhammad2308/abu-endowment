<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\FacultyController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DonorController;

// Welcome page
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

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
        Route::get('/faculty', [FacultyController::class, 'index'])->name('admin.faculty.index');
        
        // Projects management
        Route::get('/projects', [ProjectController::class, 'index'])->name('admin.projects');
        
        // Donor statistics
        Route::get('/statistics', [AdminController::class, 'statistics'])->name('admin.statistics');
        
        // Email/SMS management
        Route::get('/notifications', [AdminController::class, 'notifications'])->name('admin.notifications');
        
        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
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
