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
        $clubs = auth()->user()->managedClubs()->with('students')->get();
        return view('club-manager.dashboard', compact('clubs'));
    }

    /**
     * Show student dashboard - accessible by all roles
     */
    public function studentDashboard()
    {
        $user = auth()->user();
        
        // Get recent event activities for students
        $recentActivities = [];
        $totalEnrollments = 0;
        $completedEvents = 0;
        
        if ($user->isStudent()) {
            // Auto-complete any enrollments for events that have ended
            $user->eventEnrollments()
                 ->where('status', 'enrolled')
                 ->whereHas('event', function($q) {
                     $q->where('end_date', '<=', now());
                 })
                 ->get()
                 ->each(function($enrollment) {
                     $enrollment->markAsCompleted();
                 });
            
            $recentActivities = $user->getRecentEventActivities(5);
            $totalEnrollments = $user->eventEnrollments()->count();
            $completedEvents = $user->eventEnrollments()->where('status', 'completed')->count();
        }
        
        return view('student.dashboard', compact('recentActivities', 'totalEnrollments', 'completedEvents'));
    }

    /**
     * Show user management - requires permission
     */
    public function userManagement(Request $request)
    {
        $totalUsers = \App\Models\User::count();
        $role = $request->get('role');
        $query = \App\Models\User::with('role');
        if ($role) {
            $query->whereHas('role', function($q) use ($role) {
                $q->where('name', $role);
            });
        }
        $users = $query->orderBy('name')->paginate(20);
        return view('admin.users', compact('totalUsers', 'users', 'role'));
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
