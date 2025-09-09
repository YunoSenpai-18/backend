<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ Add this

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // ✅ Add HasApiTokens here

    protected $fillable = [
        'full_name',
        'school_id',
        'email',
        'phone',
        'photo',
        'role',
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
            'role' => 'string',
        ];
    }
}
