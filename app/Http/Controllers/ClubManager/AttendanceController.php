<?php

namespace App\Http\Controllers\ClubManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\EventEnrollment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $clubId = $request->get('club_id');
        
        // Get events for the club manager's clubs
        $query = Event::with(['club', 'attendances'])
            ->whereHas('club.managers', function($q) use ($user) {
                $q->where('user_id', $user->id)->where('banned', false);
            });

        if ($clubId) {
            $query->where('club_id', $clubId);
        }

        $events = $query->where('attendance_enabled', true)
                       ->orderBy('start_date', 'desc')
                       ->paginate(10);

        // Get clubs for filter
        $clubs = $user->managedClubs()->where('banned', false)->get();

        return view('club-manager.attendance.index', compact('events', 'clubs', 'clubId'));
    }

    public function show(Event $event)
    {
        $user = Auth::user();
        
        // Check if user can manage this event
        if (!$event->club->managers()->where('user_id', $user->id)->where('banned', false)->exists()) {
            abort(403, 'You are not authorized to manage attendance for this event.');
        }

        // Get enrolled students with their attendance status
        $enrolledStudents = $event->enrollments()
            ->with(['user', 'user.role'])
            ->where('status', 'enrolled')
            ->get()
            ->map(function($enrollment) use ($event) {
                $attendance = $event->attendances()->where('user_id', $enrollment->user_id)->first();
                $enrollment->attendance = $attendance;
                return $enrollment;
            });

        $attendanceStats = $event->getAttendanceStats();

        return view('club-manager.attendance.show', compact('event', 'enrolledStudents', 'attendanceStats'));
    }

    public function markAttendance(Request $request, Event $event)
    {
        $user = Auth::user();
        
        // Check if user can manage this event
        if (!$event->club->managers()->where('user_id', $user->id)->where('banned', false)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:present,absent,late',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if user is enrolled in the event
        if (!$event->isUserEnrolled($request->user_id)) {
            return response()->json(['error' => 'User is not enrolled in this event'], 400);
        }

        try {
            DB::beginTransaction();

            // Create or update attendance record
            $attendance = EventAttendance::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'user_id' => $request->user_id,
                ],
                [
                    'marked_by' => $user->id,
                    'status' => $request->status,
                    'check_in_method' => 'manual',
                    'checked_in_at' => now(),
                    'notes' => $request->notes,
                ]
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'attendance' => $attendance->load(['user', 'markedBy'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to mark attendance: ' . $e->getMessage()], 500);
        }
    }

    public function bulkMarkAttendance(Request $request, Event $event)
    {
        $user = Auth::user();
        
        // Check if user can manage this event
        if (!$event->club->managers()->where('user_id', $user->id)->where('banned', false)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:present,absent,late',
            'attendances.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $results = [];
            foreach ($request->attendances as $attendanceData) {
                // Check if user is enrolled
                if (!$event->isUserEnrolled($attendanceData['user_id'])) {
                    continue;
                }

                $attendance = EventAttendance::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'user_id' => $attendanceData['user_id'],
                    ],
                    [
                        'marked_by' => $user->id,
                        'status' => $attendanceData['status'],
                        'check_in_method' => 'manual',
                        'checked_in_at' => now(),
                        'notes' => $attendanceData['notes'] ?? null,
                    ]
                );

                $results[] = $attendance;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bulk attendance marked successfully',
                'count' => count($results)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to mark bulk attendance: ' . $e->getMessage()], 500);
        }
    }

    public function generateQRCode(Event $event)
    {
        $user = Auth::user();
        
        // Check if user can manage this event
        if (!$event->club->managers()->where('user_id', $user->id)->where('banned', false)->exists()) {
            abort(403, 'You are not authorized to generate QR code for this event.');
        }

        $qrCode = $event->generateQRCode();
        
        // Generate QR code URL for attendance check-in
        $checkInUrl = route('attendance.qr-checkin', ['qrCode' => $qrCode]);

        return view('club-manager.attendance.qr-code', compact('event', 'qrCode', 'checkInUrl'));
    }

    public function qrCheckIn(Request $request, $qrCode)
    {
        // Find event by QR code
        $event = Event::where('qr_code', $qrCode)->first();
        
        if (!$event) {
            return view('attendance.qr-error', ['message' => 'Invalid QR code']);
        }

        if (!$event->isAttendanceEnabled()) {
            return view('attendance.qr-error', ['message' => 'Attendance is not enabled for this event']);
        }

        if ($event->isAttendanceDeadlinePassed()) {
            return view('attendance.qr-error', ['message' => 'Attendance deadline has passed']);
        }

        return view('attendance.qr-checkin', compact('event'));
    }

    public function processQRCheckIn(Request $request, $qrCode)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Find event by QR code
        $event = Event::where('qr_code', $qrCode)->first();
        
        if (!$event || !$event->isAttendanceEnabled() || $event->isAttendanceDeadlinePassed()) {
            return response()->json(['error' => 'Invalid request'], 400);
        }

        // Find user by email
        $user = User::where('email', $request->email)->first();
        
        // Check if user is enrolled in the event
        if (!$event->isUserEnrolled($user->id)) {
            return response()->json(['error' => 'You are not enrolled in this event'], 400);
        }

        // Check if already marked attendance
        if ($event->hasUserMarkedAttendance($user->id)) {
            return response()->json(['error' => 'You have already marked attendance for this event'], 400);
        }

        try {
            DB::beginTransaction();

            // Mark attendance as present via QR code
            $attendance = EventAttendance::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'marked_by' => null, // Self check-in
                'status' => 'present',
                'check_in_method' => 'qr_code',
                'checked_in_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully!',
                'user' => $user->name,
                'event' => $event->name,
                'checked_in_at' => $attendance->checked_in_at->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to mark attendance: ' . $e->getMessage()], 500);
        }
    }

    public function exportAttendance(Event $event)
    {
        $user = Auth::user();
        
        // Check if user can manage this event
        if (!$event->club->managers()->where('user_id', $user->id)->where('banned', false)->exists()) {
            abort(403);
        }

        $attendances = $event->attendances()
            ->with(['user', 'markedBy'])
            ->orderBy('checked_in_at')
            ->get();

        $filename = 'attendance_' . $event->name . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendances) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Student Name',
                'Email',
                'Status',
                'Check-in Method',
                'Checked In At',
                'Marked By',
                'Notes'
            ]);

            // CSV data
            foreach ($attendances as $attendance) {
                fputcsv($file, [
                    $attendance->user->name,
                    $attendance->user->email,
                    ucfirst($attendance->status),
                    ucfirst(str_replace('_', ' ', $attendance->check_in_method)),
                    $attendance->checked_in_at->format('Y-m-d H:i:s'),
                    $attendance->markedBy ? $attendance->markedBy->name : 'Self (QR)',
                    $attendance->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
