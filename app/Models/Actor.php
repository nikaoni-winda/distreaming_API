<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    // Table's name
    protected $table = 'actors';

    // Primary key custom
    protected $primaryKey = 'actor_id';

    // Disable timestamps
    public $timestamps = false;

    // Fillable
    protected $fillable = [
        'actor_name'
    ];

    // Relationships
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_actors', 'actor_id', 'movie_id');
    }
}
