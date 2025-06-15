<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $fillable = [
        'name',
    ];

    public function managers()
    {
        return $this->belongsToMany(User::class, 'club_manager', 'club_id', 'user_id');
    }
}
