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
        Schema::table('ads', function (Blueprint $table) {
            DB::statement("ALTER TABLE ads MODIFY COLUMN type ENUM('launch-screen', 'banner', 'tutorial-screen', 'logo') DEFAULT 'banner'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
