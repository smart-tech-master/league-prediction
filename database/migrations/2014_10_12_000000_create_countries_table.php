<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 5);
            $table->string('code', 2);
            $table->string('name', 80);
            $table->string('symbol', 10);
            $table->string('capital', 80);
            $table->string('currency', 3);
            $table->string('continent', 30);
            $table->string('continent_code', 2);
            $table->char('alpha_3', 3);
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
        Schema::dropIfExists('country');
    }
}
