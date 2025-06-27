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
        
        // Add enrollment information for students
        if ($user->isStudent()) {
            $events->each(function ($event) use ($user) {
                $enrollment = $event->enrollments()->where('user_id', $user->id)->first();
                
                // Auto-complete enrollment if event has ended
                if ($enrollment && $enrollment->status === 'enrolled' && $event->end_date <= now()) {
                    $enrollment->markAsCompleted();
                    $enrollment->refresh(); // Refresh to get updated data
                }
                
                $event->user_enrollment = $enrollment;
                $event->can_enroll = $event->allowsEnrollment() && !$enrollment && $event->end_date > now();
                $event->enrollment_count = $event->getEnrollmentCount();
            });
        }
        
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
            'event_type' => 'required|string|max:50',
            'event_type_description' => 'nullable|string|max:255',
            'venue_link' => 'nullable|url|max:255',
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
            'event_type' => 'required|string|max:50',
            'event_type_description' => 'nullable|string|max:255',
            'venue_link' => 'nullable|url|max:255',
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
