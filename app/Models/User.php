<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;           // ← нужно для сброса пароля
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name', 'email', 'password', 'birthdate', 'age', 'gender', 'city', 'avatar', 'is_blocked',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthdate'         => 'date',
        'is_blocked'        => 'boolean',
    ];

    // ─── Связи ────────────────────────────────────────────────────────────────

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    // Мероприятия, на которые записался (как участник)
    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class)->withTimestamps();
    }

    // Мероприятия, созданные этим пользователем (как организатор)
    public function createdEvents()
    {
        return $this->hasMany(Event::class, 'user_id');
    }

    // ─── Проверка ролей ───────────────────────────────────────────────────────

    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }

    public function isOrganizer(): bool
    {
        return $this->hasRole('organizer');
    }

    public function isUser(): bool
    {
        return $this->hasRole('user');
    }

    public function isBlocked(): bool
    {
        return (bool) $this->is_blocked;
    }

    public function canManageEvent(Event $event): bool
    {
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }
        if ($this->isOrganizer() && $event->user_id === $this->id) {
            return true;
        }
        return false;
    }
}
