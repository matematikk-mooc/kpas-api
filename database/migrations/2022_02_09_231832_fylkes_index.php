<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FylkesIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fylkes', function (Blueprint $table) {
            $table->string('Fylkesnr',10)->change();
            $table->unique('Fylkesnr');
        });
     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fylkes', function (Blueprint $table) {
            $table->string('Fylkesnr')->change();
            $table->dropUnique('Fylkesnr');
        });
    }
}
