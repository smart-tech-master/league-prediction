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
        Schema::connection('api-football')->create('rounds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->string('slug');
            $table->json('keywords')->nullable();
            $table->integer('sl');
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('season_id')->constrained('seasons');
            $table->index(['name', 'sl', 'league_id', 'season_id'], 'constrained_index_unique');
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
