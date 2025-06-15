<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleTestController extends Controller
{
    /**
     * Show admin dashboard - only accessible by master admin
     */
    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    /**
     * Show club manager dashboard - accessible by master admin and club manager
     */
    public function clubManagerDashboard()
    {
        $clubs = auth()->user()->managedClubs()->get();
        return view('club-manager.dashboard', compact('clubs'));
    }

    /**
     * Show student dashboard - accessible by all roles
     */
    public function studentDashboard()
    {
        return view('student.dashboard');
    }

    /**
     * Show user management - requires permission
     */
    public function userManagement()
    {
        $totalUsers = \App\Models\User::count();
        return view('admin.users', compact('totalUsers'));
    }

    /**
     * Show form to create a new club manager
     */
    public function showCreateClubManagerForm()
    {
        return view('admin.create_club_manager');
    }

    /**
     * Store a newly created club manager in storage.
     */
    public function storeClubManager(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => 2, // Assuming 2 = club_manager
        ]);
        return redirect()->route('admin.users.create-club-manager')->with('success', 'Club manager created.');
    }

    /**
     * Show form to create a new student
     */
    public function showCreateStudentForm()
    {
        return view('admin.create_student');
    }

    /**
     * Store a newly created student in storage.
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role_id' => 3, // Assuming 3 = student
        ]);
        return redirect()->route('admin.users.create-student')->with('success', 'Student created.');
    }
}
