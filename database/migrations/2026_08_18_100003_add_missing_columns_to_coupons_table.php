<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'title')) {
                $table->string('title')->nullable()->after('id');
            }

            if (!Schema::hasColumn('coupons', 'image')) {
                $table->string('image')->nullable()->after('title');
            }

            if (!Schema::hasColumn('coupons', 'type')) {
                $table->unsignedTinyInteger('type')->default(1)->after('image');
            }

            if (!Schema::hasColumn('coupons', 'is_multiple')) {
                $table->unsignedTinyInteger('is_multiple')->default(0)->after('type');
            }

            if (!Schema::hasColumn('coupons', 'pincode_id')) {
                $table->unsignedBigInteger('pincode_id')->nullable()->after('is_multiple');
            }

            if (!Schema::hasColumn('coupons', 'limit')) {
                $table->integer('limit')->default(0)->after('pincode_id');
            }

            if (!Schema::hasColumn('coupons', 'start_date')) {
                $table->date('start_date')->nullable()->after('limit');
            }

            if (!Schema::hasColumn('coupons', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('coupons', 'discount_type')) {
                $table->unsignedTinyInteger('discount_type')->default(1)->after('expiry_date');
            }

            if (!Schema::hasColumn('coupons', 'amount')) {
                $table->decimal('amount', 10, 2)->nullable()->after('discount_type');
            }

            if (!Schema::hasColumn('coupons', 'percentage')) {
                $table->decimal('percentage', 5, 2)->nullable()->after('amount');
            }

            if (!Schema::hasColumn('coupons', 'status')) {
                $table->unsignedTinyInteger('status')->default(1)->after('percentage');
            }

            if (!Schema::hasColumn('coupons', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('status');
            }

            if (!Schema::hasColumn('coupons', 'role')) {
                $table->string('role')->nullable()->after('created_by');
            }
        });

        DB::table('coupons')->orderBy('id')->chunkById(100, function ($coupons) {
            foreach ($coupons as $coupon) {
                $title = trim((string) ($coupon->title ?? $coupon->name ?? ''));
                $startDate = trim((string) ($coupon->start_date ?? $coupon->valid_from ?? ''));
                $expiryDate = trim((string) ($coupon->expiry_date ?? $coupon->valid_to ?? ''));

                DB::table('coupons')
                    ->where('id', $coupon->id)
                    ->update([
                        'title' => $title !== '' ? $title : null,
                        'limit' => is_numeric($coupon->limit ?? null)
                            ? $coupon->limit
                            : (is_numeric($coupon->user_limit ?? null) ? $coupon->user_limit : 0),
                        'start_date' => $startDate !== '' ? $startDate : null,
                        'expiry_date' => $expiryDate !== '' ? $expiryDate : null,
                        'status' => is_numeric($coupon->status ?? null) ? $coupon->status : 1,
                    ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            foreach ([
                'title',
                'image',
                'type',
                'is_multiple',
                'pincode_id',
                'limit',
                'start_date',
                'expiry_date',
                'discount_type',
                'amount',
                'percentage',
                'status',
                'created_by',
                'role',
            ] as $column) {
                if (Schema::hasColumn('coupons', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
