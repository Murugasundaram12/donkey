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
        if (!Schema::hasTable('booking')) {
            Schema::create('booking', function (Blueprint $table) {
                $table->id();
                $table->string('booking_id', 65)->nullable();
                $table->string('customer_id', 55)->nullable();
                $table->string('driver_id', 55)->nullable();
                $table->string('payment_id', 85)->nullable();
                $table->integer('status')->default(0);
                $table->string('cancelledby', 10)->nullable();
                $table->string('reason', 500)->nullable();
                $table->integer('category')->default(1);
                $table->float('distance')->nullable();
                $table->string('duration', 200)->nullable();
                $table->string('pincode', 45)->nullable();
                $table->string('otp', 100)->nullable();
                $table->integer('accepted')->nullable();
                $table->string('ignored', 500)->nullable();
                $table->string('user_lat', 500)->nullable();
                $table->string('user_long', 500)->nullable();
                $table->string('driver_lat', 500)->nullable();
                $table->string('driver_long', 500)->nullable();
                $table->string('speed', 500)->nullable();
                $table->string('title', 200)->nullable();
                $table->longText('content')->nullable();
                $table->tinyInteger('source')->default(0)->nullable();
                $table->string('external_name', 100)->nullable();
                $table->string('external_phone', 20)->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->unsignedBigInteger('assigned_subscriber_id')->nullable();
                $table->unsignedBigInteger('provider_accepted_by')->nullable();
                $table->timestamp('provider_accepted_at')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('booking');
    }
};
