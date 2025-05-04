<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Authenticatable implements AuthorizableContract, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasPermissions, Authorizable;

    protected $fillable = [
        'name',
        'email',
        'password',
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
        ];
    }

    public function scopeMasterAdmin($query)
    {
        return $query->role(RolesEnum::MASTER_ADMIN->value);
    }

    public function scopeAdmins($query)
    {
        return $query->role(RolesEnum::ADMIN->value);
    }

    public function scopeEditors($query)
    {
        return $query->role(RolesEnum::EDITOR->value);
    }
}
