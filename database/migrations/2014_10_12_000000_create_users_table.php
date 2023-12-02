<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->date('dob')->nullable();
            $table->string('profile_picture')->nullable();
            $table->longText('bio')->nullable();
            $table->string('device_token')->nullable();
            $table->enum('device_platform', ['android', 'ios'])->nullable();
            $table->enum('provider', ['google', 'apple'])->nullable();
            $table->string('provider_id')->nullable();
            $table->foreignId('locale_id')->nullable()->constrained('locales');
            $table->boolean('receive_notifications')->default(1);
            $table->enum('role', ['super-admin', 'public-user'])->default('public-user');
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
        Schema::dropIfExists('users');
    }
}
