<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_renewals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id');
            $table->string('cycle_key')->unique();
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('status')->default('quoted');
            $table->decimal('subscription_price', 12, 2);
            $table->decimal('subscription_gst', 12, 2);
            $table->unsignedTinyInteger('penalty_day')->default(0);
            $table->decimal('penalty_principal', 12, 2)->default(0);
            $table->decimal('penalty_gst', 12, 2)->default(0);
            $table->unsignedTinyInteger('renewal_days')->default(28);
            $table->unsignedTinyInteger('bonus_days')->default(0);
            $table->decimal('total_payable', 12, 2);
            $table->string('idempotency_key')->nullable()->unique();
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('payment_id')->nullable()->unique();
            $table->unsignedBigInteger('payment_details_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('before_due_notified_at')->nullable();
            $table->timestamp('due_notified_at')->nullable();
            $table->timestamp('deactivated_notified_at')->nullable();
            $table->timestamps();
            $table->index(['subscriber_id', 'due_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_renewals');
    }
};
