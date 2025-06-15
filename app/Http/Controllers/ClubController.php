<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{
    public function create()
    {
        return view('admin.create_club');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clubs,name',
        ]);

        \App\Models\Club::create([
            'name' => $request->name,
        ]);

        return redirect()->route('clubs.index')->with('success', 'Club created successfully.');
    }

    public function edit($id)
    {
        $club = \App\Models\Club::findOrFail($id);
        return view('admin.edit_club', compact('club'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clubs,name,' . $id,
        ]);
        $club = \App\Models\Club::findOrFail($id);
        $club->update(['name' => $request->name]);
        return redirect()->route('clubs.index')->with('success', 'Club updated successfully.');
    }

    public function destroy($id)
    {
        $club = \App\Models\Club::findOrFail($id);
        $club->delete();
        return redirect()->route('clubs.index')->with('success', 'Club deleted successfully.');
    }

    public function index()
    {
        $clubs = \App\Models\Club::all();
        return view('admin.clubs', compact('clubs'));
    }

    public function showAssignManagersForm($id)
    {
        $club = \App\Models\Club::findOrFail($id);
        $managers = \App\Models\User::whereHas('role', function($q) { $q->where('name', 'club_manager'); })->get();
        $assigned = $club->managers->pluck('id')->toArray();
        return view('admin.assign_managers', compact('club', 'managers', 'assigned'));
    }

    public function assignManagers(Request $request, $id)
    {
        $club = \App\Models\Club::findOrFail($id);
        $request->validate([
            'manager_ids' => 'array',
            'manager_ids.*' => 'exists:users,id',
        ]);
        $managerIds = \App\Models\User::whereIn('id', $request->manager_ids ?? [])->whereHas('role', function($q) { $q->where('name', 'club_manager'); })->pluck('id');
        $club->managers()->sync($managerIds);
        return redirect()->route('admin.clubs.assign-managers', $club->id)->with('success', 'Managers assigned successfully.');
    }
}
