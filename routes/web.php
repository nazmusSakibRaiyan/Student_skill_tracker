<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\RoleTestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
    
    // Email Verification Routes
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// RBAC Protected Routes (Require Email Verification)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Master Admin only routes
    Route::middleware(['role:master_admin'])->group(function () {
        Route::get('/admin', [RoleTestController::class, 'adminDashboard'])->name('admin.dashboard');
    });
    
    // Master Admin and Club Manager routes
    Route::middleware(['role:master_admin,club_manager'])->group(function () {
        Route::get('/club-manager', [RoleTestController::class, 'clubManagerDashboard'])->name('club-manager.dashboard');
    });
    
    // All authenticated users (any role)
    Route::get('/student', [RoleTestController::class, 'studentDashboard'])->name('student.dashboard');
    
    // Permission-based route
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users', [RoleTestController::class, 'userManagement'])->name('users.index');
    });
    
    // Show current user's role and permissions
    Route::get('/profile', function () {
        $user = auth()->user();
        return view('profile', compact('user'));
    })->name('profile');
});
