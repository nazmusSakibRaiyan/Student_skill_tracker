<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\RoleTestController;
use App\Http\Controllers\ClubController;
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

// Password Reset Routes
Route::middleware('guest')->group(function () {
    Route::get('password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
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
        // Assign club managers to a club (form and submit)
        Route::get('/admin/clubs/{club}/assign-managers', [ClubController::class, 'showAssignManagersForm'])->name('admin.clubs.assign-managers');
        Route::post('/admin/clubs/{club}/assign-managers', [ClubController::class, 'assignManagers']);
        // Add club manager
        Route::get('/admin/users/create-club-manager', [RoleTestController::class, 'showCreateClubManagerForm'])->name('admin.users.create-club-manager');
        Route::post('/admin/users/create-club-manager', [RoleTestController::class, 'storeClubManager']);
        // Add student
        Route::get('/admin/users/create-student', [RoleTestController::class, 'showCreateStudentForm'])->name('admin.users.create-student');
        Route::post('/admin/users/create-student', [RoleTestController::class, 'storeStudent']);
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
    
    // Club Management - Only admin (master_admin) can create/edit/delete clubs
    Route::middleware(['role:master_admin'])->group(function () {
        Route::get('/clubs', [ClubController::class, 'index'])->name('clubs.index');
        Route::get('/clubs/create', [ClubController::class, 'create'])->name('clubs.create');
        Route::post('/clubs', [ClubController::class, 'store'])->name('clubs.store');
        Route::get('/clubs/{id}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
        Route::put('/clubs/{id}', [ClubController::class, 'update'])->name('clubs.update');
        Route::delete('/clubs/{id}', [ClubController::class, 'destroy'])->name('clubs.destroy');
    });
    
    // Club Manager routes
    Route::middleware(['role:club_manager'])->prefix('club-manager')->name('club-manager.')->group(function () {
        Route::get('/clubs/{club}/edit', [\App\Http\Controllers\ClubManagerClubController::class, 'edit'])->name('club.edit');
        Route::put('/clubs/{club}', [\App\Http\Controllers\ClubManagerClubController::class, 'update'])->name('club.update');
        Route::get('/clubs/{club}/add-student', [\App\Http\Controllers\ClubManagerClubController::class, 'addStudentForm'])->name('club.add-student-form');
        Route::post('/clubs/{club}/add-student', [\App\Http\Controllers\ClubManagerClubController::class, 'addStudent'])->name('club.add-student');
        Route::get('/clubs/{club}/search-students', [\App\Http\Controllers\ClubManagerClubController::class, 'searchStudents'])->name('club.search-students');
        Route::delete('/clubs/{club}/remove-student/{user}', [\App\Http\Controllers\ClubManagerClubController::class, 'removeStudent'])->name('club.remove-student');
    });
    
    // Admin routes for approving/rejecting students
    Route::middleware(['role:master_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pending-students', [\App\Http\Controllers\AdminClubStudentController::class, 'pending'])->name('clubs.pending-students');
        Route::post('/clubs/{club}/approve-student/{user}', [\App\Http\Controllers\AdminClubStudentController::class, 'approve'])->name('clubs.approve-student');
        Route::post('/clubs/{club}/reject-student/{user}', [\App\Http\Controllers\AdminClubStudentController::class, 'reject'])->name('clubs.reject-student');
    });
    
    // Student routes
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/clubs/{club}', function($clubId) {
            $club = \App\Models\Club::findOrFail($clubId);
            // Optionally, check if the user is a member
            if (!auth()->user()->clubs->contains($club->id)) {
                abort(403);
            }
            return view('student.club_details', compact('club'));
        })->name('student.club-details');
    });
});
