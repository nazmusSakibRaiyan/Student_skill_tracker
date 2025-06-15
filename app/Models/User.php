<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that belongs to the user
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->name, $roles);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->role && $this->role->hasPermission($permissionName);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->role) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($this->role->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user is a master admin
     */
    public function isMasterAdmin(): bool
    {
        return $this->hasRole('master_admin');
    }

    /**
     * Check if user is a club manager
     */
    public function isClubManager(): bool
    {
        return $this->hasRole('club_manager');
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Get all permissions for this user through their role
     */
    public function getPermissions()
    {
        return $this->role ? $this->role->permissions : collect();
    }

    /**
     * Send the email verification notification using custom template.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomEmailVerification);
    }

    /**
     * Get the clubs that are managed by the user.
     */
    public function managedClubs(): BelongsToMany
    {
        return $this->belongsToMany(Club::class, 'club_manager', 'user_id', 'club_id');
    }

    /**
     * Get the clubs that the user is a member of.
     */
    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_student', 'user_id', 'club_id')->withPivot('status')->withTimestamps();
    }
}
