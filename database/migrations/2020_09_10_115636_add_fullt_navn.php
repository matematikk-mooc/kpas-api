<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltNavn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skoles', function (Blueprint $table) {
            $table->string('FulltNavn');
        });

        Schema::table('barnehages', function (Blueprint $table) {
            $table->string('FulltNavn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('skoles', function (Blueprint $table) {
            $table->dropColumn('FulltNavn');
        });
        Schema::table('barnehages', function (Blueprint $table) {
            $table->dropColumn('FulltNavn');
        });
    }
}
