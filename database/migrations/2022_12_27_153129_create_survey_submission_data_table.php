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
        Schema::create('survey_submission_data', function (Blueprint $table) {
            $table->integer('question_id')->unsigned();
            $table->integer('submission_id')->unsigned();
            $table->string('value');

            $table->primary(['question_id', 'submission_id']);
            $table->foreign('question_id')->references('id')->on('survey_questions'); 
            $table->foreign('submission_id')->references('id')->on('survey_submissions'); 


        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_submission_data');
    }
};
