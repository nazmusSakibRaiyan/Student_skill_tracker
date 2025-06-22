<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClubManager extends Model
{
    protected $table = 'club_manager';
    public $timestamps = true;
    protected $fillable = [
        'club_id',
        'user_id',
        'banned',
    ];
}
