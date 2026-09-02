<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pushnotifications', function (Blueprint $table) {
            if (!Schema::hasColumn('pushnotifications', 'subscriber_id')) {
                $table->unsignedBigInteger('subscriber_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('pushnotifications', 'category')) {
                $table->string('category', 30)->default('System')->after('type');
            }
            if (!Schema::hasColumn('pushnotifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('content');
            }
            if (!Schema::hasColumn('pushnotifications', 'data')) {
                $table->json('data')->nullable()->after('read_at');
            }
        });

        Schema::table('subscriber', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriber', 'device_token')) {
                $table->string('device_token', 255)->nullable();
            }
        });

        Schema::create('pushnotification_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pushnotification_id');
            $table->unsignedBigInteger('subscriber_id');
            $table->timestamp('read_at');
            $table->timestamps();
            $table->unique(['pushnotification_id', 'subscriber_id'], 'pushnotification_reads_unique');
        });

        Schema::table('pushnotifications', function (Blueprint $table) {
            $table->index(['subscriber_id', 'category', 'read_at'], 'pushnotifications_vendor_read_index');
        });
    }

    public function down(): void
    {
        Schema::table('pushnotifications', function (Blueprint $table) {
            $table->dropIndex('pushnotifications_vendor_read_index');
            foreach (['data', 'read_at', 'category', 'subscriber_id'] as $column) {
                if (Schema::hasColumn('pushnotifications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
        Schema::table('subscriber', function (Blueprint $table) {
            if (Schema::hasColumn('subscriber', 'device_token')) {
                $table->dropColumn('device_token');
            }
        });
        Schema::dropIfExists('pushnotification_reads');
    }
};
