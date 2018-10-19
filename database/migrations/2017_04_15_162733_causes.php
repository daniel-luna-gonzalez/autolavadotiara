<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Causes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('causes', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->string("name");
            $table->string("description");
            $table->timestamps();
        });
        
        Schema::create('causes_donor', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
            $table->increments('id');
            $table->integer("idDonor")->unsigned();
            $table->integer("idCause")->unsigned();
            $table->timestamps();
            
            $table->foreign('idDonor')->references('id')->on('donors');
            $table->foreign('idCause')->references('id')->on('causes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('causes_donor');
        Schema::dropIfExists('causes');
    }
}
