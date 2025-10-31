<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'profile_photo', 
    'bio',
    'contact_number',
    'google_id',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
    'email_verified_at' => 'datetime',
    'is_banned' => 'boolean',
];

    // Role constants
    const ROLE_USER = 'user';
    const ROLE_ADMIN = 'admin';

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }
}