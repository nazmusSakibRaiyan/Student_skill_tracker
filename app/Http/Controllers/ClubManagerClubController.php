<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Club;
use App\Notifications\StudentAddedToClub;

class ClubManagerClubController extends Controller
{
    public function edit($id)
    {
        $club = Club::findOrFail($id);
        // Optionally, check if the user is a manager of this club
        return view('club-manager.edit_club', compact('club'));
    }

    public function update(Request $request, $id)
    {
        $club = Club::findOrFail($id);
        // Optionally, check if the user is a manager of this club
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('club_logos', 'public');
        }
        $club->update($data);
        return redirect()->route('club-manager.dashboard')->with('success', 'Club updated successfully.');
    }

    public function addStudentForm($id)
    {
        $club = Club::findOrFail($id);
        return view('club-manager.add_student', compact('club'));
    }

    public function addStudent(Request $request, $id)
    {
        $club = Club::findOrFail($id);
        $request->validate([
            'user_ids' => 'required|string',
        ]);
        $userIds = collect(explode(',', $request->user_ids))->filter();
        $added = 0;
        foreach ($userIds as $userId) {
            $student = \App\Models\User::find($userId);
            if ($student && !$club->students()->where('user_id', $student->id)->exists()) {
                $club->students()->attach($student->id, ['status' => 'pending']);
                // Notify admin(s)
                $admins = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'master_admin'); })->get();
                foreach ($admins as $admin) {
                    $admin->notify(new \App\Notifications\StudentAddedToClub($club, $student));
                }
                $added++;
            }
        }
        if ($added > 0) {
            return back()->with('success', "$added student(s) added and pending admin approval.");
        } else {
            return back()->with('error', 'No students were added (already in club or invalid).');
        }
    }

    public function searchStudents(Request $request, $id)
    {
        $club = Club::findOrFail($id);
        $query = $request->input('q');
        $students = \App\Models\User::whereHas('role', function($q){ $q->where('name', 'student'); })
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%");
            })
            ->whereDoesntHave('clubs', function($q) use ($club) {
                $q->where('club_id', $club->id);
            })
            ->limit(10)
            ->get();
        if ($students->isEmpty()) {
            return response()->json(['debug' => 'No students found', 'query' => $query, 'club_id' => $club->id]);
        }
        return response()->json($students);
    }

    public function removeStudent(Request $request, $clubId, $userId)
    {
        $club = Club::findOrFail($clubId);
        // Optionally, check if the user is a manager of this club
        $club->students()->detach($userId);
        return back()->with('success', 'Student removed from club.');
    }
}