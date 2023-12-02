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
        Schema::connection('api-football')->create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->string('timezone');
            $table->dateTime('timestamp');
            $table->string('long_status');
            $table->string('short_status');
            $table->timestamp('finished_at')->nullable();
            $table->string('league_round');
            $table->foreignId('league_id')->constrained('leagues');
            $table->foreignId('season_id')->constrained('seasons');
            $table->foreignId('round_id')->constrained('rounds');
            $table->index(['league_id', 'season_id', 'round_id'], 'constrained_index_unique');
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
