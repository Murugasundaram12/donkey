<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastRechargeDateToSubscriberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriber', function (Blueprint $table) {
            $table->timestamp('last_recharge_date')->nullable()->after('pincode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriber', function (Blueprint $table) {
            $table->dropColumn('last_recharge_date');
        });
    }
}
