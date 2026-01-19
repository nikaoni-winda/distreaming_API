<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    // Table's name
    protected $table = 'movies';

    //Primary key custom
    protected $primaryKey = 'movie_id';

    //Disable timestamps
    public $timestamps = false;

    //Fillable
    protected $fillable = [
        'movie_title',
        'movie_duration',
        'production_year',
        'movie_poster',
        'movie_description_en',
        'movie_description_id',
        'trailer_url'
    ];

    protected $casts = [
        'movie_duration' => 'integer',
        'average_rating' => 'decimal:1',
        'production_year' => 'integer'
    ];

    // DISABLED: Appends cause infinite loop with eager loading
    // If you need rating_class, manually call getRatingClassAttribute()
    // protected $appends = ['rating_class'];

    // Relationships
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres', 'movie_id', 'genre_id');
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movie_actors', 'movie_id', 'actor_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'movie_id', 'movie_id');
    }

    // Accessor for rating_class
    public function getRatingClassAttribute()
    {
        if ($this->average_rating === null) {
            return 'Not Rated';
        }

        if ($this->average_rating >= 8.5) {
            return 'Top Rated';
        } elseif ($this->average_rating >= 7.0) {
            return 'Popular';
        } else {
            return 'Regular';
        }
    }

    // Method to update average rating
    public function updateAverageRating()
    {
        $averageRating = $this->reviews()->avg('rating');

        // Update average_rating, set null if no reviews
        $this->average_rating = $averageRating ? round($averageRating, 1) : null;
        $this->save();
    }
}
