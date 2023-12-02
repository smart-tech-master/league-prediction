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
        Schema::create('fixture_prediction', function (Blueprint $table) {
//            $table->foreignId('fixture_id')->constrained('custom-football.fixtures');
//            $table->foreignId('prediction_id')->constrained('predictions');
//            $table->unique(['fixture_id', 'prediction_id'], 'constrained_index_unique');
            $table->unsignedBigInteger('fixture_id');
            $table->unsignedBigInteger('prediction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture_prediction');
    }
};
