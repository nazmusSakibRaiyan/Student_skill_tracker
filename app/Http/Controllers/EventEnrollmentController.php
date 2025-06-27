<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventEnrollmentController extends Controller
{
    /**
     * Enroll student in an event
     */
    public function enroll(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        // Check if user is a student
        if (!$user->isStudent()) {
            return response()->json(['error' => 'Only students can enroll in events'], 403);
        }

        // Check if event allows enrollment (only seminars, workshops, contests)
        if (!$event->allowsEnrollment()) {
            return response()->json(['error' => 'This event type does not allow enrollment'], 400);
        }

        // Check if user is approved member of the club
        $isApproved = $event->club->students()
                           ->where('user_id', $user->id)
                           ->wherePivot('status', 'approved')
                           ->exists();

        if (!$isApproved) {
            return response()->json(['error' => 'You must be an approved member of this club to enroll'], 403);
        }

        // Check if already enrolled
        if ($event->isUserEnrolled($user->id)) {
            return response()->json(['error' => 'You are already enrolled in this event'], 400);
        }

        // Check if event has not ended
        if ($event->end_date < now()) {
            return response()->json(['error' => 'Cannot enroll in past events'], 400);
        }

        // Create enrollment
        $enrollment = EventEnrollment::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'enrolled',
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'message' => 'Successfully enrolled in event',
            'enrollment' => $enrollment->load('event')
        ]);
    }

    /**
     * Cancel enrollment
     */
    public function cancel(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        $enrollment = EventEnrollment::where('event_id', $event->id)
                                   ->where('user_id', $user->id)
                                   ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'You are not enrolled in this event'], 404);
        }

        if ($enrollment->status === 'completed') {
            return response()->json(['error' => 'Cannot cancel completed event'], 400);
        }

        $enrollment->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Successfully cancelled enrollment'
        ]);
    }

    /**
     * Mark enrollment as completed (for club managers)
     */
    public function markCompleted(Request $request, $eventId, $userId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        // Check if user is a club manager of this event's club
        if (!$event->club->managers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Only club managers can mark completions'], 403);
        }

        $enrollment = EventEnrollment::where('event_id', $event->id)
                                   ->where('user_id', $userId)
                                   ->first();

        if (!$enrollment) {
            return response()->json(['error' => 'Student is not enrolled in this event'], 404);
        }

        $enrollment->markAsCompleted();

        return response()->json([
            'message' => 'Enrollment marked as completed',
            'enrollment' => $enrollment->load('user', 'event')
        ]);
    }

    /**
     * Get user's enrollments
     */
    public function getUserEnrollments()
    {
        $user = Auth::user();
        
        // Auto-complete any enrollments for events that have ended
        $user->eventEnrollments()
             ->where('status', 'enrolled')
             ->whereHas('event', function($q) {
                 $q->where('end_date', '<=', now());
             })
             ->get()
             ->each(function($enrollment) {
                 $enrollment->markAsCompleted();
             });
        
        $enrollments = $user->eventEnrollments()
                           ->with(['event.club'])
                           ->latest('created_at')
                           ->get();

        return response()->json($enrollments);
    }

    /**
     * Get event enrollments (for club managers)
     */
    public function getEventEnrollments($eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = Auth::user();

        // Check if user is a club manager of this event's club
        if (!$event->club->managers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Only club managers can view enrollments'], 403);
        }

        $enrollments = $event->enrollments()
                            ->with('user')
                            ->latest('created_at')
                            ->get();

        return response()->json($enrollments);
    }
}
