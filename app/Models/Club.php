<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;

class Club extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'description',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    
    public function managers()
    {
        return $this->belongsToMany(User::class, 'club_manager', 'club_id', 'user_id');
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'club_student', 'club_id', 'user_id')->withPivot('status')->withTimestamps();
    }

    /**
     * Get all skill categories for this club
     */
    public function skillCategories()
    {
        return $this->hasMany(SkillCategory::class);
    }

    /**
     * Get active skill categories for this club
     */
    public function activeSkillCategories()
    {
        return $this->hasMany(SkillCategory::class)->where('active', true);
    }

    /**
     * Get all student skills for this club
     */
    public function studentSkills()
    {
        return $this->hasMany(StudentSkill::class);
    }

    /**
     * Get total points awarded in this club
     */
    public function getTotalPointsAwarded(): int
    {
        return $this->studentSkills()->sum('total_points');
    }

    /**
     * Get average skill level for this club
     */
    public function getAverageSkillLevel(): float
    {
        return $this->studentSkills()->avg('level') ?? 0;
    }
}
