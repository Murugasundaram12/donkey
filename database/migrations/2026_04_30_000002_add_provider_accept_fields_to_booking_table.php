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
            if (!Schema::hasColumn('booking', 'provider_accepted_by')) {
                $table->unsignedBigInteger('provider_accepted_by')->nullable()->after('assigned_subscriber_id');
                $table->index('provider_accepted_by', 'booking_provider_accepted_by_idx');
            }

            if (!Schema::hasColumn('booking', 'provider_accepted_at')) {
                $table->timestamp('provider_accepted_at')->nullable()->after('provider_accepted_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            if (Schema::hasColumn('booking', 'provider_accepted_at')) {
                $table->dropColumn('provider_accepted_at');
            }

            if (Schema::hasColumn('booking', 'provider_accepted_by')) {
                $table->dropIndex('booking_provider_accepted_by_idx');
                $table->dropColumn('provider_accepted_by');
            }
        });
    }
};
