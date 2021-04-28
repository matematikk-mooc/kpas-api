<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultValuesForBarnehages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barnehages', function (Blueprint $table) {
            $table->string('Breddegrad')->default('')->change();
            $table->string('Lengdegrad')->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barnehages', function (Blueprint $table) {
            $table->string('Breddegrad')->default(null)->change();
            $table->string('Lengdegrad')->default(null)->change();
        });
    }
}
