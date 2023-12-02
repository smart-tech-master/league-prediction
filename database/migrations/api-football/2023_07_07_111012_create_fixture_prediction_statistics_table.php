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
        Schema::connection('api-football')->create('fixture_prediction_statistics', function (Blueprint $table) {
            $table->foreignId('fixture_id')->constrained('fixtures');
            $table->char('home', 5);
            $table->char('draw', 5);
            $table->char('away', 5);
            $table->string('advice')->nullable();
            $table->unique(['fixture_id'], 'constrained_index_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture_prediction_statistics');
    }
};
