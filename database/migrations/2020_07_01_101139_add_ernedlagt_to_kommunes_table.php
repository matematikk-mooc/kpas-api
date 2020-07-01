<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddErnedlagtToKommunesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kommunes', function (Blueprint $table) {
            $table->boolean('ErNedlagt');
            //
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
            $table->dropColumn('ErNedlagt');
            //
        });
    }
}
