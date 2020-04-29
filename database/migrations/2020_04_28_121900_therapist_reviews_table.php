<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TherapistReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('therapist_reviews', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('therapist_id')->unsigned();
            $table->foreign('therapist_id')->references('id')->on('therapists')->onDelete('cascade');
            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('therapist_review_questions')->onDelete('cascade');
            $table->enum('rating', [1, 2, 3, 4, 5])->comment('1: Very Bad, 2: Bad, 3: Medium, 4: Good One, 5: Too Happy');
            // $table->string('comment')->nullable();
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
        Schema::dropIfExists('therapist_reviews');
    }
}
