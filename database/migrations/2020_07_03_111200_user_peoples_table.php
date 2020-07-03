<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserPeoplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_peoples', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
			$table->enum('gender', ['m', 'f'])->comment('m: Male, f: Female');
            $table->string('photo')->nullable();
            $table->bigInteger('user_id')->unsigned();
			$table->enum('is_removed', ['0', '1'])->default('0')->comment('0: Nope, 1: Yes');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('user_peoples');
    }
}
