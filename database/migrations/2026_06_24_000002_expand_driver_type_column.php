<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ExpandDriverTypeColumn extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('driver', 'type')) {
            DB::statement("ALTER TABLE `driver` MODIFY `type` VARCHAR(20) NULL");
            return;
        }

        Schema::table('driver', function (Blueprint $table) {
            $table->string('type', 20)->nullable()->after('subscriberId');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('driver', 'type')) {
            DB::statement("ALTER TABLE `driver` MODIFY `type` VARCHAR(1) NULL");
        }
    }
}
