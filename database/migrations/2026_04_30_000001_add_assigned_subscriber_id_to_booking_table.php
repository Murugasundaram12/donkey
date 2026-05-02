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
        Schema::table('booking', function (Blueprint $table) {
            if (!Schema::hasColumn('booking', 'assigned_subscriber_id')) {
                $table->unsignedBigInteger('assigned_subscriber_id')->nullable()->after('company_id');
                $table->index('assigned_subscriber_id', 'booking_assigned_subscriber_id_idx');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            if (Schema::hasColumn('booking', 'assigned_subscriber_id')) {
                $table->dropIndex('booking_assigned_subscriber_id_idx');
                $table->dropColumn('assigned_subscriber_id');
            }
        });
    }
};
