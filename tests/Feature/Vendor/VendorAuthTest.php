<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class VendorAuthTest extends TestCase
{
    use DatabaseTransactions;

    private array $defaultVendorData = [
        'location' => 'Location Test',
        'subscriptionDate' => '2025-01-01 00:00:00',
        'expiryDate' => '2030-12-31 23:59:59',
        'status' => 1,
        'blockedstatus' => 1,
        'pincode' => '["600001"]',
        'aadharNo' => '123456789012',
        'aadharImage' => 'front.jpg',
        'pancardImage' => 'pan.jpg',
        'customerdocument' => 'doc.pdf',
        'account_type' => 'Individual',
        'image' => 'profile.jpg',
        'created_by' => '1',
        'bankstatement' => 'stmt.jpg',
        'aadharBackImage' => 'back.jpg',
        'video' => '',
        'gst' => '',
        'qr' => '',
    ];

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_vendor_login_successful_with_valid_credentials()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor',
            'email' => 'vendor@test.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB1001',
            'device_token' => 'OLD_TOKEN_ABC',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor@test.com',
            'password' => 'password123',
            'device_token' => 'OLD_TOKEN_ABC',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
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
    }

    public function test_vendor_login_updates_different_device_token_without_mismatch_error()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Token Vendor',
            'email' => 'tokenvendor@test.com',
            'mobile' => '9876543219',
            'password' => Hash::make('password123'),
            'device_token' => 'OLD_TOKEN_ABC',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'tokenvendor@test.com',
            'password' => 'password123',
            'device_token' => 'NEW_TOKEN_XYZ',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);

        $vendor->refresh();
        $this->assertEquals('NEW_TOKEN_XYZ', $vendor->device_token);
    }

    public function test_vendor_login_fails_without_device_token()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'No Token Vendor',
            'email' => 'notoken@test.com',
            'mobile' => '9876543218',
            'password' => Hash::make('password123'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'notoken@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_vendor_login_fails_with_invalid_credentials()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor',
            'email' => 'vendor2@test.com',
            'mobile' => '9876543211',
            'password' => Hash::make('password123'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor2@test.com',
            'password' => 'wrongpassword',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_blocked_vendor_cannot_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Blocked Vendor',
            'email' => 'blocked@test.com',
            'mobile' => '9876543212',
            'password' => Hash::make('password123'),
            'blockedstatus' => 0,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'blocked@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact admin.',
            ]);
    }

    public function test_authenticated_vendor_can_fetch_profile()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Active Vendor',
            'email' => 'active@test.com',
            'mobile' => '9876543213',
            'password' => Hash::make('password123'),
        ]));

        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'active@test.com',
                    ]
                ]
            ]);
    }

    public function test_expired_vendor_cannot_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor',
            'email' => 'expired@test.com',
            'mobile' => '9876543299',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'expired@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your subscription has expired. Please renew your subscription to continue.',
                'data' => [
                    'payment_status' => 0,
                    'payment_expiry' => '2020-01-01',
                ]
            ]);
    }

    public function test_vendor_expiring_today_can_login()
    {
        $todayStr = Carbon::now()->format('Y-m-d 23:59:59');
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Today Vendor',
            'email' => 'today@test.com',
            'mobile' => '9876543298',
            'password' => Hash::make('password123'),
            'expiryDate' => $todayStr,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'today@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_without_expiry_date_can_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'No Expiry Vendor',
            'email' => 'noexpiry@test.com',
            'mobile' => '9876543297',
            'password' => Hash::make('password123'),
            'expiryDate' => null,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'noexpiry@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_logout_revokes_token()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Logout Vendor',
            'email' => 'logout@test.com',
            'mobile' => '9876543214',
            'password' => Hash::make('password123'),
        ]));

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/logout');

        $response->assertStatus(200)
            ->assertJson(['status' => true]);
    }
}
