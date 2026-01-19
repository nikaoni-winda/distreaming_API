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
        Schema::table('movies', function (Blueprint $table) {
            $table->string('movie_poster', 500)->after('production_year');
            $table->text('movie_description_en')->after('movie_poster');
            $table->text('movie_description_id')->after('movie_description_en');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->dropColumn(['movie_poster', 'movie_description_en', 'movie_description_id']);
        });
    }
};
