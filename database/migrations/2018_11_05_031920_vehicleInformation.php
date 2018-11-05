<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VehicleInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('vehicleInformation', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->increments('id');

            $table->integer('subscription_id');

            $table->string('Nocajon')->nullable();
            $table->string('color')->nullable();
            $table->string('depto')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('nivelEstacionamiento')->nullable();
            $table->string('placas')->nullable();
            $table->string('vehicleType')->nullable();
            $table->string('washDays')->nullable();

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
        Schema::dropIfExists("vehicleInformation");
    }
}
