<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        $this->addIndex('booking', 'idx_booking_booking_id', ['booking_id']);
        $this->addIndex('booking', 'idx_booking_customer_id', ['customer_id']);
        $this->addIndex('booking', 'idx_booking_accepted', ['accepted']);
        $this->addIndex('booking', 'idx_booking_status', ['status']);
        $this->addIndex('booking', 'idx_booking_pincode', ['pincode']);
        $this->addIndex('booking', 'idx_booking_category', ['category']);
        $this->addIndex('booking', 'idx_booking_created_at', ['created_at']);
        $this->addIndex('booking', 'idx_booking_pincode_status', ['pincode', 'status']);
        $this->addIndex('booking', 'idx_booking_status_accepted', ['status', 'accepted']);

        $this->addIndex('booking_payment', 'idx_booking_payment_booking_id', ['booking_id']);
        $this->addIndex('booking_payment', 'idx_booking_payment_type', ['type']);
        $this->addIndex('booking_payment', 'idx_booking_payment_status', ['status']);

        $this->addIndex('booking_locations', 'idx_booking_locations_booking_id', ['booking_id']);
        $this->addIndex('booking_locations', 'idx_booking_locations_location_id', ['location_id']);
        $this->addIndex('booking_location_mapping', 'idx_booking_location_mapping_booking_id', ['booking_id']);

        $this->addIndex('pincode', 'idx_pincode_pincode', ['pincode']);
        $this->addIndex('pincode', 'idx_pincode_used_by', ['usedBy']);

        $this->addIndex('subscriber', 'idx_subscriber_subscriber_id', ['subscriberId']);
        $this->addIndex('subscriber', 'idx_subscriber_created_by', ['created_by']);
        $this->addIndex('subscriber', 'idx_subscriber_active_blocked', ['activestatus', 'blockedstatus']);
        $this->addIndex('subscriber', 'idx_subscriber_expiry_date', ['expiryDate']);

        $this->addIndex('driver', 'idx_driver_userid', ['userid']);
        $this->addIndex('driver', 'idx_driver_subscriber_status', ['subscriberId', 'status']);
        $this->addIndex('driver', 'idx_driver_type', ['type']);

        $this->addIndex('users', 'idx_users_user_id', ['user_id']);
        $this->addIndex('users', 'idx_users_is_driver', ['is_driver']);
        $this->addIndex('users', 'idx_users_is_live', ['is_live']);

        $this->addIndex('price', 'idx_price_pincode_category_range', ['pincode', 'category', 'range_from', 'range_to']);
        $this->addIndex('price', 'idx_price_subscriber_id', ['subscriber_id']);

        $this->addIndex('complaints', 'idx_complaints_subscriber_status', ['subscriberId', 'status']);
        $this->addIndex('enquiry', 'idx_enquiry_subscriber_id', ['subscriberId']);
        $this->addIndex('employees', 'idx_employees_subscriber_id', ['subscriber_id']);
        $this->addIndex('pricenotify', 'idx_pricenotify_modified_read', ['modifiedId', 'readBy']);
        $this->addIndex('driver_notify', 'idx_driver_notify_modified_read', ['modifiedId', 'readBy']);
    }

    public function down()
    {
        foreach ([
            'booking' => [
                'idx_booking_booking_id',
                'idx_booking_customer_id',
                'idx_booking_accepted',
                'idx_booking_status',
                'idx_booking_pincode',
                'idx_booking_category',
                'idx_booking_created_at',
                'idx_booking_pincode_status',
                'idx_booking_status_accepted',
            ],
            'booking_payment' => [
                'idx_booking_payment_booking_id',
                'idx_booking_payment_type',
                'idx_booking_payment_status',
            ],
            'booking_locations' => [
                'idx_booking_locations_booking_id',
                'idx_booking_locations_location_id',
            ],
            'booking_location_mapping' => ['idx_booking_location_mapping_booking_id'],
            'pincode' => ['idx_pincode_pincode', 'idx_pincode_used_by'],
            'subscriber' => [
                'idx_subscriber_subscriber_id',
                'idx_subscriber_created_by',
                'idx_subscriber_active_blocked',
                'idx_subscriber_expiry_date',
            ],
            'driver' => [
                'idx_driver_userid',
                'idx_driver_subscriber_status',
                'idx_driver_type',
            ],
            'users' => [
                'idx_users_user_id',
                'idx_users_is_driver',
                'idx_users_is_live',
            ],
            'price' => [
                'idx_price_pincode_category_range',
                'idx_price_subscriber_id',
            ],
            'complaints' => ['idx_complaints_subscriber_status'],
            'enquiry' => ['idx_enquiry_subscriber_id'],
            'employees' => ['idx_employees_subscriber_id'],
            'pricenotify' => ['idx_pricenotify_modified_read'],
            'driver_notify' => ['idx_driver_notify_modified_read'],
        ] as $table => $indexes) {
            foreach ($indexes as $index) {
                $this->dropIndex($table, $index);
            }
        }
    }

    private function addIndex(string $table, string $index, array $columns): void
    {
        if (!Schema::hasTable($table) || $this->indexExists($table, $index)) {
            return;
        }

        $wrappedColumns = collect($columns)
            ->filter(fn ($column) => Schema::hasColumn($table, $column))
            ->map(fn ($column) => "`{$column}`")
            ->implode(', ');

        if ($wrappedColumns === '') {
            return;
        }

        DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$index}` ({$wrappedColumns})");
    }

    private function dropIndex(string $table, string $index): void
    {
        if (Schema::hasTable($table) && $this->indexExists($table, $index)) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$index}`");
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        $database = DB::getDatabaseName();

        return !empty(DB::select(
            'SELECT 1 FROM information_schema.statistics WHERE table_schema = ? AND table_name = ? AND index_name = ? LIMIT 1',
            [$database, $table, $index]
        ));
    }
}
