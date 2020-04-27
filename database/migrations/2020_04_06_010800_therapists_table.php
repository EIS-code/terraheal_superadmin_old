<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TherapistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('dob');
            $table->enum('gender', ['m', 'f'])->comment('m: Male, f: Female');
            $table->string('email')->unique();
            $table->string('tel_number', 50);
            $table->string('hobbies');
            // $table->string('spoken_language_ids')->comment('Comma separated language ids for spoken.');
            $table->string('short_description');
            // $table->string('profile_photo');
            $table->enum('is_freelancer', [0, 1])->comment('0: Nope, 1: Yes');
            $table->enum('is_deleted', [0, 1])->comment('0: Nope, 1: Yes');
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
        Schema::dropIfExists('therapists');
    }
}
