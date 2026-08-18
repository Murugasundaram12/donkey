<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            if (!Schema::hasColumn('sites', 'map')) {
                $table->text('map')->nullable()->after('address');
            }

            if (!Schema::hasColumn('sites', 'user_app')) {
                $table->string('user_app')->nullable()->after('image');
            }

            if (!Schema::hasColumn('sites', 'driver_app')) {
                $table->string('driver_app')->nullable()->after('user_app');
            }

            if (!Schema::hasColumn('sites', 'maintainance')) {
                $table->unsignedTinyInteger('maintainance')->default(0)->after('driver_app');
            }

            if (!Schema::hasColumn('sites', 'userToken')) {
                $table->text('userToken')->nullable()->after('maintainance');
            }

            if (!Schema::hasColumn('sites', 'driverToken')) {
                $table->text('driverToken')->nullable()->after('userToken');
            }

            if (!Schema::hasColumn('sites', 'main_logo')) {
                $table->string('main_logo')->nullable()->after('driverToken');
            }

            if (!Schema::hasColumn('sites', 'sidebar_logo')) {
                $table->string('sidebar_logo')->nullable()->after('main_logo');
            }

            if (!Schema::hasColumn('sites', 'sidebar_small_logo')) {
                $table->string('sidebar_small_logo')->nullable()->after('sidebar_logo');
            }

            if (!Schema::hasColumn('sites', 'favicon')) {
                $table->string('favicon')->nullable()->after('sidebar_small_logo');
            }

            if (!Schema::hasColumn('sites', 'mining_coin')) {
                $table->string('mining_coin')->nullable()->after('favicon');
            }

            if (!Schema::hasColumn('sites', 'indirect_percentage')) {
                $table->string('indirect_percentage')->nullable()->after('mining_coin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            foreach ([
                'map',
                'user_app',
                'driver_app',
                'maintainance',
                'userToken',
                'driverToken',
                'main_logo',
                'sidebar_logo',
                'sidebar_small_logo',
                'favicon',
                'mining_coin',
                'indirect_percentage',
            ] as $column) {
                if (Schema::hasColumn('sites', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
