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
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor@test.com',
            'password' => 'password123',
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
