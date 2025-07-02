<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        $query = User::with('role');
        if ($role) {
            $query->whereHas('role', function($q) use ($role) {
                $q->where('name', $role);
            });
        }
        $users = $query->orderBy('name')->paginate(20);
        $totalUsers = User::count();
        return view('admin.users', compact('users', 'role', 'totalUsers'));
    }

    public function banClubManager(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubManager = \App\Models\ClubManager::where('user_id', $request->user_id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$clubManager) {
            return back()->with('error', 'Club manager not found.');
        }

        $clubManager->banned = true;
        $clubManager->save();

        return back()->with('success', 'Club manager banned successfully.');
    }

    public function unbanClubManager(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubManager = \App\Models\ClubManager::where('user_id', $request->user_id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$clubManager) {
            return back()->with('error', 'Club manager not found.');
        }

        $clubManager->banned = false;
        $clubManager->save();

        return back()->with('success', 'Club manager unbanned successfully.');
    }

    public function manageRoles(Request $request)
    {
        $role = $request->get('role', 'all');
        
        $query = User::with(['role', 'clubManagers.club']);
        
        // Filter by role if specified
        if ($role !== 'all') {
            $query->whereHas('role', function($q) use ($role) {
                $q->where('name', $role);
            });
        } else {
            // Only show students and club managers (not master admins)
            $query->whereHas('role', function($q) {
                $q->whereIn('name', ['student', 'club_manager']);
            });
        }
        
        $users = $query->orderBy('name')->paginate(20);
        
        // Get statistics
        $stats = [
            'total_students' => User::whereHas('role', fn($q) => $q->where('name', 'student'))->count(),
            'total_club_managers' => User::whereHas('role', fn($q) => $q->where('name', 'club_manager'))->count(),
            'banned_club_managers' => \App\Models\ClubManager::where('banned', true)->count(),
        ];
        
        return view('admin.manage-roles', compact('users', 'role', 'stats'));
    }

    public function deleteUser($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            
            // Prevent deletion of master admin accounts
            if ($user->isMasterAdmin()) {
                return back()->with('error', 'Cannot delete master admin accounts.');
            }

            $userEmail = $user->email;
            $userName = $user->name;

            // Log the deletion
            \Log::info('User deleted by master admin', [
                'deleted_user' => $userEmail,
                'admin' => auth()->user()->email
            ]);

            // Delete the user (cascade should handle related records)
            $user->delete();

            return back()->with('success', "User '{$userName}' ({$userEmail}) has been deleted successfully.");
            
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'user_id' => $user_id,
                'error' => $e->getMessage(),
                'admin' => auth()->user()->email
            ]);
            
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
