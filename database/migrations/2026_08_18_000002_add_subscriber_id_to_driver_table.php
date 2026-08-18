<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('driver') && !Schema::hasColumn('driver', 'subscriberId')) {
            Schema::table('driver', function (Blueprint $table) {
                $table->string('subscriberId', 121)->nullable()->after('id');
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
        if (Schema::hasTable('driver') && Schema::hasColumn('driver', 'subscriberId')) {
            Schema::table('driver', function (Blueprint $table) {
                $table->dropColumn('subscriberId');
            });
        }
    }
};
