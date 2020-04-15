<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 11, 8);
            $table->integer('city');
            $table->integer('country');
            $table->string('owner_name');
            $table->string('tel_number', 50);
            $table->string('owner_mobile_number');
            $table->string('owner_email')->unique();
            $table->string('email')->unique();
            $table->tinyInteger('currency_id');
            $table->string('time_zone');
            $table->string('user_name');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('shops');
    }
}
