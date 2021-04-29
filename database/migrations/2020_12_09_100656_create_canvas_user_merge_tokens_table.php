<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanvasUserMergeTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canvas_user_merge_tokens', function (Blueprint $table) {
            $table->integer('canvas_user_id');
            $table->string('token', 100);
            $table->timestamps();

            $table->primary('canvas_user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canvas_user_merge_tokens');
    }
}
