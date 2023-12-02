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
        Schema::connection('custom-football')->create('rounds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
//            $table->foreignId('league_id')->constrained('api-football.leagues');
//            $table->foreignId('season_id')->constrained('api-football.seasons');
//            $table->foreignId('from')->constrained('api-football.rounds');
//            $table->foreignId('to')->constrained('api-football.rounds');
//            $table->foreignId('competition_id')->constrained('custom-football.competitions');
            //$table->unique(['league_id', 'season_id', 'from', 'to', 'competition_id']);
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('season_id');
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to');
            $table->unsignedBigInteger('competition_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rounds');
    }
};
