<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index($clubId)
    {
        $club = Club::findOrFail($clubId);
        $user = Auth::user();
        \Log::info('EventController@index', [
            'user_id' => $user->id,
            'user_role' => $user->role->name ?? null,
            'club_id' => $club->id,
            'approved' => $user->isStudent() ? $club->students()->where('user_id', $user->id)->wherePivot('status', 'approved')->exists() : null,
            'events_count' => $club->events()->count(),
            'event_ids' => $club->events()->pluck('id')->toArray(),
        ]);
        // If student, check if approved member
        if ($user->isStudent()) {
            $isApproved = $club->students()->where('user_id', $user->id)->wherePivot('status', 'approved')->exists();
            if (!$isApproved) {
                abort(403, 'You are not an approved member of this club.');
            }
        }
        $events = $club->events()->latest()->get();
        return response()->json($events);
    }

    public function store(Request $request, $clubId)
    {
        $club = Club::findOrFail($clubId);
        // Authorization: Only club manager
        if (!$this->isClubManager($club)) {
            abort(403, 'Unauthorized');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|file|mimes:jpeg,jpg|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('event_logos', 'public');
            $data['logo'] = $logoPath;
        } else {
            unset($data['logo']);
        }
        $data['club_id'] = $club->id;
        $event = Event::create($data);
        return response()->json($event, 201);
    }

    public function update(Request $request, $clubId, $eventId)
    {
        $club = Club::findOrFail($clubId);
        $event = Event::where('club_id', $club->id)->findOrFail($eventId);
        if (!$this->isClubManager($club)) {
            abort(403, 'Unauthorized');
        }
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'logo' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $event->update($data);
        return response()->json($event);
    }

    public function destroy($clubId, $eventId)
    {
        $club = Club::findOrFail($clubId);
        $event = Event::where('club_id', $club->id)->findOrFail($eventId);
        if (!$this->isClubManager($club)) {
            abort(403, 'Unauthorized');
        }
        $event->delete();
        return response()->json(['message' => 'Event deleted']);
    }

    private function isClubManager($club)
    {
        $user = Auth::user();
        // Assuming club_manager table has user_id and club_id
        return $club->managers()->where('user_id', $user->id)->exists();
    }
}
