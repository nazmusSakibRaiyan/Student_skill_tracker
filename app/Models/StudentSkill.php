<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentSkill extends Model
{
    protected $fillable = [
        'user_id',
        'club_id',
        'skill_category_id',
        'total_points',
        'level',
        'progress_percentage',
    ];

    protected $casts = [
        'total_points' => 'integer',
        'level' => 'integer',
        'progress_percentage' => 'decimal:2',
    ];

    /**
     * Get the student (user) that owns this skill
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the club this skill belongs to
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the skill category
     */
    public function skillCategory(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class);
    }

    /**
     * Get all point histories for this skill
     */
    public function pointHistories(): HasMany
    {
        return $this->hasMany(SkillPointHistory::class)->latest('awarded_at');
    }

    /**
     * Update skill level and progress based on total points
     */
    public function updateLevelAndProgress(): void
    {
        $this->level = SkillCategory::levelFromPoints($this->total_points);
        $this->progress_percentage = SkillCategory::progressInLevel($this->total_points);
        $this->save();
    }

    /**
     * Add points to this skill and update level/progress
     */
    public function addPoints(int $points): void
    {
        $this->total_points += $points;
        $this->updateLevelAndProgress();
    }
}
