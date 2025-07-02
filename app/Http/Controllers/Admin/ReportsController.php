<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        // Get overview statistics
        $stats = $this->getOverviewStats();
        
        return view('admin.reports', compact('stats'));
    }
    
    /**
     * Get overview statistics for the reports dashboard
     */
    private function getOverviewStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        return [
            'total_users' => DB::table('users')->count(),
            'new_users_this_month' => DB::table('users')->where('created_at', '>=', $currentMonth)->count(),
            'new_users_last_month' => DB::table('users')
                ->whereBetween('created_at', [$lastMonth, $currentMonth])
                ->count(),
                
            'total_clubs' => DB::table('clubs')->count(),
            'new_clubs_this_month' => DB::table('clubs')->where('created_at', '>=', $currentMonth)->count(),
            
            'total_events' => DB::table('events')->count(),
            'new_events_this_month' => DB::table('events')->where('created_at', '>=', $currentMonth)->count(),
            
            'total_enrollments' => DB::table('event_enrollments')->count(),
            'new_enrollments_this_month' => DB::table('event_enrollments')->where('created_at', '>=', $currentMonth)->count(),
            'completed_enrollments' => DB::table('event_enrollments')->where('status', 'completed')->count(),
            
            'verified_users' => DB::table('users')->whereNotNull('email_verified_at')->count(),
            'unverified_users' => DB::table('users')->whereNull('email_verified_at')->count(),
        ];
    }
}
