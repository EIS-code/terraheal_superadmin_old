<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersTable extends Migration
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
            $table->string('name');
            $table->string('dob')->nullable();
            $table->string('email')->unique();
            $table->string('tel_number', 50)->nullable();
            $table->string('nif')->nullable();
            $table->string('address')->nullable();
            // $table->string('photo');
            $table->string('avatar')->nullable();
            $table->string('avatar_original')->nullable();
            $table->string('oauth_uid')->nullable();
            $table->tinyInteger('oauth_provider')->nullable()->comment('1: google, 2: facebook, 3: twitter, 4: linkedin');
            $table->string('password')->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->foreign('country_id')->references('id')->on('country')->onDelete('cascade');
            $table->bigInteger('shop_id')->unsigned();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
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
        Schema::dropIfExists('users');
    }
}
