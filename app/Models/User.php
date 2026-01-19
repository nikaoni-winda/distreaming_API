<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    // Table's name
    protected $table = 'users';

    // Primary key custom
    protected $primaryKey = 'user_id';

    // Disable timestamps
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'user_nickname',
        'user_email',
        'password',
        'role',
        'plan'
    ];

    // Hidden attributes
    protected $hidden = [
        'password'
    ];

    // Relationships
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id', 'user_id');
    }

    public function watchHistory()
    {
        return $this->hasMany(WatchHistory::class, 'user_id', 'user_id');
    }

    // Role helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
}
