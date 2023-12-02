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
        Schema::connection('custom-football')->create('fixtures', function (Blueprint $table) {
            $table->id();
//            $table->foreignId('league_id')->constrained('api-football.leagues');
//            $table->foreignId('season_id')->constrained('api-football.seasons');
//            $table->foreignId('competition_id')->constrained('custom-football.competitions');
//            $table->foreignId('round_id')->constrained('custom-football.rounds');
            //$table->index(['league_id', 'season_id', 'competition_id', 'round_id']);
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('competition_id');
            $table->unsignedBigInteger('round_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
};
