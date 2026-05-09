<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'is_banned',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_banned' => 'boolean',
        'password' => 'hashed',
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class, 'publisher_id');
    }

    public function accessTokens(): HasMany
    {
        return $this->hasMany(AccessToken::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
