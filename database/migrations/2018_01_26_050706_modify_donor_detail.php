<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDonorDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
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
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn('suscription_created_at');
            $table->dropColumn('canceled_at');
            $table->dropColumn('paused_at');
            $table->dropColumn('billing_cycle_start');
            $table->dropColumn('billing_cycle_end');
        });
    }
}
