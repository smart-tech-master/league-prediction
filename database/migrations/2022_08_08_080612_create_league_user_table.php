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
        Schema::create('league_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('league_id')->constrained('api-football.leagues');
            $table->foreignId('season_id')->constrained('api-football.seasons');
            $table->timestamps();
            $table->unique(['user_id', 'league_id', 'season_id'], 'constrained_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('league_user');
    }
};
