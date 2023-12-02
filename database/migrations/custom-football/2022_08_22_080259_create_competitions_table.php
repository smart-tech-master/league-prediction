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
        Schema::connection('custom-football')->create('competitions', function (Blueprint $table) {

            $db = \Illuminate\Support\Facades\DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->foreignId('league_id')->constrained('api-football.leagues');
            $table->foreignId('season_id')->constrained('api-football.seasons');
            $table->string('title');
            $table->string('code')->nullable()->unique();
            $table->longText('description')->nullable();
            $table->enum('joined_by', ['general', 'private'])->default('general');
            $table->enum('play_for', ['fun', 'prize']);
            $table->string('contact')->nullable();
            $table->integer('participants')->nullable();
            $table->foreignId('round_id')->nullable()->constrained('api-football.rounds');
            $table->enum('type', ['home-and-away', 'one-match'])->nullable();
            $table->foreignId('user_id')->constrained($db . '.users');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['league_id', 'season_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
};
