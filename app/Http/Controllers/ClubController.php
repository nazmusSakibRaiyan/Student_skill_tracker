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
        $club = \App\Models\Club::with('managers')->findOrFail($id);
        $managers = \App\Models\User::whereHas('role', function($q) { 
            $q->where('name', 'club_manager'); 
        })->get();
        $assigned = $club->managers->pluck('id')->toArray();
        
        // Debug logging
        \Log::info('Assign Managers Form', [
            'club_id' => $club->id,
            'club_name' => $club->name,
            'total_managers' => $managers->count(),
            'assigned_managers' => $assigned
        ]);
        
        return view('admin.assign_managers', compact('club', 'managers', 'assigned'));
    }

    public function assignManagers(Request $request, $id)
    {
        try {
            $club = \App\Models\Club::findOrFail($id);
            
            $request->validate([
                'manager_ids' => 'nullable|array',
                'manager_ids.*' => 'exists:users,id',
            ]);

            // Get the submitted manager IDs (default to empty array if none selected)
            $submittedManagerIds = $request->manager_ids ?? [];
            
            // Validate that submitted IDs are actually club managers
            $validManagerIds = \App\Models\User::whereIn('id', $submittedManagerIds)
                ->whereHas('role', function($q) { 
                    $q->where('name', 'club_manager'); 
                })->pluck('id')->toArray();

            // Log for debugging
            \Log::info('Club Manager Assignment', [
                'club_id' => $club->id,
                'submitted_ids' => $submittedManagerIds,
                'valid_ids' => $validManagerIds,
                'admin' => auth()->user()->email
            ]);

            // Sync the managers (this will add new ones and remove unchecked ones)
            $club->managers()->sync($validManagerIds);

            return redirect()->route('admin.clubs.assign-managers', $club->id)
                ->with('success', 'Managers assigned successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Error assigning club managers', [
                'club_id' => $id,
                'error' => $e->getMessage(),
                'admin' => auth()->user()->email
            ]);
            
            return redirect()->back()
                ->with('error', 'Failed to assign managers: ' . $e->getMessage());
        }
    }
}
