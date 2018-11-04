<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Customer extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('customer', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';

            $table->increments('id');
            $table->string('idPlan')->nullable();           //conekta
            $table->string('idSubscription')->nullable();   //conekta
            $table->string('token_card')->nullable();       //conekta
            $table->string('idCustomer')->nullable();       //conekta
            $table->string('name');
            $table->string("last_name");
            $table->string("mother_last_name");
            $table->string('email')->unique();
            $table->string('birthday');
            $table->integer("created_by")->default(1);
            $table->date("created_at");
            $table->date("updated_at");
            $table->boolean("status")->default(1);

            $table->double('amount')->default(0)->nullable(false);

            $table->timestamp('suscription_created_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('billing_cycle_start')->nullable();
            $table->timestamp('billing_cycle_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('customer');
    }

}
