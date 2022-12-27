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
        Schema::create('join_canvas_group_users', function (Blueprint $table) {
            $table->integer('canvas_user_id')->unsigned();
            $table->integer('canvas_group_id')->unsigned();
            $table->primary(['canvas_user_id', 'canvas_group_id']);
            $table->foreign('canvas_group_id')->references('canvas_id')->on('groups');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('join_canvas_group_users');
    }
};
