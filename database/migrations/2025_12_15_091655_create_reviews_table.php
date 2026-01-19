<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id'); // Primary Key: INT AUTO_INCREMENT
            $table->unsignedBigInteger('user_id'); // INT NOT NULL
            $table->unsignedBigInteger('movie_id'); // INT NOT NULL
            $table->tinyInteger('rating'); // TINYINT NOT NULL
            $table->date('review_date'); // DATE NOT NULL

            // Foreign Keys
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('movie_id')->references('movie_id')->on('movies')->onDelete('cascade');

            // Composite Unique (1 user can only review 1 movie once)
            $table->unique(['user_id', 'movie_id']);
        });

        // Note: CHECK constraint for rating (1-10) is handled in the Review model validation
        // SQLite doesn't support ALTER TABLE ADD CONSTRAINT
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
