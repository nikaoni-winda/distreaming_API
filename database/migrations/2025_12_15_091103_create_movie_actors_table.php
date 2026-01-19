<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movie_actors', function (Blueprint $table) {
            $table->unsignedBigInteger('movie_id'); // INT NOT NULL
            $table->unsignedBigInteger('actor_id'); // INT NOT NULL

            // Composite Primary Key
            $table->primary(['movie_id', 'actor_id']);

            // Foreign Keys
            $table->foreign('movie_id')->references('movie_id')->on('movies')->onDelete('cascade');
            $table->foreign('actor_id')->references('actor_id')->on('actors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_actors');
    }
};
