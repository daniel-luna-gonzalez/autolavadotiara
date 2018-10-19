<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Donors extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('donors', function (Blueprint $table) {
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
            $table->rememberToken();
            $table->integer("created_by")->default(1);
            $table->date("created_at");
            $table->date("updated_at");
            $table->boolean("status")->default(1);

            
        });

        Schema::create('fiscalEntity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idDonor')->unsigned();
            $table->string("name")->default("");
            $table->string("tax_id")->default("");
            $table->string("street1")->default("");
            $table->string("street2")->default("");
            $table->string("street3")->default("");
            $table->string("state")->default("");
            $table->string("zip")->default("");
            $table->string("city")->default("");
            $table->timestamps();
            
            $table->foreign('idDonor')->references('id')
                    ->on('donors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('fiscalEntity');
        Schema::dropIfExists('donors');
    }

}
