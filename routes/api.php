<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClubManagerController;
use App\Http\Controllers\Api\UserController;

Route::middleware(['auth:sanctum', 'role:master_admin'])->group(function () {
    // Assign club managers to a club
    Route::post('/clubs/{club}/managers', [ClubManagerController::class, 'assignManagers']);
    // Add a new club manager
    Route::post('/users/club-manager', [UserController::class, 'storeClubManager']);
    // Add a new student
    Route::post('/users/student', [UserController::class, 'storeStudent']);
    // Ban a club manager (master admin only)
    Route::post('/ban-club-manager', [ClubManagerController::class, 'banManager']);
});
