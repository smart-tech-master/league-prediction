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
        Schema::connection('custom-football')->create('chats', function (Blueprint $table) {

            $db = \Illuminate\Support\Facades\DB::connection('mysql')->getDatabaseName();

            $table->id();
            $table->longText('comment');
            $table->foreignId('user_id')->constrained($db . '.users');
            $table->foreignId('competition_id')->constrained('competitions');
            $table->foreignId('parent_id')->nullable()->constrained('chats');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
};
