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

    public function club()
    {
        return $this->belongsTo(Club::class);
    }
}
