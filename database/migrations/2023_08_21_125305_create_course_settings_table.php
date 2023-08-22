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
        Schema::create('course_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id')->unsigned();
            $table->date("unmaintained_since")->nullable();
            $table->boolean('role_support')->default(false);
            $table->boolean('licence')->default(false);
            $table->enum('multilang', ['ALL', 'SE', 'NN', 'NONE']);
            $table->enum('banner_type', ['ALERT', 'NOTIFICATION', 'FEEDBACK', 'UNMAINTAINED', 'NONE']);
            $table->string('banner_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_settings');
    }
};
