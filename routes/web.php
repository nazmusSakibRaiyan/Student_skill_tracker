<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\RoleTestController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
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
        // Reports & Analytics
        Route::get('/admin/reports', [\App\Http\Controllers\Admin\ReportsController::class, 'index'])->name('admin.reports');
        // System Logs & Activity
        Route::get('/admin/system-logs', [\App\Http\Controllers\Admin\SystemLogsController::class, 'index'])->name('admin.system-logs');
        Route::get('/admin/system-logs/download', [\App\Http\Controllers\Admin\SystemLogsController::class, 'downloadLog'])->name('admin.system-logs.download');
        Route::post('/admin/system-logs/clear', [\App\Http\Controllers\Admin\SystemLogsController::class, 'clearLog'])->name('admin.system-logs.clear');
        // Assign club managers to a club (form and submit)
        Route::get('/admin/clubs/{club}/assign-managers', [ClubController::class, 'showAssignManagersForm'])->name('admin.clubs.assign-managers');
        Route::post('/admin/clubs/{club}/assign-managers', [ClubController::class, 'assignManagers']);
        // Add club manager
        Route::get('/admin/users/create-club-manager', [RoleTestController::class, 'showCreateClubManagerForm'])->name('admin.users.create-club-manager');
        Route::post('/admin/users/create-club-manager', [RoleTestController::class, 'storeClubManager']);
        // Add student
        Route::get('/admin/users/create-student', [RoleTestController::class, 'showCreateStudentForm'])->name('admin.users.create-student');
        Route::post('/admin/users/create-student', [RoleTestController::class, 'storeStudent']);
        // List all users (with filter)
        Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        // Manage roles
        Route::get('/admin/manage-roles', [\App\Http\Controllers\Admin\UserController::class, 'manageRoles'])->name('admin.manage-roles');
        // Bulk import users
        Route::get('/admin/users/import', [\App\Http\Controllers\Admin\UserController::class, 'showImportForm'])->name('admin.users.import');
        Route::post('/admin/users/import', [\App\Http\Controllers\Admin\UserController::class, 'importUsers'])->name('admin.users.import.process');
        Route::get('/admin/users/import/template', [\App\Http\Controllers\Admin\UserController::class, 'downloadTemplate'])->name('admin.users.import.template');
        // Announcements
        Route::resource('admin/announcements', \App\Http\Controllers\Admin\AnnouncementController::class)->names([
            'index' => 'admin.announcements.index',
            'create' => 'admin.announcements.create',
            'store' => 'admin.announcements.store',
            'show' => 'admin.announcements.show',
            'edit' => 'admin.announcements.edit',
            'update' => 'admin.announcements.update',
            'destroy' => 'admin.announcements.destroy',
        ]);
        Route::post('/admin/announcements/{announcement}/toggle', [\App\Http\Controllers\Admin\AnnouncementController::class, 'toggle'])->name('admin.announcements.toggle');
        // ...existing code...
        Route::delete('/admin/users/{user_id}', [\App\Http\Controllers\Admin\UserController::class, 'deleteUser'])->name('admin.users.delete');
        // Ban club manager (web route)
        Route::post('/admin/ban-club-manager', [\App\Http\Controllers\Admin\UserController::class, 'banClubManager'])->name('admin.ban-club-manager');
        // Unban club manager (web route)
        Route::post('/admin/unban-club-manager', [\App\Http\Controllers\Admin\UserController::class, 'unbanClubManager'])->name('admin.unban-club-manager');
    });
    
    // Master Admin and Club Manager routes
    Route::middleware(['role:master_admin,club_manager'])->group(function () {
        Route::get('/club-manager', [RoleTestController::class, 'clubManagerDashboard'])->name('club-manager.dashboard');
    });
    
    // All authenticated users (any role)
    Route::get('/student', [RoleTestController::class, 'studentDashboard'])->name('student.dashboard');
    
    // Mark announcement as read
    Route::post('/announcements/{announcement}/read', [\App\Http\Controllers\Admin\AnnouncementController::class, 'markAsRead'])->name('announcements.read');
    
    // Permission-based route
    Route::middleware(['permission:manage_users'])->group(function () {
        Route::get('/users', [RoleTestController::class, 'userManagement'])->name('users.index');
    });
    
    // Show current user's role and permissions
    Route::get('/profile', function () {
        $user = auth()->user();
        return view('profile', compact('user'));
    })->name('profile');
    
    // Profile picture upload
    Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture.update');
    
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
        
        // Event management UI for club managers
        Route::get('/clubs/{club}/events', function($clubId) {
            $club = \App\Models\Club::findOrFail($clubId);
            // Optionally, check if the user is a manager
            if (!auth()->user()->managedClubs->contains($club->id)) {
                abort(403);
            }
            return view('club-manager.events', compact('club'));
        })->name('club.events.index');
        Route::get('/clubs/{club}/events/api', [EventController::class, 'index']);
        Route::post('/clubs/{club}/events', [EventController::class, 'store']);
        Route::put('/clubs/{club}/events/{event}', [EventController::class, 'update']);
        Route::delete('/clubs/{club}/events/{event}', [EventController::class, 'destroy']);
        
        // Skill Management Routes for Club Managers
        Route::prefix('clubs/{club}/skills')->name('skills.')->group(function () {
            Route::get('/', [\App\Http\Controllers\SkillManagementController::class, 'index'])->name('index');
            Route::get('/members', [\App\Http\Controllers\SkillManagementController::class, 'showMembers'])->name('members');
            Route::get('/member/{user}', [\App\Http\Controllers\SkillManagementController::class, 'showMemberProfile'])->name('member-profile');
            Route::get('/member/{user}/assign', [\App\Http\Controllers\SkillManagementController::class, 'showAssignForm'])->name('assign-form');
            Route::post('/member/{user}/assign', [\App\Http\Controllers\SkillManagementController::class, 'assignPoints'])->name('assign-points');
            Route::get('/categories', [\App\Http\Controllers\SkillManagementController::class, 'manageCategories'])->name('categories');
            Route::post('/categories', [\App\Http\Controllers\SkillManagementController::class, 'storeCategory'])->name('categories.store');
            Route::put('/categories/{skillCategory}', [\App\Http\Controllers\SkillManagementController::class, 'updateCategory'])->name('categories.update');
            Route::delete('/categories/{skillCategory}', [\App\Http\Controllers\SkillManagementController::class, 'deleteCategory'])->name('categories.delete');
            Route::get('/leaderboard', [\App\Http\Controllers\SkillManagementController::class, 'showLeaderboard'])->name('leaderboard');
        });
        
        // Attendance Management Routes for Club Managers
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'index'])->name('index');
            Route::get('/analytics', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'analytics'])->name('analytics');
            Route::get('/analytics/export', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'exportAnalytics'])->name('analytics.export');
            Route::get('/events/{event}', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'show'])->name('show');
            Route::post('/events/{event}/mark', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'markAttendance'])->name('mark');
            Route::post('/events/{event}/bulk-mark', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'bulkMarkAttendance'])->name('bulk-mark');
            Route::get('/events/{event}/qr-code', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'generateQRCode'])->name('qr-code');
            Route::get('/events/{event}/export', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'exportAttendance'])->name('export');
        });
    });
    
    // QR Code Check-in Routes (Public - no authentication required)
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/qr/{qrCode}', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'qrCheckIn'])->name('qr-checkin');
        Route::post('/qr/{qrCode}', [\App\Http\Controllers\ClubManager\AttendanceController::class, 'processQRCheckIn'])->name('qr-process');
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
        
        // Event viewing for approved students
        Route::get('/student/clubs/{club}/events', [\App\Http\Controllers\EventController::class, 'index'])->name('student.club.events.index');
        
        // Event enrollment routes
        Route::post('/events/{event}/enroll', [\App\Http\Controllers\EventEnrollmentController::class, 'enroll'])->name('events.enroll');
        Route::delete('/events/{event}/cancel', [\App\Http\Controllers\EventEnrollmentController::class, 'cancel'])->name('events.cancel');
        Route::get('/my-enrollments', [\App\Http\Controllers\EventEnrollmentController::class, 'getUserEnrollments'])->name('student.enrollments');
        
        // Student Skill Progress Routes
        Route::prefix('my-skills')->name('student.skills.')->group(function () {
            Route::get('/', [\App\Http\Controllers\StudentSkillController::class, 'index'])->name('index');
            Route::get('/club/{club}', [\App\Http\Controllers\StudentSkillController::class, 'showClubSkills'])->name('club');
            Route::get('/club/{club}/category/{skillCategory}', [\App\Http\Controllers\StudentSkillController::class, 'showCategoryProgress'])->name('category');
            Route::get('/history', [\App\Http\Controllers\StudentSkillController::class, 'showHistory'])->name('history');
            Route::get('/achievements', [\App\Http\Controllers\StudentSkillController::class, 'showAchievements'])->name('achievements');
        });
    });

    // Club Manager routes for managing event enrollments
    Route::middleware(['role:club_manager'])->group(function () {
        Route::get('/events/{event}/enrollments', [\App\Http\Controllers\EventEnrollmentController::class, 'getEventEnrollments'])->name('events.enrollments');
        Route::post('/events/{event}/enrollments/{user}/complete', [\App\Http\Controllers\EventEnrollmentController::class, 'markCompleted'])->name('events.mark-completed');
    });
});
