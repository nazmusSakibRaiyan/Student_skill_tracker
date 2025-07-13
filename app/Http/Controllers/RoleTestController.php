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

        // Get skill data for all users (not just students)
        // Get all clubs the user is approved for
        $clubs = $user->clubs()->wherePivot('status', 'approved')->with([
            'skillCategories' => function($q) {
                $q->where('active', true);
            }
        ])->get();
        
        // Get all student skills
        $studentSkills = \App\Models\StudentSkill::where('user_id', $user->id)
            ->with(['club', 'skillCategory'])
            ->get()
            ->groupBy('club_id');
        
        // Calculate overall stats
        $totalPoints = \App\Models\StudentSkill::where('user_id', $user->id)->sum('total_points');
        $averageLevel = \App\Models\StudentSkill::where('user_id', $user->id)->avg('level') ?? 0;
        $totalClubs = $clubs->count();
        $totalCategories = $clubs->sum(function($club) {
            return $club->skillCategories->count();
        });
        
        // Recent skill activity
        $recentSkillActivity = \App\Models\SkillPointHistory::whereHas('studentSkill', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['studentSkill.skillCategory', 'studentSkill.club', 'assignedBy'])
        ->latest()
        ->take(5)
        ->get();
        
        return view('student.dashboard', compact(
            'recentActivities', 
            'totalEnrollments', 
            'completedEvents',
            'clubs',
            'studentSkills', 
            'totalPoints', 
            'averageLevel', 
            'totalClubs', 
            'totalCategories',
            'recentSkillActivity'
        ));
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
        
        // Calculate real role distribution statistics from database
        $studentCount = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'student');
        })->count();
        
        $clubManagerCount = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'club_manager');
        })->count();
        
        $adminCount = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'master_admin');
        })->count();
        
        // Calculate percentages
        $studentPercentage = $totalUsers > 0 ? round(($studentCount / $totalUsers) * 100) : 0;
        $clubManagerPercentage = $totalUsers > 0 ? round(($clubManagerCount / $totalUsers) * 100) : 0;
        $adminPercentage = $totalUsers > 0 ? round(($adminCount / $totalUsers) * 100) : 0;
        
        $roleStats = [
            'students' => [
                'count' => $studentCount,
                'percentage' => $studentPercentage
            ],
            'club_managers' => [
                'count' => $clubManagerCount,
                'percentage' => $clubManagerPercentage
            ],
            'admins' => [
                'count' => $adminCount,
                'percentage' => $adminPercentage
            ]
        ];
        
        return view('admin.users', compact('totalUsers', 'users', 'role', 'roleStats'));
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
