<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function watchlist()
    {
        return $this->hasMany(Watchlist::class);
    }

    public function watched()
    {
        return $this->hasMany(Watched::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}