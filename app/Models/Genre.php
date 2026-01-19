<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    // Table's name
    protected $table = 'genres';

    // Primary key custom
    protected $primaryKey = 'genre_id';

    // Disable timestamps
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'genre_name'
    ];

    // Relationships
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genres', 'genre_id', 'movie_id');
    }
}
