<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Table's name
    protected $table = 'reviews';

    // Primary key custom
    protected $primaryKey = 'review_id';

    // Disable timestamps
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'user_id',
        'movie_id',
        'rating',
        'review_date'
    ];

    // Cast rating as integer and review_date as date
    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }
}
