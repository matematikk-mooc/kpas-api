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
        Schema::create('user_deletion_tokens', function (Blueprint $table) {
            $table->id();
            $table->integer('canvas_user_id');
            $table->string('token', 100);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();

            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_deletion_tokens');
    }
};
