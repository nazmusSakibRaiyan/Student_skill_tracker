<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Models\SkillCategory;
use App\Models\StudentSkill;
use App\Models\SkillPointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SkillManagementController extends Controller
{
    /**
     * Show skill management dashboard for a club
     */
    public function index(Club $club)
    {
        $this->ensureClubManager($club);
        
        $stats = [
            'total_members' => $club->students()->where('status', 'approved')->count(),
            'total_categories' => $club->skillCategories()->where('active', true)->count(),
            'total_points_awarded' => SkillPointHistory::whereHas('studentSkill', function($q) use ($club) {
                $q->where('club_id', $club->id);
            })->sum('points_awarded'),
            'recent_activities' => SkillPointHistory::with(['studentSkill.user', 'studentSkill.skillCategory', 'assignedBy'])
                ->whereHas('studentSkill', function($q) use ($club) {
                    $q->where('club_id', $club->id);
                })
                ->latest()
                ->take(10)
                ->get()
        ];
        
        return view('club-manager.skills.index', compact('club', 'stats'));
    }
    
    /**
     * Show all members of the club
     */
    public function showMembers(Club $club)
    {
        $this->ensureClubManager($club);
        
        $members = $club->students()
            ->where('status', 'approved')
            ->with(['studentSkills' => function($q) use ($club) {
                $q->where('club_id', $club->id)->with('skillCategory');
            }])
            ->paginate(20);
        
        $categories = $club->skillCategories()->where('active', true)->get();
        
        return view('club-manager.skills.members', compact('club', 'members', 'categories'));
    }
    
    /**
     * Show detailed profile of a club member
     */
    public function showMemberProfile(Club $club, User $user)
    {
        $this->ensureClubManager($club);
        $this->ensureClubMember($club, $user);
        
        $memberSkills = StudentSkill::where('club_id', $club->id)
            ->where('user_id', $user->id)
            ->with('skillCategory')
            ->get();
        
        $recentHistory = SkillPointHistory::whereHas('studentSkill', function($q) use ($club, $user) {
            $q->where('club_id', $club->id)->where('user_id', $user->id);
        })
        ->with(['studentSkill.skillCategory', 'assignedBy'])
        ->latest()
        ->take(20)
        ->get();
        
        $totalPoints = $memberSkills->sum('total_points');
        $averageLevel = $memberSkills->avg('level') ?? 0;
        
        return view('club-manager.skills.member-profile', compact(
            'club', 'user', 'memberSkills', 'recentHistory', 'totalPoints', 'averageLevel'
        ));
    }
    
    /**
     * Show form to assign skill points
     */
    public function showAssignForm(Club $club, User $user)
    {
        $this->ensureClubManager($club);
        $this->ensureClubMember($club, $user);
        
        $categories = $club->skillCategories()->where('active', true)->get();
        $currentSkills = StudentSkill::where('club_id', $club->id)
            ->where('user_id', $user->id)
            ->with('skillCategory')
            ->get()
            ->keyBy('skill_category_id');
        
        return view('club-manager.skills.assign-form', compact('club', 'user', 'categories', 'currentSkills'));
    }
    
    /**
     * Assign skill points to a student
     */
    public function assignPoints(Request $request, Club $club, User $user)
    {
        $this->ensureClubManager($club);
        $this->ensureClubMember($club, $user);
        
        $request->validate([
            'skill_category_id' => 'required|exists:skill_categories,id',
            'points' => 'required|integer|min:1|max:50',
            'reason' => 'required|string|max:500',
        ]);
        
        // Verify category belongs to club
        $category = SkillCategory::where('id', $request->skill_category_id)
            ->where('club_id', $club->id)
            ->where('active', true)
            ->firstOrFail();
        
        DB::transaction(function() use ($request, $club, $user, $category) {
            // Get or create student skill record
            $studentSkill = StudentSkill::firstOrCreate([
                'user_id' => $user->id,
                'club_id' => $club->id,
                'skill_category_id' => $category->id,
            ], [
                'total_points' => 0,
                'level' => 1,
                'progress_percentage' => 0,
            ]);
            
            // Add points
            $studentSkill->addPoints($request->points);
            
            // Record the history
            SkillPointHistory::create([
                'student_skill_id' => $studentSkill->id,
                'points_awarded' => $request->points,
                'reason' => $request->reason,
                'assigned_by' => Auth::id(),
                'awarded_at' => now(),
            ]);
        });
        
        return redirect()->route('club-manager.skills.member-profile', [$club, $user])
            ->with('success', "Successfully awarded {$request->points} points in {$category->name} to {$user->name}!");
    }
    
    /**
     * Manage skill categories for the club
     */
    public function manageCategories(Club $club)
    {
        $this->ensureClubManager($club);
        
        $categories = $club->skillCategories()->withCount('studentSkills')->get();
        
        return view('club-manager.skills.categories', compact('club', 'categories'));
    }
    
    /**
     * Store a new skill category
     */
    public function storeCategory(Request $request, Club $club)
    {
        $this->ensureClubManager($club);
        
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'max_points' => 'nullable|integer|min:100|max:10000',
        ]);
        
        // Check if category name already exists for this club
        $exists = $club->skillCategories()->where('name', $request->name)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'A category with this name already exists.');
        }
        
        $club->skillCategories()->create([
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
            'icon' => $request->icon ?? 'star',
            'max_points' => $request->max_points ?? 1000,
            'active' => true,
        ]);
        
        return redirect()->back()->with('success', 'Skill category created successfully!');
    }
    
    /**
     * Update a skill category
     */
    public function updateCategory(Request $request, Club $club, SkillCategory $skillCategory)
    {
        $this->ensureClubManager($club);
        
        if ($skillCategory->club_id !== $club->id) {
            abort(404);
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('skill_categories')->where(function ($query) use ($club) {
                return $query->where('club_id', $club->id);
            })->ignore($skillCategory->id)],
            'description' => 'nullable|string|max:500',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'max_points' => 'nullable|integer|min:100|max:10000',
            'active' => 'boolean',
        ]);
        
        $skillCategory->update($request->only([
            'name', 'description', 'color', 'icon', 'max_points', 'active'
        ]));
        
        return redirect()->back()->with('success', 'Skill category updated successfully!');
    }
    
    /**
     * Delete a skill category
     */
    public function deleteCategory(Club $club, SkillCategory $skillCategory)
    {
        $this->ensureClubManager($club);
        
        if ($skillCategory->club_id !== $club->id) {
            abort(404);
        }
        
        // Check if category has associated student skills
        if ($skillCategory->studentSkills()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category with existing student records. Deactivate it instead.');
        }
        
        $skillCategory->delete();
        
        return redirect()->back()->with('success', 'Skill category deleted successfully!');
    }
    
    /**
     * Show leaderboard for the club
     */
    public function showLeaderboard(Club $club)
    {
        $this->ensureClubManager($club);
        
        $categories = $club->skillCategories()->where('active', true)->get();
        $selectedCategory = request('category');
        
        $query = $club->students()
            ->where('status', 'approved')
            ->with(['studentSkills' => function($q) use ($club, $selectedCategory) {
                $q->where('club_id', $club->id);
                if ($selectedCategory) {
                    $q->where('skill_category_id', $selectedCategory);
                }
                $q->with('skillCategory');
            }]);
        
        if ($selectedCategory) {
            $leaders = $query->get()->map(function($student) use ($selectedCategory) {
                $skill = $student->studentSkills->where('skill_category_id', $selectedCategory)->first();
                return [
                    'student' => $student,
                    'total_points' => $skill ? $skill->total_points : 0,
                    'level' => $skill ? $skill->level : 0,
                    'skill' => $skill
                ];
            })->sortByDesc('total_points')->values();
        } else {
            $leaders = $query->get()->map(function($student) {
                return [
                    'student' => $student,
                    'total_points' => $student->studentSkills->sum('total_points'),
                    'average_level' => round($student->studentSkills->avg('level') ?? 0, 1),
                    'skills' => $student->studentSkills
                ];
            })->sortByDesc('total_points')->values();
        }
        
        return view('club-manager.skills.leaderboard', compact('club', 'categories', 'leaders', 'selectedCategory'));
    }
    
    /**
     * Ensure user is a manager of the club
     */
    private function ensureClubManager(Club $club)
    {
        $user = Auth::user();
        if (!$user->hasRole('master_admin') && !$club->managers()->where('user_id', $user->id)->exists()) {
            abort(403, 'You are not authorized to manage this club.');
        }
    }
    
    /**
     * Ensure user is a member of the club
     */
    private function ensureClubMember(Club $club, User $user)
    {
        if (!$club->students()->where('user_id', $user->id)->where('status', 'approved')->exists()) {
            abort(404, 'User is not an approved member of this club.');
        }
    }
}
