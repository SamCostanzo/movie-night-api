<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watched extends Model
{
    protected $table = 'watched';

    protected $fillable = [
        'user_id',
        'tmdb_id',
        'title',
        'poster_path',
        'watched_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}