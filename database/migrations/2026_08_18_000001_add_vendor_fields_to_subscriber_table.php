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
        Schema::table('subscriber', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriber', 'subscriberId')) {
                $table->string('subscriberId', 121)->nullable()->after('id');
            }
            if (!Schema::hasColumn('subscriber', 'password')) {
                $table->string('password', 200)->nullable()->after('email');
            }
            if (!Schema::hasColumn('subscriber', 'status')) {
                $table->integer('status')->default(1)->after('password');
            }
            if (!Schema::hasColumn('subscriber', 'expiryDate')) {
                $table->timestamp('expiryDate')->nullable()->after('subscriptionDate');
            }
            if (!Schema::hasColumn('subscriber', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'bankacno')) {
                $table->string('bankacno', 50)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'ifsccode')) {
                $table->string('ifsccode', 20)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'bankstatement')) {
                $table->string('bankstatement', 200)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'aadharBackImage')) {
                $table->string('aadharBackImage', 200)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'video')) {
                $table->string('video', 200)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'gst')) {
                $table->string('gst', 200)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'qr')) {
                $table->string('qr', 200)->nullable();
            }
            if (!Schema::hasColumn('subscriber', 'notify')) {
                $table->string('notify', 200)->default('0');
            }
            if (!Schema::hasColumn('subscriber', 'platform_fee')) {
                $table->string('platform_fee', 200)->default('0');
            }
            if (!Schema::hasColumn('subscriber', 'need_to_pay')) {
                $table->integer('need_to_pay')->default(0);
            }
            if (!Schema::hasColumn('subscriber', 'notification_settings')) {
                $table->text('notification_settings')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
