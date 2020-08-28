<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function(Blueprint $table) {
                $table->increments('id');
                $table->string("idConekta")->nullable(true);
                $table->integer('idDonor')->unsigned();
                $table->float("amount")->default(0);
                $table->string("cardToken")->nullable(true);
                $table->string("plan_id")->nullable(true);
                $table->string("subscription_id")->nullable(true);
                $table->string("subscription_status", 30)->nullable(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
