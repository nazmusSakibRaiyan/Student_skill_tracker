<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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
}
