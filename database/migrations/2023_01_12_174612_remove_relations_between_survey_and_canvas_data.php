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
        // Remove the foreign key constraints linking the survey and canvas data tables
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });

        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add the foreign key constraints linking the survey and canvas data tables
        Schema::table('surveys', function (Blueprint $table) {
            $table->foreign('course_id')->references('canvas_id')->on('canvas_courses');
        });

        Schema::table('survey_submissions', function (Blueprint $table) {
            $table->foreign('user_id')->references('canvas_user_id')->on('join_canvas_group_users');
        });
    }
};
