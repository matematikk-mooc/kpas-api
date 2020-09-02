<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarnehagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barnehages', function (Blueprint $table) {
            $table->string('FylkeNr');
            $table->string('KommuneNr');
            $table->string('Navn');
            $table->string('OrgNr');
            $table->string('NSRId');
            $table->boolean('ErBarnehage');
            $table->boolean('ErBarnehageEier');
            $table->boolean('ErOffentligBarnehage');
            $table->boolean('ErPrivatBarnehage');
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
        Schema::dropIfExists('barnehages');
    }
}
