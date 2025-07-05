<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\User;
use App\Models\Club;
use App\Models\Role;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $roles = Role::all();
        $clubs = Club::all();
        $users = User::with('role')->get();

        return view('admin.announcements.create', compact('roles', 'clubs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,emergency',
            'target_type' => 'required|in:all,role,club,individual',
            'expires_at' => 'nullable|date|after:now',
            'target_roles' => 'array',
            'target_clubs' => 'array',
            'target_users' => 'array',
        ]);

        $targetFilters = [];
        
        switch ($request->target_type) {
            case 'role':
                $targetFilters['roles'] = $request->target_roles ?? [];
                break;
            case 'club':
                $targetFilters['clubs'] = array_map('intval', $request->target_clubs ?? []);
                break;
            case 'individual':
                $targetFilters['users'] = array_map('intval', $request->target_users ?? []);
                break;
        }

        $announcement = Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'priority' => $request->priority,
            'target_type' => $request->target_type,
            'target_filters' => $targetFilters,
            'created_by' => auth()->id(),
            'expires_at' => $request->expires_at,
        ]);

        \Log::info('Announcement created', [
            'announcement_id' => $announcement->id,
            'title' => $announcement->title,
            'target_type' => $announcement->target_type,
            'created_by' => auth()->user()->email
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('creator');
        
        // Get read statistics
        $totalReads = $announcement->readByUsers()->count();
        $recentReads = $announcement->readByUsers()
            ->orderByPivot('read_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.announcements.show', compact('announcement', 'totalReads', 'recentReads'));
    }

    public function edit(Announcement $announcement)
    {
        $roles = Role::all();
        $clubs = Club::all();
        $users = User::with('role')->get();

        return view('admin.announcements.edit', compact('announcement', 'roles', 'clubs', 'users'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,emergency',
            'target_type' => 'required|in:all,role,club,individual',
            'expires_at' => 'nullable|date|after:now',
            'is_active' => 'boolean',
            'target_roles' => 'array',
            'target_clubs' => 'array',
            'target_users' => 'array',
        ]);

        $targetFilters = [];
        
        switch ($request->target_type) {
            case 'role':
                $targetFilters['roles'] = $request->target_roles ?? [];
                break;
            case 'club':
                $targetFilters['clubs'] = array_map('intval', $request->target_clubs ?? []);
                break;
            case 'individual':
                $targetFilters['users'] = array_map('intval', $request->target_users ?? []);
                break;
        }

        $announcement->update([
            'title' => $request->title,
            'message' => $request->message,
            'priority' => $request->priority,
            'target_type' => $request->target_type,
            'target_filters' => $targetFilters,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    public function toggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);

        $status = $announcement->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Announcement {$status} successfully!");
    }

    public function markAsRead(Request $request, Announcement $announcement)
    {
        $announcement->markAsReadBy(auth()->user());

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }
}
