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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('league_id')->constrained('api-football.leagues');
            $table->foreignId('season_id')->constrained('api-football.seasons');
            $table->foreignId('round_id')->constrained('api-football.rounds');
            $table->foreignId('fixture_id')->constrained('api-football.fixtures');
            $table->unsignedTinyInteger('home_team_goals');
            $table->unsignedTinyInteger('away_team_goals');
            $table->boolean('multiply_by_two')->default(false);
            $table->integer('points', )->nullable();
            $table->integer('country_position')->nullable();
            $table->integer('world_position')->nullable();
            $table->integer('continent_position')->nullable();
            $table->unique(['user_id', 'league_id', 'season_id', 'round_id', 'fixture_id'], 'constrained_index_unique');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('predictions');
    }
};
