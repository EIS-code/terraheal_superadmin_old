<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FreelancerTherapistDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('freelancer_therapist_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('path');
            $table->bigInteger('freelancer_therapist_id')->unsigned();
            $table->foreign('freelancer_therapist_id')->references('id')->on('freelancer_therapists')->onDelete('cascade');
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
        Schema::dropIfExists('freelancer_therapist_documents');
    }
}
