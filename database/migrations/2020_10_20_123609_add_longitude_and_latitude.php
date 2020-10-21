<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLongitudeAndLatitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skoles', function (Blueprint $table) {
            $table->string('Breddegrad');
            $table->string('Lengdegrad');
        });

        Schema::table('barnehages', function (Blueprint $table) {
            $table->string('Breddegrad');
            $table->string('Lengdegrad');
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
            $table->dropColumn('Breddegrad');
            $table->dropColumn('Lengdegrad');
        });
        Schema::table('barnehages', function (Blueprint $table) {
            $table->dropColumn('Breddegrad');
            $table->dropColumn('Lengdegrad');
        });
    }
}
