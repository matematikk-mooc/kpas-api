<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollmentActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollment_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('course_id'); #course_id
            $table->string('course_name'); #course_name
            $table->integer('active_users_count');
            $table->timestamp('activity_date');
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
        Schema::dropIfExists('enrollment_activities');
    }
}
