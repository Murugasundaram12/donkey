<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingApplicationColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_id')) {
                $table->string('user_id', 55)->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'is_googleUser')) {
                $table->integer('is_googleUser')->default(0)->after('user_id');
            }
            if (!Schema::hasColumn('users', 'firstname')) {
                $table->string('firstname', 45)->nullable()->after('is_googleUser');
            }
            if (!Schema::hasColumn('users', 'lastname')) {
                $table->string('lastname', 45)->nullable()->after('firstname');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city', 250)->nullable()->after('email_verified_at');
            }
            if (!Schema::hasColumn('users', 'country_code')) {
                $table->string('country_code', 250)->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('country_code');
            }
            if (!Schema::hasColumn('users', 'phone_code')) {
                $table->integer('phone_code')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'otp')) {
                $table->string('otp', 10)->nullable()->after('password');
            }
            if (!Schema::hasColumn('users', 'plant_code')) {
                $table->integer('plant_code')->nullable()->after('otp');
            }
            if (!Schema::hasColumn('users', 'is_live')) {
                $table->integer('is_live')->default(0)->after('plant_code');
            }
            if (!Schema::hasColumn('users', 'is_driver')) {
                $table->integer('is_driver')->default(0)->after('is_live');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department', 45)->nullable()->after('is_driver');
            }
            if (!Schema::hasColumn('users', 'profile_image')) {
                $table->string('profile_image', 200)->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'file_data')) {
                $table->string('file_data', 45)->nullable()->after('profile_image');
            }
            if (!Schema::hasColumn('users', 'last_login')) {
                $table->dateTime('last_login')->nullable()->after('file_data');
            }
            if (!Schema::hasColumn('users', 'roles')) {
                $table->string('roles', 45)->nullable()->after('last_login');
            }
            if (!Schema::hasColumn('users', 'email_verified')) {
                $table->tinyInteger('email_verified')->default(0)->after('created_at');
            }
            if (!Schema::hasColumn('users', 'phone_verified')) {
                $table->tinyInteger('phone_verified')->default(0)->after('email_verified');
            }
            if (!Schema::hasColumn('users', 'phone_verified_at')) {
                $table->dateTime('phone_verified_at')->nullable()->after('phone_verified');
            }
            if (!Schema::hasColumn('users', 'user_timezone')) {
                $table->string('user_timezone', 45)->nullable()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable()->after('user_timezone');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable()->after('dob');
            }
            if (!Schema::hasColumn('users', 'image')) {
                $table->string('image', 200)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'device_token')) {
                $table->string('device_token', 255)->nullable()->after('image');
            }
            if (!Schema::hasColumn('users', 'logout_time')) {
                $table->string('logout_time', 255)->nullable()->after('device_token');
            }
            if (!Schema::hasColumn('users', 'blockedstatus')) {
                $table->string('blockedstatus', 10)->default('1')->after('logout_time');
            }
            if (!Schema::hasColumn('users', 'coins')) {
                $table->string('coins', 255)->default('0')->after('blockedstatus');
            }
            if (!Schema::hasColumn('users', 'referred_by')) {
                $table->string('referred_by', 200)->nullable()->after('coins');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 200)->nullable()->after('referred_by');
            }
        });

        // Add indexes safely
        Schema::table('users', function (Blueprint $table) {
            $table->index('user_id', 'idx_users_user_id');
            $table->index('is_driver', 'idx_users_is_driver');
            $table->index('is_live', 'idx_users_is_live');
            $table->index('phone', 'idx_users_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_user_id');
            $table->dropIndex('idx_users_is_driver');
            $table->dropIndex('idx_users_is_live');
            $table->dropIndex('idx_users_phone');

            $columns = [
                'user_id', 'is_googleUser', 'firstname', 'lastname', 'city', 'country_code',
                'phone', 'phone_code', 'otp', 'plant_code', 'is_live', 'is_driver',
                'department', 'profile_image', 'file_data', 'last_login', 'roles',
                'email_verified', 'phone_verified', 'phone_verified_at', 'user_timezone',
                'dob', 'gender', 'image', 'device_token', 'logout_time', 'blockedstatus',
                'coins', 'referred_by', 'referral_code'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
