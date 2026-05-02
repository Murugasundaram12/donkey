<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'rate_limit_per_minute')) {
                $table->unsignedInteger('rate_limit_per_minute')->default(60)->after('api_key');
            }
            if (!Schema::hasColumn('companies', 'daily_booking_limit')) {
                $table->unsignedInteger('daily_booking_limit')->nullable()->after('rate_limit_per_minute');
            }
            if (!Schema::hasColumn('companies', 'monthly_booking_limit')) {
                $table->unsignedInteger('monthly_booking_limit')->nullable()->after('daily_booking_limit');
            }
            if (!Schema::hasColumn('companies', 'current_month_bookings')) {
                $table->unsignedInteger('current_month_bookings')->default(0)->after('monthly_booking_limit');
            }
            if (!Schema::hasColumn('companies', 'booking_limit_reset_at')) {
                $table->timestamp('booking_limit_reset_at')->nullable()->after('current_month_bookings');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'rate_limit_per_minute',
                'daily_booking_limit',
                'monthly_booking_limit',
                'current_month_bookings',
                'booking_limit_reset_at',
            ]);
        });
    }
};
