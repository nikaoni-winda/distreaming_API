<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WatchHistory extends Model
{
    // Table's name
    protected $table = 'watch_history';

    // Primary key custom
    protected $primaryKey = 'watch_history_id';

    // Disable timestamps
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'user_id',
        'movie_id',
        'watch_date'
    ];

    // Cast watch_date as date
    protected $casts = [
        'watch_date' => 'date'
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
