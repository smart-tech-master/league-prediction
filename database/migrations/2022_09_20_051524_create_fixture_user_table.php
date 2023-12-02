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
        Schema::create('fixture_user', function (Blueprint $table) {
//            $table->foreignId('user_id')->constrained('users');
//            $table->foreignId('fixture_id')->constrained('custom-football.fixtures');
//            $table->unique(['user_id', 'fixture_id'], 'constrained_index_unique');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('fixture_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixture_user');
    }
};
