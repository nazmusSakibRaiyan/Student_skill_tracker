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
}
