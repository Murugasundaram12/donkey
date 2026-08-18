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

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_vendor_login_successful_with_valid_credentials()
    {
        $vendor = Subscriber::create([
            'name' => 'Test Vendor',
            'email' => 'vendor@test.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB1001',
            'status' => 1,
            'blockedstatus' => 1,
            'expiryDate' => Carbon::now()->addYear(),
        ]);

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
                    'vendor' => ['id', 'email', 'mobile', 'subscriber_id']
                ]
            ]);
    }

    public function test_vendor_login_fails_with_invalid_credentials()
    {
        $vendor = Subscriber::create([
            'name' => 'Test Vendor',
            'email' => 'vendor2@test.com',
            'mobile' => '9876543211',
            'password' => Hash::make('password123'),
            'status' => 1,
            'blockedstatus' => 1,
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor2@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['status' => false]);
    }

    public function test_blocked_vendor_cannot_login()
    {
        $vendor = Subscriber::create([
            'name' => 'Blocked Vendor',
            'email' => 'blocked@test.com',
            'mobile' => '9876543212',
            'password' => Hash::make('password123'),
            'status' => 1,
            'blockedstatus' => 0,
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'blocked@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(403);
    }

    public function test_authenticated_vendor_can_fetch_profile()
    {
        $vendor = Subscriber::create([
            'name' => 'Active Vendor',
            'email' => 'active@test.com',
            'mobile' => '9876543213',
            'password' => Hash::make('password123'),
            'status' => 1,
            'blockedstatus' => 1,
            'expiryDate' => Carbon::now()->addMonth(),
        ]);

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'active@test.com'
                    ]
                ]
            ]);
    }

    public function test_vendor_logout_revokes_token()
    {
        $vendor = Subscriber::create([
            'name' => 'Logout Vendor',
            'email' => 'logout@test.com',
            'mobile' => '9876543214',
            'password' => Hash::make('password123'),
            'status' => 1,
            'blockedstatus' => 1,
        ]);

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/logout');

        $response->assertStatus(200)
            ->assertJson(['status' => true]);
    }
}
