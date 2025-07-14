<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EventAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'marked_by',
        'status',
        'check_in_method',
        'checked_in_at',
        'notes',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // Check if student was present
    public function isPresent()
    {
        return $this->status === 'present';
    }

    // Check if student was late
    public function isLate()
    {
        return $this->status === 'late';
    }

    // Check if student was absent
    public function isAbsent()
    {
        return $this->status === 'absent';
    }

    // Get attendance status badge class for UI
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'present' => 'bg-green-100 text-green-800',
            'late' => 'bg-yellow-100 text-yellow-800',
            'absent' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Get check-in method badge class
    public function getMethodBadgeClass()
    {
        return match($this->check_in_method) {
            'qr_code' => 'bg-blue-100 text-blue-800',
            'manual' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Scope for filtering by status
    public function scopePresent($query)
    {
        return $query->where('status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late');
    }

    public function scopeAbsent($query)
    {
        return $query->where('status', 'absent');
    }

    // Scope for filtering by check-in method
    public function scopeByQRCode($query)
    {
        return $query->where('check_in_method', 'qr_code');
    }

    public function scopeManual($query)
    {
        return $query->where('check_in_method', 'manual');
    }
}
