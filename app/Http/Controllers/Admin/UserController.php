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
}
