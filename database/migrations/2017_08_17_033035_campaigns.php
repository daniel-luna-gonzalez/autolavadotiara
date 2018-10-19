<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Campaigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('campaign'))
            Schema::create('campaign', function (Blueprint $table) {
                $table->charset = 'utf8';
                $table->collation = 'utf8_unicode_ci';
                $table->increments('id');
                $table->string("name");
                $table->string("description");
                $table->dateTime("start_date");
                $table->dateTime("end_date");
                $table->double("expected_amount");
                $table->double("current_amount")->default(0);
                $table->timestamps();
            });

        if (!Schema::hasTable('campaign_donor'))
                Schema::create('campaign_donor', function (Blueprint $table) {
                    $table->charset = 'utf8';
                    $table->collation = 'utf8_unicode_ci';
                    $table->increments('id');
                    $table->string("idConekta")->nullable(true);
                    $table->string("name");
                    $table->string("last_name")->nullable(true);
                    $table->string("mother_last_name")->nullable(true);
                    $table->string("email")->nullable(true);
                    $table->string("birthday")->nullable(true);
                    $table->timestamps();

                });

        if (!Schema::hasTable('campaing_orders'))
            Schema::create('campaing_orders', function (Blueprint $table) {
                $table->increments('id');
                $table->string("idConekta")->nullable(true);
                $table->integer('idDonor')->unsigned();
                $table->float("amount")->default(0);
                $table->integer("idCampaign")->unsigned();
                $table->string("cardToken")->nullable(true);
                $table->timestamps();

                $table->foreign('idCampaign')->references('id')->on('campaign');

            });

        if (!Schema::hasTable('campaign_fiscalEntity'))
            Schema::create('campaign_fiscalEntity', function (Blueprint $table) {
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
                    ->on('campaign_donor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('campaign_fiscalEntity');
        Schema::dropIfExists('campaing_orders');
        Schema::dropIfExists('campaign_donor');
        Schema::dropIfExists('campaign');
    }
}
