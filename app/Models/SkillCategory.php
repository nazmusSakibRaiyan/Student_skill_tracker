<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillCategory extends Model
{
    protected $fillable = [
        'club_id',
        'name',
        'description',
        'color',
        'icon',
        'max_points',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'max_points' => 'integer',
    ];

    /**
     * Get the club that owns this skill category
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get all student skills for this category
     */
    public function studentSkills(): HasMany
    {
        return $this->hasMany(StudentSkill::class);
    }

    /**
     * Calculate points needed for a specific level
     */
    public static function pointsForLevel(int $level): int
    {
        // Progressive point system: Level 1 = 0-19, Level 2 = 20-49, Level 3 = 50-99, etc.
        return ($level - 1) * 20;
    }

    /**
     * Calculate level from total points
     */
    public static function levelFromPoints(int $points): int
    {
        return max(1, intval($points / 20) + 1);
    }

    /**
     * Calculate progress percentage within current level
     */
    public static function progressInLevel(int $points): float
    {
        $level = self::levelFromPoints($points);
        $pointsInCurrentLevel = $points - self::pointsForLevel($level);
        $pointsNeededForNextLevel = 20; // Always 20 points per level
        
        return min(100, ($pointsInCurrentLevel / $pointsNeededForNextLevel) * 100);
    }
}
