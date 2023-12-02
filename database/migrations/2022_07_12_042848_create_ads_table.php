<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('file');
            $table->string('link')->nullable();
            $table->enum('link_type', ['external', 'internal'])->nullable();
            $table->date('started_at')->nullable();
            $table->date('ended_at')->nullable();
            $table->integer('time_of_appearance')->nullable();
            $table->enum('type', ['launch-screen', 'banner', 'tutorial-screen'])->default('banner');
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->integer('sl')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries');
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
        Schema::dropIfExists('ads');
    }
}
