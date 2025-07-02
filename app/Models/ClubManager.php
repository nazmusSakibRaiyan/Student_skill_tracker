<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClubManager extends Model
{
    protected $table = 'club_manager';
    public $timestamps = true;
    protected $fillable = [
        'club_id',
        'user_id',
        'banned',
    ];

    protected $casts = [
        'banned' => 'boolean',
    ];

    /**
     * Get the user that owns the club manager.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the club that belongs to the club manager.
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
