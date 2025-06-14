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
}
