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
        Schema::create('competition_user', function (Blueprint $table) {
//            $table->foreignId('user_id')->constrained('users');
//            $table->foreignId('competition_id')->constrained('custom-football.competitions');
//            $table->timestamps();
//            $table->unique(['user_id', 'competition_id'], 'constrained_index_unique');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('competition_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('competition_user');
    }
};
