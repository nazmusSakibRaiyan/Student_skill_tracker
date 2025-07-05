<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'message',
        'priority',
        'target_type',
        'target_filters',
        'created_by',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'target_filters' => 'array',
        'expires_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function readByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'announcement_reads')
                    ->withPivot('read_at')
                    ->withTimestamps();
    }

    public function isVisibleToUser(User $user): bool
    {
        // Check if announcement is active and not expired
        if (!$this->is_active || ($this->expires_at && $this->expires_at->isPast())) {
            return false;
        }

        switch ($this->target_type) {
            case 'all':
                return true;
                
            case 'role':
                $targetRoles = $this->target_filters['roles'] ?? [];
                return in_array($user->role->name, $targetRoles);
                
            case 'club':
                $targetClubs = $this->target_filters['clubs'] ?? [];
                
                // Get all clubs the user is associated with (as student or manager)
                $userClubIds = collect();
                
                // Add clubs where user is a student
                if ($user->clubs) {
                    $userClubIds = $userClubIds->merge($user->clubs->pluck('id'));
                }
                
                // Add clubs where user is a manager
                if ($user->managedClubs) {
                    $userClubIds = $userClubIds->merge($user->managedClubs->pluck('id'));
                }
                
                $userClubIds = $userClubIds->unique()->toArray();
                return !empty(array_intersect($targetClubs, $userClubIds));
                
            case 'individual':
                $targetUsers = $this->target_filters['users'] ?? [];
                return in_array($user->id, $targetUsers);
                
            default:
                return false;
        }
    }

    public function hasBeenReadBy(User $user): bool
    {
        return $this->readByUsers()->where('user_id', $user->id)->exists();
    }

    public function markAsReadBy(User $user): void
    {
        if (!$this->hasBeenReadBy($user)) {
            $this->readByUsers()->attach($user->id, ['read_at' => now()]);
        }
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    public function scopeForUser($query, User $user)
    {
        return $query->active()->get()->filter(function ($announcement) use ($user) {
            return $announcement->isVisibleToUser($user);
        });
    }
}
