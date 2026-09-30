<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->where('activo', true)
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->roles()
            ->where('roles.activo', true)
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission)
                    ->where('activo', true);
            })
            ->exists();
    }
}
