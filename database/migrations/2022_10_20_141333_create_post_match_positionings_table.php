<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_match_positionings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('league_id')->constrained('api-football.leagues');
            $table->foreignId('season_id')->constrained('api-football.seasons');
            $table->integer('total_points')->nullable();
            $table->integer('total_points_on_last_round')->nullable();
            $table->integer('previous_world_position')->nullable();
            $table->integer('previous_continent_position')->nullable();
            $table->integer('previous_country_position')->nullable();
            $table->integer('current_world_position')->nullable();
            $table->integer('total_world_users')->nullable();
            $table->integer('current_continent_position')->nullable();
            $table->integer('total_continent_users')->nullable();
            $table->integer('current_country_position')->nullable();
            $table->integer('total_country_users')->nullable();
            $table->unique(['user_id', 'league_id', 'season_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_match_positionings');
    }
};
