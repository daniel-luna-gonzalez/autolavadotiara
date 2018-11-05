<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Subscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->increments('id');

            $table->integer('customer_id');

            $table->string('idPlan')->nullable();           //conekta
            $table->string('idSubscription')->nullable();   //conekta
            $table->string('token_card')->nullable();       //conekta
            $table->string('idCustomer')->nullable();       //conekta

            $table->timestamp('suscription_created_at')->nullable();  //conekta
            $table->timestamp('canceled_at')->nullable();             //conekta
            $table->timestamp('paused_at')->nullable();               //conekta
            $table->timestamp('billing_cycle_start')->nullable();     //conekta
            $table->timestamp('billing_cycle_end')->nullable();       //conekta

            $table->double('amount')->default(0)->nullable(false);

            $table->boolean("subscribed")->default(0);

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
        Schema::dropIfExists('subscription');
    }
}
