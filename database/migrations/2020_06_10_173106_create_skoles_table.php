<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skoles', function (Blueprint $table) {
            $table->string('Kommunenr');
            $table->string('Navn');
            $table->string('OrgNr');
            $table->string('NSRId');
            $table->boolean('ErSkole');
            $table->boolean('ErSkoleEier');
            $table->boolean('ErGrunnSkole');
            $table->boolean('ErPrivatSkole');
            $table->boolean('ErOffentligSkole');
            $table->boolean('ErVideregaaendeSkole');
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
        Schema::dropIfExists('skoles');
    }
}
