<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'enrolled_at',
        'completed_at',
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mark enrollment as completed
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);
    }

    // Check if enrollment is completed
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    // Check if enrollment is active
    public function isActive()
    {
        return $this->status === 'enrolled';
    }

    // Auto-complete enrollment if event has ended
    public function checkAutoComplete()
    {
        if ($this->status === 'enrolled' && $this->event->end_date <= Carbon::now()) {
            $this->markAsCompleted();
            return true;
        }
        return false;
    }

    // Scope for auto-completable enrollments
    public function scopeAutoCompletable($query)
    {
        return $query->where('status', 'enrolled')
                    ->whereHas('event', function($q) {
                        $q->where('end_date', '<=', Carbon::now());
                    });
    }
}
