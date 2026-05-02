<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('booking_locations', function (Blueprint $table) {
            $table->enum('type', ['from', 'to'])->default('to')->after('booking_id');
        });
    }

    public function down()
    {
        Schema::table('booking_locations', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
