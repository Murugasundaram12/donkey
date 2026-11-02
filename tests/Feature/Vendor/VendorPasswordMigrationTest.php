<?php

namespace Tests\Feature\Vendor;

use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Employee;
use App\Models\Booking;
use App\Models\site;
use App\Models\statusnotify;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class VendorPasswordMigrationTest extends TestCase
{
    protected string $isolatedDbPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->isolatedDbPath = database_path('isolated_test.sqlite');
        if (!file_exists($this->isolatedDbPath)) {
            touch($this->isolatedDbPath);
        }

        // Strictly configure isolated SQLite connection
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => $this->isolatedDbPath,
            'database.connections.sqlite.foreign_key_constraints' => false,
        ]);

        DB::purge('mysql');
        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        // Confirm we are strictly on sqlite and isolated db
        $this->assertEquals('sqlite', DB::getDefaultConnection());
        $this->assertEquals($this->isolatedDbPath, DB::connection()->getDatabaseName());

        $this->createIsolatedTables();
    }

    protected function tearDown(): void
    {
        DB::purge('sqlite');
        parent::tearDown();
    }

    private function createIsolatedTables(): void
    {
        Schema::connection('sqlite')->dropAllTables();

        Schema::connection('sqlite')->create('subscriber', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Test Vendor');
            $table->string('subscriberId', 121)->nullable();
            $table->string('created_by', 20)->nullable()->default('1');
            $table->string('location')->default('Test Location');
            $table->string('subscriptionDate')->default('2026-01-01');
            $table->timestamp('expiryDate')->nullable();
            $table->string('subscription_price', 121)->nullable()->default('100');
            $table->text('description')->nullable();
            $table->string('password', 200)->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('pincode')->nullable()->default('["600001"]');
            $table->timestamp('last_recharge_date')->nullable();
            $table->string('aadharNo')->default('123456789012');
            $table->string('aadharImage')->default('front.jpg');
            $table->string('aadharBackImage', 200)->nullable();
            $table->string('pancardImage')->default('pan.jpg');
            $table->string('bankstatement', 200)->default('stmt.jpg');
            $table->string('account_type')->default('Individual');
            $table->string('customerdocument', 200)->nullable();
            $table->string('bankacno', 20)->nullable();
            $table->string('ifsccode', 20)->nullable();
            $table->string('image')->default('profile.jpg');
            $table->integer('status')->default(1);
            $table->string('blockedstatus', 10)->nullable()->default('1');
            $table->string('activestatus', 10)->nullable()->default('1');
            $table->string('remember_token', 100)->nullable();
            $table->string('device_token')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->string('video', 200)->nullable();
            $table->string('gst', 200)->nullable();
            $table->string('qr', 200)->nullable();
            $table->string('notify', 200)->default('0');
            $table->string('platform_fee', 200)->default('0');
            $table->integer('need_to_pay')->default(0);
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('official_mail')->nullable();
            $table->string('official_mobile')->nullable();
            $table->string('profile')->nullable();
            $table->string('emp_id')->nullable();
            $table->string('address')->nullable();
            $table->string('current_address')->nullable();
            $table->string('location')->nullable();
            $table->string('aadhar')->nullable();
            $table->string('password')->nullable();
            $table->unsignedBigInteger('subscriber_id')->nullable();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id')->unique();
            $table->unsignedBigInteger('assigned_subscriber_id')->nullable();
            $table->string('subscriberId')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('status')->default(1);
            $table->integer('category')->default(1);
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    private function createVendor(array $attributes = []): Subscriber
    {
        return Subscriber::create(array_merge([
            'name' => 'Test Vendor',
            'subscriberId' => 'SUB-' . uniqid(),
            'email' => 'vendor_' . uniqid() . '@test.com',
            'mobile' => '9' . rand(100000000, 999999999),
            'password' => 'default_plain_pass',
            'status' => 1,
            'blockedstatus' => '1',
            'activestatus' => '1',
            'created_by' => '1',
            'subscriptionDate' => '2026-01-01',
            'expiryDate' => now()->addYear()->format('Y-m-d H:i:s'),
            'device_token' => 'init_tok',
        ], $attributes));
    }

    /**
     * TEST 1: Plaintext API login migrates to Bcrypt.
     */
    public function test_plaintext_api_login_migrates_to_bcrypt()
    {
        $vendor = $this->createVendor([
            'email' => 'plain_api@test.com',
            'password' => 'secretplain123',
        ]);

        $this->assertEquals('secretplain123', $vendor->password);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'plain_api@test.com',
            'password' => 'secretplain123',
            'device_token' => 'fcm_tok_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);

        $this->assertNotEmpty($response->json('data.token'));

        $vendor->refresh();
        $this->assertTrue(Hash::check('secretplain123', $vendor->password));
        $this->assertStringStartsWith('$2y$', $vendor->password);
    }

    /**
     * TEST 2: Trimmed plaintext API fallback preserves the matched candidate.
     */
    public function test_trimmed_plaintext_api_fallback_preserves_matched_candidate()
    {
        $vendor = $this->createVendor([
            'email' => 'trimmed_api@test.com',
            'password' => 'trimmedSecret456',
        ]);

        // Client sends whitespace around password
        $response = $this->postJson('/api/vendor/login', [
            'login' => 'trimmed_api@test.com',
            'password' => "  trimmedSecret456  \n",
            'device_token' => 'fcm_tok_trim',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);

        $vendor->refresh();
        // The hashed value must be the trimmed candidate that matched the database
        $this->assertTrue(Hash::check('trimmedSecret456', $vendor->password));
        $this->assertFalse(Hash::check("  trimmedSecret456  \n", $vendor->password));
    }

    /**
     * TEST 3: Subsequent Bcrypt API login succeeds without rehashing.
     */
    public function test_subsequent_bcrypt_api_login_succeeds_without_rehashing()
    {
        $hashed = Hash::make('bcrypt_pass_789');
        $vendor = $this->createVendor([
            'email' => 'bcrypt_api@test.com',
            'password' => $hashed,
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'bcrypt_api@test.com',
            'password' => 'bcrypt_pass_789',
            'device_token' => 'fcm_tok_bcrypt',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $vendor->refresh();
        // Stored hash must be completely identical (no rehashing fired)
        $this->assertEquals($hashed, $vendor->password);
    }

    /**
     * TEST 4: Plaintext Web login migrates to Bcrypt.
     */
    public function test_plaintext_web_login_migrates_to_bcrypt()
    {
        $vendor = $this->createVendor([
            'email' => 'plain_web@test.com',
            'password' => 'plain_web_secret',
            'created_by' => '1',
        ]);

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'plain_web@test.com',
            'password' => 'plain_web_secret',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        $vendor->refresh();
        $this->assertTrue(Hash::check('plain_web_secret', $vendor->password));
        $this->assertStringStartsWith('$2y$', $vendor->password);
    }

    /**
     * TEST 5: Subsequent Bcrypt Web login succeeds without rehashing.
     */
    public function test_subsequent_bcrypt_web_login_succeeds_without_rehashing()
    {
        $hashed = Hash::make('bcrypt_web_secret');
        $vendor = $this->createVendor([
            'email' => 'bcrypt_web@test.com',
            'password' => $hashed,
            'created_by' => '1',
        ]);

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'bcrypt_web@test.com',
            'password' => 'bcrypt_web_secret',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        $vendor->refresh();
        $this->assertEquals($hashed, $vendor->password);
    }

    /**
     * TEST 6: Wrong password does not modify the stored password.
     */
    public function test_wrong_password_does_not_modify_stored_password()
    {
        $vendor = $this->createVendor([
            'email' => 'wrong_pass@test.com',
            'password' => 'original_secret',
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'wrong_pass@test.com',
            'password' => 'incorrect_password',
            'device_token' => 'fcm_tok_err',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
            ]);

        $vendor->refresh();
        $this->assertEquals('original_secret', $vendor->password);
    }

    /**
     * TEST 7: Blocked/inactive accounts are not migrated.
     */
    public function test_blocked_and_inactive_accounts_are_not_migrated()
    {
        // Blocked account
        $blockedVendor = $this->createVendor([
            'email' => 'blocked@test.com',
            'password' => 'plain_blocked_pass',
            'blockedstatus' => '0',
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'blocked@test.com',
            'password' => 'plain_blocked_pass',
            'device_token' => 'fcm_tok_block',
        ]);

        $response->assertStatus(403);
        $blockedVendor->refresh();
        $this->assertEquals('plain_blocked_pass', $blockedVendor->password);

        // Inactive account
        $inactiveVendor = $this->createVendor([
            'email' => 'inactive@test.com',
            'password' => 'plain_inactive_pass',
            'activestatus' => '0',
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'inactive@test.com',
            'password' => 'plain_inactive_pass',
            'device_token' => 'fcm_tok_inact',
        ]);

        $response->assertStatus(403);
        $inactiveVendor->refresh();
        $this->assertEquals('plain_inactive_pass', $inactiveVendor->password);
    }

    /**
     * TEST 8: Conditional update affecting zero rows is safely handled.
     */
    public function test_conditional_update_affecting_zero_rows_is_safely_handled()
    {
        $vendor = $this->createVendor([
            'email' => 'race_success@test.com',
            'password' => 'concurrent_pass_123',
        ]);

        // Simulate concurrent update before update query executes:
        // When query fires on 'subscriber', concurrently update the DB password to valid bcrypt
        $updatedBefore = false;
        DB::listen(function ($query) use ($vendor, &$updatedBefore) {
            if (!$updatedBefore && strpos($query->sql, 'update "subscriber" set "password"') !== false) {
                $updatedBefore = true;
                DB::table('subscriber')
                    ->where('id', $vendor->id)
                    ->update(['password' => Hash::make('concurrent_pass_123')]);
            }
        });

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'race_success@test.com',
            'password' => 'concurrent_pass_123',
            'device_token' => 'fcm_tok_race',
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => true]);

        $vendor->refresh();
        $this->assertTrue(Hash::check('concurrent_pass_123', $vendor->password));
    }

    /**
     * TEST 9: Reloaded password verification failure prevents authentication.
     */
    public function test_reloaded_password_verification_failure_prevents_authentication()
    {
        $vendor = $this->createVendor([
            'email' => 'race_fail@test.com',
            'password' => 'original_race_pass',
        ]);

        // Simulate concurrent password reset to a DIFFERENT password
        $updatedBefore = false;
        DB::listen(function ($query) use ($vendor, &$updatedBefore) {
            if (!$updatedBefore && strpos($query->sql, 'update "subscriber" set "password"') !== false) {
                $updatedBefore = true;
                DB::table('subscriber')
                    ->where('id', $vendor->id)
                    ->update(['password' => Hash::make('completely_new_reset_pass')]);
            }
        });

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'race_fail@test.com',
            'password' => 'original_race_pass',
            'device_token' => 'fcm_tok_fail',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Authentication service temporarily unavailable. Please try again.',
            ]);
    }

    /**
     * TEST 10: Database migration failure prevents authentication.
     */
    public function test_database_migration_failure_prevents_authentication()
    {
        $vendor = $this->createVendor([
            'email' => 'db_error@test.com',
            'password' => 'plain_err_pass',
        ]);

        // Simulate fatal database error during update query
        DB::listen(function ($query) {
            if (strpos($query->sql, 'update "subscriber" set "password"') !== false) {
                throw new \Exception('Simulated database write lock failure');
            }
        });

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'db_error@test.com',
            'password' => 'plain_err_pass',
            'device_token' => 'fcm_tok_err',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'message' => 'Authentication service temporarily unavailable. Please try again.',
            ]);
    }

    /**
     * TEST 11: Existing Sanctum tokens remain valid across migration.
     */
    public function test_existing_sanctum_tokens_remain_valid()
    {
        $vendor = $this->createVendor([
            'email' => 'sanctum_token@test.com',
            'password' => 'plain_token_pass',
        ]);

        // Create token prior to password migration
        $token = $vendor->createToken('vendor_app_token')->plainTextToken;

        // Verify initial token access
        $this->withToken($token)->getJson('/api/vendor/me')
            ->assertStatus(200)
            ->assertJson(['status' => true]);

        // Perform login that triggers password migration
        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'sanctum_token@test.com',
            'password' => 'plain_token_pass',
            'device_token' => 'new_fcm_tok',
        ]);
        $loginResponse->assertStatus(200);

        // Verify pre-existing token STILL works
        $this->withToken($token)->getJson('/api/vendor/me')
            ->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'sanctum_token@test.com',
                    ]
                ]
            ]);
    }

    /**
     * TEST 12: Expired vendor login and renewal behavior remain unchanged.
     */
    public function test_expired_vendor_login_and_renewal_behavior_remain_unchanged()
    {
        // Expired vendor
        $expiredVendor = $this->createVendor([
            'email' => 'expired_vendor@test.com',
            'password' => 'plain_expired_pass',
            'expiryDate' => now()->subDays(10)->format('Y-m-d H:i:s'),
            'status' => 1,
            'blockedstatus' => '1',
            'activestatus' => '1',
            'created_by' => '1',
        ]);

        // API login succeeds with payment_status = 0 and migrates password
        $apiResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_vendor@test.com',
            'password' => 'plain_expired_pass',
            'device_token' => 'fcm_tok_exp',
        ]);

        $apiResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'payment_status' => 0,
                    ]
                ]
            ]);

        $expiredVendor->refresh();
        $this->assertTrue(Hash::check('plain_expired_pass', $expiredVendor->password));

        // Web login for expired subscriber redirects to makePlatFormFee
        $webVendor = $this->createVendor([
            'email' => 'expired_web@test.com',
            'password' => 'plain_web_expired',
            'expiryDate' => now()->subDays(5)->format('Y-m-d H:i:s'),
            'status' => 1,
            'blockedstatus' => '1',
            'activestatus' => '1',
            'created_by' => '1',
        ]);

        $webResponse = $this->post('/subscribers/loginaction', [
            'email' => 'expired_web@test.com',
            'password' => 'plain_web_expired',
        ]);

        $webResponse->assertRedirect(route('makePlatFormFee', ['Id' => $webVendor->id]));

        $webVendor->refresh();
        $this->assertTrue(Hash::check('plain_web_expired', $webVendor->password));
    }

    /**
     * TEST 13: Employee login remains unchanged and neither record is corrupted.
     */
    public function test_employee_login_remains_unchanged()
    {
        $subscriber = $this->createVendor([
            'email' => 'emp_shared@test.com',
            'password' => 'subscriber_secret_123',
            'status' => 1,
            'blockedstatus' => '1',
            'expiryDate' => now()->addYear()->format('Y-m-d H:i:s'),
        ]);

        $employee = Employee::create([
            'name' => 'Shared Employee',
            'email' => 'emp_shared@test.com',
            'emp_id' => 'EMP-SHARED-001',
            'official_mail' => 'emp_shared@test.com',
            'mobile' => '9123456780',
            'official_mobile' => '9123456780',
            'password' => 'employee_plain_pass_456',
            'subscriber_id' => $subscriber->id,
        ]);

        // Attempt login using Employee's password
        $response = $this->post('/subscribers/loginaction', [
            'email' => 'emp_shared@test.com',
            'password' => 'employee_plain_pass_456',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        // Confirm authenticated as employee
        $this->assertTrue(auth()->guard('employee')->check());
        $this->assertEquals($employee->id, auth()->guard('employee')->id());

        // Confirm employee record is untouched
        $employee->refresh();
        $this->assertEquals('employee_plain_pass_456', $employee->password);

        // Confirm subscriber record is untouched
        $subscriber->refresh();
        $this->assertEquals('subscriber_secret_123', $subscriber->password);
    }

    /**
     * TEST 14: Booking, payment, and subscription records remain unchanged.
     */
    public function test_booking_payment_and_subscription_records_remain_unchanged()
    {
        $vendor = $this->createVendor([
            'subscriberId' => 'SUB-IMMUTABLE-001',
            'email' => 'immutable@test.com',
            'mobile' => '9988776655',
            'password' => 'plain_immutable_pass',
            'subscriptionDate' => '2025-05-15',
            'expiryDate' => '2030-05-15 23:59:59',
            'subscription_price' => '399.50',
            'status' => 1,
            'blockedstatus' => '1',
            'activestatus' => '1',
        ]);

        $booking = Booking::create([
            'booking_id' => 'BK-IMMUTABLE-999',
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => '600001',
            'status' => 2,
            'category' => 1,
        ]);

        // Pre-migration snapshots
        $origSubscriberId = $vendor->subscriberId;
        $origEmail = $vendor->email;
        $origMobile = $vendor->mobile;
        $origSubscriptionDate = $vendor->subscriptionDate;
        $origExpiryDate = $vendor->expiryDate;
        $origSubscriptionPrice = $vendor->subscription_price;

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'immutable@test.com',
            'password' => 'plain_immutable_pass',
            'device_token' => 'fcm_tok_immut',
        ]);
        $response->assertStatus(200);

        $vendor->refresh();
        $booking->refresh();

        // Password upgraded
        $this->assertTrue(Hash::check('plain_immutable_pass', $vendor->password));

        // Other fields strictly unchanged
        $this->assertEquals($origSubscriberId, $vendor->subscriberId);
        $this->assertEquals($origEmail, $vendor->email);
        $this->assertEquals($origMobile, $vendor->mobile);
        $this->assertEquals($origSubscriptionDate, $vendor->subscriptionDate);
        $this->assertEquals($origExpiryDate, $vendor->expiryDate);
        $this->assertEquals($origSubscriptionPrice, $vendor->subscription_price);

        // Booking record strictly unchanged
        $this->assertEquals('BK-IMMUTABLE-999', $booking->booking_id);
        $this->assertEquals($vendor->id, $booking->assigned_subscriber_id);
        $this->assertEquals(2, $booking->status);
    }
}
