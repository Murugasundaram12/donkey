<?php

namespace Tests\Feature\Vendor;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorGuestPasswordResetTest extends TestCase
{
    use DatabaseTransactions;

    private array $defaultVendorData = [
        'location' => 'Chennai',
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

    private function createVendor(array $overrides = []): Subscriber
    {
        return Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Guest Reset Vendor',
            'email' => 'reset_' . uniqid() . '@example.com',
            'mobile' => '98' . rand(10000000, 99999999),
            'password' => Hash::make('OldPassword@123'),
            'subscriberId' => 'SUB-GUEST-' . uniqid(),
            'device_token' => 'tok_' . uniqid(),
        ], $overrides));
    }

    public function test_guest_forgot_password_success(): void
    {
        $vendor = $this->createVendor(['mobile' => '9876543210']);

        $response = $this->postJson('/api/vendor/forgot-password', [
            'mobile' => '9876543210',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Vendor account verified successfully.',
                'data' => [
                    'mobile' => '9876543210',
                ],
            ]);
    }

    public function test_guest_forgot_password_not_found(): void
    {
        $response = $this->postJson('/api/vendor/forgot-password', [
            'mobile' => '9000000000',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Vendor account not found with this mobile number.',
            ]);
    }

    public function test_guest_forgot_password_validation_error(): void
    {
        $response = $this->postJson('/api/vendor/forgot-password', []);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_guest_change_password_success(): void
    {
        $vendor = $this->createVendor(['mobile' => '9876543210']);

        $response = $this->postJson('/api/vendor/change-password', [
            'mobile' => '9876543210',
            'new_password' => 'NewSecurePassword@123',
            'confirm_password' => 'NewSecurePassword@123',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Password updated successfully',
            ]);

        $vendor->refresh();
        $this->assertTrue(Hash::check('NewSecurePassword@123', $vendor->password));
    }

    public function test_guest_change_password_mismatched_confirmation(): void
    {
        $vendor = $this->createVendor(['mobile' => '9876543210']);

        $response = $this->postJson('/api/vendor/change-password', [
            'mobile' => '9876543210',
            'new_password' => 'NewSecurePassword@123',
            'confirm_password' => 'DifferentPassword@123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_guest_change_password_short_password(): void
    {
        $vendor = $this->createVendor(['mobile' => '9876543210']);

        $response = $this->postJson('/api/vendor/change-password', [
            'mobile' => '9876543210',
            'new_password' => '12345',
            'confirm_password' => '12345',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_guest_change_password_vendor_not_found(): void
    {
        $response = $this->postJson('/api/vendor/change-password', [
            'mobile' => '9000000000',
            'new_password' => 'NewSecurePassword@123',
            'confirm_password' => 'NewSecurePassword@123',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Vendor account not found with this mobile number.',
            ]);
    }
}
