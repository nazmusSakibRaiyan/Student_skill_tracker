<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Models\User;

class ClubManagerController extends Controller
{
    // Assign one or more managers to a club
    public function assignManagers(Request $request, Club $club)
    {
        $request->validate([
            'manager_ids' => 'required|array',
            'manager_ids.*' => 'exists:users,id',
        ]);
        // Only allow users with club_manager role
        $managerIds = User::whereIn('id', $request->manager_ids)
            ->whereHas('role', function($q) { $q->where('name', 'club_manager'); })
            ->pluck('id');
        $club->managers()->sync($managerIds);
        return response()->json(['message' => 'Managers assigned successfully.']);
    }

    /**
     * Ban a club manager (master admin only)
     */
    public function banManager(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubManager = \App\Models\ClubManager::where('user_id', $request->user_id)
            ->where('club_id', $request->club_id)
            ->first();

        if (!$clubManager) {
            return response()->json(['message' => 'Club manager not found.'], 404);
        }

        $clubManager->banned = true;
        $clubManager->save();

        return response()->json(['message' => 'Club manager banned successfully.']);
    }
}
