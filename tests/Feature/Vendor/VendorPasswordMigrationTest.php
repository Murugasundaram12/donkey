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
    protected static bool $schemaInitialized = false;
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
        $this->clearIsolatedTables();
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
            $table->string('created_by', 20)->nullable()->default('0');
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
            $table->string('role')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('tokenable_type');
            $table->unsignedBigInteger('tokenable_id');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('statusnotifies', function (Blueprint $table) {
            $table->id();
            $table->string('datas')->nullable();
            $table->unsignedBigInteger('modifiedId')->nullable();
            $table->string('modifiedBy')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('sites', function (Blueprint $table) {
            $table->id();
            $table->integer('maintainance')->default(0);
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['role_id', 'model_id', 'model_type']);
        });

        Schema::connection('sqlite')->create('model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->primary(['permission_id', 'model_id', 'model_type']);
        });

        Schema::connection('sqlite')->create('role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            $table->primary(['permission_id', 'role_id']);
        });

        Schema::connection('sqlite')->create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->unsignedBigInteger('assigned_subscriber_id')->nullable();
            $table->string('pincode')->nullable();
            $table->integer('status')->default(0);
            $table->integer('category')->default(1);
            $table->timestamps();
        });

        Schema::connection('sqlite')->create('pincodebasedcategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscriber_id')->nullable();
            $table->string('pincode_id')->nullable();
            $table->string('category_id')->nullable();
            $table->timestamps();
        });

        self::$schemaInitialized = true;
    }

    private function clearIsolatedTables(): void
    {
        DB::connection('sqlite')->table('pincodebasedcategories')->truncate();
        DB::connection('sqlite')->table('booking')->truncate();
        DB::connection('sqlite')->table('personal_access_tokens')->truncate();
        DB::connection('sqlite')->table('employees')->truncate();
        DB::connection('sqlite')->table('statusnotifies')->truncate();
        DB::connection('sqlite')->table('sites')->truncate();
        DB::connection('sqlite')->table('model_has_roles')->truncate();
        DB::connection('sqlite')->table('roles')->truncate();
        DB::connection('sqlite')->table('subscriber')->truncate();

        // Seed default site record
        site::create(['id' => 1, 'maintainance' => 0]);

        // Seed Subscriber Admin role
        Role::create(['name' => 'Subscriber Admin', 'guard_name' => 'subscriber']);
    }

    private function createVendor(array $overrides = []): Subscriber
    {
        return Subscriber::create(array_merge([
            'name' => 'John Vendor',
            'subscriberId' => 'SUB-999',
            'email' => 'vendor@migration-test.com',
            'mobile' => '9876543210',
            'password' => 'legacy_plain_pass_123',
            'pincode' => '["600001"]',
            'status' => 1,
            'blockedstatus' => '1',
            'activestatus' => '1',
            'expiryDate' => now()->addYear()->format('Y-m-d H:i:s'),
            'subscriptionDate' => '2026-01-01',
            'subscription_price' => '250.00',
            'created_by' => '0',
            'device_token' => 'init_device_token',
        ], $overrides));
    }

    /**
     * TEST 1: Plaintext API login succeeds and upgrades password.
     */
    public function test_plaintext_api_login_succeeds_and_upgrades_password()
    {
        $vendor = $this->createVendor(['password' => 'secret_plain_pass']);
        $this->assertEquals('secret_plain_pass', $vendor->password);

        $response = $this->postJson('/api/vendor/login', [
            'login' => $vendor->email,
            'password' => 'secret_plain_pass',
            'device_token' => 'token_abc',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'token_type',
                    'vendor' => [
                        'id',
                        'subscriber_id',
                        'name',
                        'email',
                        'mobile',
                    ]
                ]
            ]);

        // Check password in isolated database is now hashed with Bcrypt
        $vendor->refresh();
        $this->assertNotEquals('secret_plain_pass', $vendor->password);
        $this->assertTrue(Hash::check('secret_plain_pass', $vendor->password));
        $this->assertStringStartsWith('$2y$', $vendor->password);
    }

    /**
     * TEST 2: Second API login succeeds using Bcrypt.
     */
    public function test_second_api_login_succeeds_using_bcrypt()
    {
        $vendor = $this->createVendor(['password' => 'secret_plain_pass']);

        // First login: upgrades plaintext to Bcrypt
        $this->postJson('/api/vendor/login', [
            'login' => $vendor->email,
            'password' => 'secret_plain_pass',
            'device_token' => 'token_first',
        ])->assertStatus(200);

        $vendor->refresh();
        $storedHashAfterFirstLogin = $vendor->password;
        $this->assertTrue(Hash::check('secret_plain_pass', $storedHashAfterFirstLogin));

        // Second login: verifies against Bcrypt, does NOT rehash
        $response = $this->postJson('/api/vendor/login', [
            'login' => $vendor->email,
            'password' => 'secret_plain_pass',
            'device_token' => 'token_second',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);

        $vendor->refresh();
        // The Bcrypt hash must remain identical, proving no unnecessary rehashing occurred
        $this->assertEquals($storedHashAfterFirstLogin, $vendor->password);
    }

    /**
     * TEST 3: Plaintext Web login succeeds and upgrades password.
     */
    public function test_plaintext_web_login_succeeds_and_upgrades_password()
    {
        $vendor = $this->createVendor([
            'email' => 'web_vendor@migration-test.com',
            'password' => 'web_plain_pass',
        ]);
        $this->assertEquals('web_plain_pass', $vendor->password);

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'web_vendor@migration-test.com',
            'password' => 'web_plain_pass',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        $vendor->refresh();
        $this->assertNotEquals('web_plain_pass', $vendor->password);
        $this->assertTrue(Hash::check('web_plain_pass', $vendor->password));
        $this->assertStringStartsWith('$2y$', $vendor->password);
    }

    /**
     * TEST 4: Second Web login succeeds using Bcrypt.
     */
    public function test_second_web_login_succeeds_using_bcrypt()
    {
        $vendor = $this->createVendor([
            'email' => 'web_vendor2@migration-test.com',
            'password' => 'web_plain_pass_2',
        ]);

        // First login: upgrades to Bcrypt
        $this->post('/subscribers/loginaction', [
            'email' => 'web_vendor2@migration-test.com',
            'password' => 'web_plain_pass_2',
        ])->assertRedirect(route('subscribers.dashboard'));

        $vendor->refresh();
        $firstHash = $vendor->password;
        $this->assertTrue(Hash::check('web_plain_pass_2', $firstHash));

        // Flush session for fresh second login
        session()->flush();
        auth()->guard('subscriber')->logout();

        // Second login: verifies with Bcrypt
        $response = $this->post('/subscribers/loginaction', [
            'email' => 'web_vendor2@migration-test.com',
            'password' => 'web_plain_pass_2',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        $vendor->refresh();
        $this->assertEquals($firstHash, $vendor->password);
    }

    /**
     * TEST 5: Incorrect password does not change the database password.
     */
    public function test_incorrect_password_does_not_change_database_password()
    {
        $vendor = $this->createVendor([
            'email' => 'wrongpass@test.com',
            'password' => 'correct_legacy_password',
        ]);

        // Failed API login attempt
        $apiResponse = $this->postJson('/api/vendor/login', [
            'login' => 'wrongpass@test.com',
            'password' => 'wrong_password_attempt',
            'device_token' => 'dev_tok',
        ]);
        $apiResponse->assertStatus(401);

        $vendor->refresh();
        $this->assertEquals('correct_legacy_password', $vendor->password);

        // Failed Web login attempt
        $webResponse = $this->post('/subscribers/loginaction', [
            'email' => 'wrongpass@test.com',
            'password' => 'wrong_password_attempt',
        ]);
        $webResponse->assertSessionHas('error', 'Wrong Credentials');

        $vendor->refresh();
        $this->assertEquals('correct_legacy_password', $vendor->password);
    }

    /**
     * TEST 6: Blocked/inactive account behavior remains unchanged.
     */
    public function test_blocked_and_inactive_account_behavior_remains_unchanged()
    {
        // 6A: Blocked vendor on API
        $blockedVendor = $this->createVendor([
            'email' => 'blocked_api@test.com',
            'password' => 'blocked_pass_123',
            'blockedstatus' => '0',
        ]);

        $responseApi = $this->postJson('/api/vendor/login', [
            'login' => 'blocked_api@test.com',
            'password' => 'blocked_pass_123',
            'device_token' => 'tok',
        ]);
        $responseApi->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact admin.',
            ]);

        // Assert blocked vendor password is NOT changed/migrated
        $blockedVendor->refresh();
        $this->assertEquals('blocked_pass_123', $blockedVendor->password);

        // 6B: Blocked subscriber on Web
        $blockedWebVendor = $this->createVendor([
            'email' => 'blocked_web@test.com',
            'password' => 'blocked_web_pass',
            'blockedstatus' => '0',
        ]);

        $responseWebBlocked = $this->post('/subscribers/loginaction', [
            'email' => 'blocked_web@test.com',
            'password' => 'blocked_web_pass',
        ]);
        $responseWebBlocked->assertSessionHas('error', 'You have been  blocked.Please Contact admin!');

        // Assert blocked web subscriber password is NOT changed/migrated
        $blockedWebVendor->refresh();
        $this->assertEquals('blocked_web_pass', $blockedWebVendor->password);

        // 6C: Inactive/Expired subscriber on Web
        $expiredWebVendor = $this->createVendor([
            'email' => 'expired_web@test.com',
            'password' => 'expired_pass',
            'status' => 1,
            'blockedstatus' => '1',
            'expiryDate' => '01-01-2020',
        ]);

        $responseWebExpired = $this->post('/subscribers/loginaction', [
            'email' => 'expired_web@test.com',
            'password' => 'expired_pass',
        ]);
        $responseWebExpired->assertSessionHas('error', 'Your not activated (Your Account Expiried ).Please Contact admin!');

        $expiredWebVendor->refresh();
        $this->assertEquals(0, $expiredWebVendor->status);
        // Assert expired subscriber password is NOT changed/migrated
        $this->assertEquals('expired_pass', $expiredWebVendor->password);
        $this->assertDatabaseHas('statusnotifies', [
            'modifiedId' => $expiredWebVendor->id,
            'datas' => 'Payment due',
        ]);

        // 6D: Duplicate subscriber accounts with same email do not migrate password
        $dupVendor1 = $this->createVendor([
            'email' => 'duplicate@test.com',
            'password' => 'dup_pass',
        ]);
        $dupVendor2 = $this->createVendor([
            'email' => 'duplicate@test.com',
            'password' => 'dup_pass',
        ]);

        $responseDup = $this->post('/subscribers/loginaction', [
            'email' => 'duplicate@test.com',
            'password' => 'dup_pass',
        ]);
        $responseDup->assertSessionHas('error', 'Wrong Credentials');

        $dupVendor1->refresh();
        $dupVendor2->refresh();
        $this->assertEquals('dup_pass', $dupVendor1->password);
        $this->assertEquals('dup_pass', $dupVendor2->password);
    }

    /**
     * TEST 7: Existing Sanctum tokens remain valid.
     */
    public function test_existing_sanctum_tokens_remain_valid_after_migration()
    {
        $vendor = $this->createVendor([
            'email' => 'token_vendor@test.com',
            'password' => 'initial_plain_password',
        ]);

        // Issue token prior to password migration
        $preMigrationToken = $vendor->createToken('vendor_app_token')->plainTextToken;

        // Verify token works on authenticated route
        $preResponse = $this->withHeader('Authorization', 'Bearer ' . $preMigrationToken)
            ->getJson('/api/vendor/me');
        $preResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'token_vendor@test.com',
                    ]
                ]
            ]);

        // Vendor logs in, upgrading plaintext to Bcrypt
        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'token_vendor@test.com',
            'password' => 'initial_plain_password',
            'device_token' => 'tok_abc',
        ]);
        $loginResponse->assertStatus(200);

        $vendor->refresh();
        $this->assertTrue(Hash::check('initial_plain_password', $vendor->password));

        // The pre-migration token MUST still be completely valid
        $postResponse = $this->withHeader('Authorization', 'Bearer ' . $preMigrationToken)
            ->getJson('/api/vendor/me');
        $postResponse->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'token_vendor@test.com',
                    ]
                ]
            ]);
    }

    /**
     * TEST 8: Subscription and booking data remain unchanged.
     */
    public function test_subscription_and_booking_data_remain_unchanged()
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

        // Pre-existing booking
        $booking = Booking::create([
            'booking_id' => 'BK-IMMUTABLE-999',
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => '600001',
            'status' => 2,
            'category' => 1,
        ]);

        // Snapshot original data
        $origSubscriberId = $vendor->subscriberId;
        $origEmail = $vendor->email;
        $origMobile = $vendor->mobile;
        $origSubscriptionDate = $vendor->subscriptionDate;
        $origExpiryDate = $vendor->expiryDate;
        $origSubscriptionPrice = $vendor->subscription_price;
        $origStatus = $vendor->status;
        $origBlockedstatus = $vendor->blockedstatus;

        // Perform login and password migration
        $this->postJson('/api/vendor/login', [
            'login' => 'immutable@test.com',
            'password' => 'plain_immutable_pass',
            'device_token' => 'new_device_tok',
        ])->assertStatus(200);

        $vendor->refresh();
        $booking->refresh();

        // Password upgraded
        $this->assertTrue(Hash::check('plain_immutable_pass', $vendor->password));

        // Assert all sensitive and business fields are UNCHANGED
        $this->assertEquals($origSubscriberId, $vendor->subscriberId);
        $this->assertEquals($origEmail, $vendor->email);
        $this->assertEquals($origMobile, $vendor->mobile);
        $this->assertEquals($origSubscriptionDate, $vendor->subscriptionDate);
        $this->assertEquals($origExpiryDate, $vendor->expiryDate);
        $this->assertEquals($origSubscriptionPrice, $vendor->subscription_price);
        $this->assertEquals($origStatus, $vendor->status);
        $this->assertEquals($origBlockedstatus, $vendor->blockedstatus);

        // Assert booking is UNCHANGED
        $this->assertEquals('BK-IMMUTABLE-999', $booking->booking_id);
        $this->assertEquals($vendor->id, $booking->assigned_subscriber_id);
        $this->assertEquals('600001', $booking->pincode);
        $this->assertEquals(2, $booking->status);
        $this->assertEquals(1, $booking->category);
    }

    /**
     * TEST 9: Employee login with overlapping email succeeds and modifies neither record.
     */
    public function test_employee_login_with_overlapping_email_succeeds_without_modifications()
    {
        $subscriber = $this->createVendor([
            'email' => 'shared@test.com',
            'password' => 'subscriber_secret_123',
            'status' => 1,
            'blockedstatus' => '1',
            'expiryDate' => now()->addYear()->format('Y-m-d H:i:s'),
        ]);

        $employee = Employee::create([
            'name' => 'Shared Employee',
            'email' => 'shared@test.com',
            'emp_id' => 'EMP-SHARED-001',
            'official_mail' => 'shared@test.com',
            'mobile' => '9123456780',
            'official_mobile' => '9123456780',
            'password' => 'employee_plain_pass_456',
            'subscriber_id' => $subscriber->id,
        ]);

        // Attempt login using Employee's password
        $response = $this->post('/subscribers/loginaction', [
            'email' => 'shared@test.com',
            'password' => 'employee_plain_pass_456',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));

        // Confirm authenticated as employee
        $this->assertTrue(auth()->guard('employee')->check());
        $this->assertEquals($employee->id, auth()->guard('employee')->id());

        // Confirm employee record is untouched
        $employee->refresh();
        $this->assertEquals('employee_plain_pass_456', $employee->password);
        $this->assertEquals('EMP-SHARED-001', $employee->emp_id);

        // Confirm subscriber record is untouched
        $subscriber->refresh();
        $this->assertEquals('subscriber_secret_123', $subscriber->password);
    }
}
