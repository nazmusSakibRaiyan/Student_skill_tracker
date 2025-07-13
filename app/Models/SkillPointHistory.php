<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillPointHistory extends Model
{
    protected $fillable = [
        'student_skill_id',
        'assigned_by',
        'points_awarded',
        'reason',
        'notes',
        'action_type',
        'awarded_at',
    ];

    protected $casts = [
        'points_awarded' => 'integer',
        'awarded_at' => 'datetime',
    ];

    /**
     * Get the student skill that owns this history
     */
    public function studentSkill(): BelongsTo
    {
        return $this->belongsTo(StudentSkill::class);
    }

    /**
     * Get the user who assigned the points
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
