<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKompetansepakkesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kompetansepakkes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->text('beskrivelse')->nullable();;
            $table->string('gruppering')->nullable();;
            $table->boolean('spraakstoette')->default(false);
            $table->boolean('rollestoette')->default(false);
            $table->boolean('aapen_lisens')->default(true);
            $table->text('diplom_beskrivelse')->nullable();;
            $table->string('utviklet_av')->nullable();;
            $table->date('sist_vedlikeholdt')->nullable();;

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
        Schema::dropIfExists('kompetansepakkes');
    }
}
