<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\SkillCategory;
use App\Models\StudentSkill;
use App\Models\SkillPointHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSkillController extends Controller
{
    /**
     * Show student's skill dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get all clubs the student is approved for
        $clubs = $user->clubs()->wherePivot('status', 'approved')->with([
            'skillCategories' => function($q) {
                $q->where('active', true);
            }
        ])->get();
        
        // Get all student skills
        $studentSkills = StudentSkill::where('user_id', $user->id)
            ->with(['club', 'skillCategory'])
            ->get()
            ->groupBy('club_id');
        
        // Calculate overall stats
        $totalPoints = StudentSkill::where('user_id', $user->id)->sum('total_points');
        $averageLevel = StudentSkill::where('user_id', $user->id)->avg('level') ?? 0;
        $totalClubs = $clubs->count();
        $totalCategories = $clubs->sum(function($club) {
            return $club->skillCategories->count();
        });
        
        // Recent activity
        $recentActivity = SkillPointHistory::whereHas('studentSkill', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['studentSkill.skillCategory', 'studentSkill.club', 'assignedBy'])
        ->latest()
        ->take(10)
        ->get();
        
        return view('student.skills.index', compact(
            'clubs', 'studentSkills', 'totalPoints', 'averageLevel', 
            'totalClubs', 'totalCategories', 'recentActivity'
        ));
    }
    
    /**
     * Show skills for a specific club
     */
    public function showClubSkills(Club $club)
    {
        $user = Auth::user();
        
        // Ensure user is an approved member
        if (!$club->students()->where('user_id', $user->id)->where('status', 'approved')->exists()) {
            abort(403, 'You are not a member of this club.');
        }
        
        $categories = $club->skillCategories()->where('active', true)->get();
        
        $studentSkills = StudentSkill::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->with('skillCategory')
            ->get()
            ->keyBy('skill_category_id');
        
        // Get club leaderboard position
        $allMembers = $club->students()
            ->where('status', 'approved')
            ->with(['studentSkills' => function($q) use ($club) {
                $q->where('club_id', $club->id);
            }])
            ->get()
            ->map(function($member) {
                return [
                    'user' => $member,
                    'total_points' => $member->studentSkills->sum('total_points')
                ];
            })
            ->sortByDesc('total_points')
            ->values();
        
        $userPosition = $allMembers->search(function($member) use ($user) {
            return $member['user']->id === $user->id;
        }) + 1;
        
        return view('student.skills.club', compact(
            'club', 'categories', 'studentSkills', 'allMembers', 'userPosition'
        ));
    }
    
    /**
     * Show progress for a specific category
     */
    public function showCategoryProgress(Club $club, SkillCategory $skillCategory)
    {
        $user = Auth::user();
        
        // Ensure user is an approved member and category belongs to club
        if (!$club->students()->where('user_id', $user->id)->where('status', 'approved')->exists()) {
            abort(403, 'You are not a member of this club.');
        }
        
        if ($skillCategory->club_id !== $club->id) {
            abort(404);
        }
        
        $studentSkill = StudentSkill::where('user_id', $user->id)
            ->where('club_id', $club->id)
            ->where('skill_category_id', $skillCategory->id)
            ->first();
        
        $history = SkillPointHistory::whereHas('studentSkill', function($q) use ($user, $club, $skillCategory) {
            $q->where('user_id', $user->id)
              ->where('club_id', $club->id)
              ->where('skill_category_id', $skillCategory->id);
        })
        ->with('assignedBy')
        ->latest()
        ->paginate(20);
        
        // Get category leaderboard
        $categoryLeaders = $club->students()
            ->where('status', 'approved')
            ->whereHas('studentSkills', function($q) use ($club, $skillCategory) {
                $q->where('club_id', $club->id)->where('skill_category_id', $skillCategory->id);
            })
            ->with(['studentSkills' => function($q) use ($club, $skillCategory) {
                $q->where('club_id', $club->id)->where('skill_category_id', $skillCategory->id);
            }])
            ->get()
            ->map(function($member) use ($skillCategory) {
                $skill = $member->studentSkills->first();
                return [
                    'user' => $member,
                    'skill' => $skill,
                    'total_points' => $skill ? $skill->total_points : 0,
                    'level' => $skill ? $skill->level : 0
                ];
            })
            ->sortByDesc('total_points')
            ->take(10);
        
        $userRank = $categoryLeaders->search(function($leader) use ($user) {
            return $leader['user']->id === $user->id;
        }) + 1;
        
        return view('student.skills.category', compact(
            'club', 'skillCategory', 'studentSkill', 'history', 'categoryLeaders', 'userRank'
        ));
    }
    
    /**
     * Show skill point history
     */
    public function showHistory()
    {
        $user = Auth::user();
        
        $history = SkillPointHistory::whereHas('studentSkill', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->with(['studentSkill.skillCategory', 'studentSkill.club', 'assignedBy'])
        ->latest()
        ->paginate(30);
        
        return view('student.skills.history', compact('history'));
    }
    
    /**
     * Show achievements and milestones
     */
    public function showAchievements()
    {
        $user = Auth::user();
        
        $skills = StudentSkill::where('user_id', $user->id)
            ->with(['skillCategory', 'club'])
            ->get();
        
        $achievements = [];
        
        foreach ($skills as $skill) {
            // Level milestones
            if ($skill->level >= 5) {
                $achievements[] = [
                    'type' => 'level',
                    'title' => "Level {$skill->level} in {$skill->skillCategory->name}",
                    'description' => "Reached level {$skill->level} in {$skill->skillCategory->name} at {$skill->club->name}",
                    'icon' => 'trophy',
                    'color' => $this->getLevelColor($skill->level),
                    'earned_at' => $skill->updated_at
                ];
            }
            
            // Point milestones
            $pointMilestones = [100, 250, 500, 1000, 2000];
            foreach ($pointMilestones as $milestone) {
                if ($skill->total_points >= $milestone) {
                    $achievements[] = [
                        'type' => 'points',
                        'title' => "{$milestone} Points in {$skill->skillCategory->name}",
                        'description' => "Earned {$milestone} points in {$skill->skillCategory->name}",
                        'icon' => 'star',
                        'color' => $this->getPointsColor($milestone),
                        'earned_at' => $skill->updated_at
                    ];
                }
            }
        }
        
        // Sort by earned date (newest first)
        $achievements = collect($achievements)->sortByDesc('earned_at')->unique('title');
        
        // Overall stats for badges
        $totalPoints = $skills->sum('total_points');
        $maxLevel = $skills->max('level') ?? 0;
        $clubsCount = $skills->pluck('club_id')->unique()->count();
        
        $badges = [];
        
        // Total points badges
        $pointBadges = [
            1000 => ['title' => 'Point Collector', 'color' => 'blue'],
            5000 => ['title' => 'Point Master', 'color' => 'purple'],
            10000 => ['title' => 'Point Legend', 'color' => 'yellow'],
        ];
        
        foreach ($pointBadges as $points => $badge) {
            if ($totalPoints >= $points) {
                $badges[] = array_merge($badge, [
                    'description' => "Earned {$points}+ total points across all clubs",
                    'icon' => 'award'
                ]);
            }
        }
        
        // Multi-club badges
        if ($clubsCount >= 2) {
            $badges[] = [
                'title' => 'Multi-Club Member',
                'description' => "Active member in {$clubsCount} clubs",
                'icon' => 'users',
                'color' => 'green'
            ];
        }
        
        return view('student.skills.achievements', compact('achievements', 'badges', 'skills'));
    }
    
    /**
     * Get color for level achievements
     */
    private function getLevelColor($level)
    {
        if ($level >= 10) return 'gold';
        if ($level >= 7) return 'purple';
        if ($level >= 5) return 'blue';
        return 'green';
    }
    
    /**
     * Get color for point achievements
     */
    private function getPointsColor($points)
    {
        if ($points >= 1000) return 'gold';
        if ($points >= 500) return 'purple';
        if ($points >= 250) return 'blue';
        return 'green';
    }
}
