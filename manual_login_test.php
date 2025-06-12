<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Requests\Auth\LoginRequest;

echo "=== Manual Login Test ===\n\n";

// Test the exact same process that happens in the browser
$email = 'nazmus.sakib.raiyan@g.bracu.ac.bd';
$password = 'Admin123';

// 1. Check if user exists and password is correct
$user = User::where('email', $email)->first();
if (!$user) {
    echo "❌ User not found\n";
    exit;
}

if (!Auth::attempt(['email' => $email, 'password' => $password])) {
    echo "❌ Authentication failed\n";
    exit;
}

echo "✅ Authentication successful\n";
echo "Authenticated user: " . Auth::user()->name . "\n";
echo "Role: " . Auth::user()->role->name . "\n";

// 2. Test the role-based redirect logic
$user = Auth::user();

echo "\nTesting redirect logic:\n";
if ($user->isMasterAdmin()) {
    echo "✅ Should redirect to admin dashboard\n";
    echo "Route URL: " . route('admin.dashboard') . "\n";
} elseif ($user->isClubManager()) {
    echo "✅ Should redirect to club manager dashboard\n";
    echo "Route URL: " . route('club-manager.dashboard') . "\n";
} else {
    echo "✅ Should redirect to student dashboard\n";
    echo "Route URL: " . route('student.dashboard') . "\n";
}

// 3. Test if the admin dashboard route is accessible
echo "\nTesting admin dashboard access:\n";
try {
    $controller = new \App\Http\Controllers\RoleTestController();
    $response = $controller->adminDashboard();
    echo "✅ Admin dashboard controller works\n";
} catch (Exception $e) {
    echo "❌ Admin dashboard error: " . $e->getMessage() . "\n";
}

Auth::logout();
echo "\n=== Test Complete ===\n";
