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
            $table->text('description')->nullable();
            $table->decimal('longitude', 11, 8);
            $table->decimal('latitude', 11, 8);
            $table->string('owner_name');
            $table->string('tel_number', 50);
            $table->string('owner_mobile_number', 50);
            $table->string('owner_email')->unique();
            $table->string('email')->unique();
            $table->string('time_zone');
            $table->time('open_time');
            $table->time('close_time');
            $table->enum('open_day_from', [0, 1, 2, 3, 4, 5, 6])->comment('0: Monday, 1: Tuesday, 2: Wednesday, 3: Thursday, 4: Friday, 5: Saturday, 6: Sunday');
            $table->enum('open_day_to', [0, 1, 2, 3, 4, 5, 6])->comment('0: Monday, 1: Tuesday, 2: Wednesday, 3: Thursday, 4: Friday, 5: Saturday, 6: Sunday');
            $table->string('user_name');
            $table->string('password')->nullable();
            $table->bigInteger('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->bigInteger('currency_id')->unsigned();
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
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
