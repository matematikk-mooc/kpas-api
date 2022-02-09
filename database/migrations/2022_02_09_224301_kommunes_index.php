<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KommunesIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kommunes', function (Blueprint $table) {
            $table->string('Kommunenr',10)->change();
            $table->unique('Kommunenr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kommunes', function (Blueprint $table) {
            $table->string('Kommunenr')->change();
            $table->dropUnique('Kommunenr');
        });
    }
}
