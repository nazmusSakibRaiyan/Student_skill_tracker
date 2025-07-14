<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'name',
        'description',
        'logo',
        'start_date',
        'end_date',
        'event_type',
        'event_type_description',
        'venue_link',
        'max_participants',
        'qr_code',
        'attendance_enabled',
        'attendance_deadline',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'attendance_deadline' => 'datetime',
        'attendance_enabled' => 'boolean',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function enrollments()
    {
        return $this->hasMany(EventEnrollment::class);
    }

    public function enrolledStudents()
    {
        return $this->belongsToMany(User::class, 'event_enrollments')
                    ->withPivot('status', 'enrolled_at', 'completed_at')
                    ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    // Check if event allows enrollment (only seminars, workshops, contests)
    public function allowsEnrollment()
    {
        return in_array($this->event_type, ['seminars', 'workshops', 'contests']);
    }

    // Check if user is enrolled
    public function isUserEnrolled($userId)
    {
        return $this->enrollments()->where('user_id', $userId)->exists();
    }

    // Get enrollment count
    public function getEnrollmentCount()
    {
        return $this->enrollments()->where('status', 'enrolled')->count();
    }

    // Check if event has available slots
    public function hasAvailableSlots()
    {
        if (!$this->max_participants) {
            return true; // No limit set
        }
        
        return $this->getEnrollmentCount() < $this->max_participants;
    }

    // Get available slots count
    public function getAvailableSlots()
    {
        if (!$this->max_participants) {
            return null; // No limit set
        }
        
        return max(0, $this->max_participants - $this->getEnrollmentCount());
    }

    // Check if enrollment is full
    public function isFull()
    {
        return $this->max_participants && $this->getEnrollmentCount() >= $this->max_participants;
    }

    // Generate QR code for attendance
    public function generateQRCode()
    {
        if (!$this->qr_code) {
            $this->qr_code = 'event_' . $this->id . '_' . Str::random(20);
            $this->save();
        }
        return $this->qr_code;
    }

    // Check if attendance is enabled for this event
    public function isAttendanceEnabled()
    {
        return $this->attendance_enabled;
    }

    // Check if attendance deadline has passed
    public function isAttendanceDeadlinePassed()
    {
        return $this->attendance_deadline && now()->gt($this->attendance_deadline);
    }

    // Get attendance statistics
    public function getAttendanceStats()
    {
        $totalEnrolled = $this->getEnrollmentCount();
        $totalMarked = $this->attendances()->count();
        $presentCount = $this->attendances()->present()->count();
        $lateCount = $this->attendances()->late()->count();
        $absentCount = $this->attendances()->absent()->count();
        $notMarkedCount = $totalEnrolled - $totalMarked;

        return [
            'total_enrolled' => $totalEnrolled,
            'total_marked' => $totalMarked,
            'present' => $presentCount,
            'late' => $lateCount,
            'absent' => $absentCount,
            'not_marked' => $notMarkedCount,
            'attendance_rate' => $totalEnrolled > 0 ? round(($presentCount + $lateCount) / $totalEnrolled * 100, 2) : 0,
        ];
    }

    // Check if user has marked attendance
    public function hasUserMarkedAttendance($userId)
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }

    // Get user's attendance record
    public function getUserAttendance($userId)
    {
        return $this->attendances()->where('user_id', $userId)->first();
    }
}
