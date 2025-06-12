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
        return view('club-manager.dashboard');
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
        return view('admin.users');
    }
}
