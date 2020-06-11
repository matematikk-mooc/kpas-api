<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFylkesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fylkes', function (Blueprint $table) {
            $table->string('Fylkesnr');
            $table->string('Navn');
            $table->string('OrgNr');
            $table->string('OrgNrFylkesmann');
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
        Schema::dropIfExists('fylkes');
    }
}
