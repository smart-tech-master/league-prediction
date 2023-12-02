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
        Schema::connection('api-football')->create('fixture_team', function (Blueprint $table) {
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->foreignId('team_id')->constrained('teams');
            $table->enum('ground', ['home', 'away']);
            $table->integer('goals')->nullable();
            $table->unique(['fixture_id', 'team_id', 'ground'], 'constrained_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture_team');
    }
};
