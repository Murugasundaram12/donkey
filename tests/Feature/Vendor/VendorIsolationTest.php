<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Booking;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class VendorIsolationTest extends TestCase
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

    public function test_vendor_a_cannot_access_vendor_b_booking_details()
    {
        $vendorA = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Vendor A',
            'email' => 'vendora@test.com',
            'mobile' => '9999900001',
            'password' => Hash::make('pass123'),
            'pincode' => json_encode(['1']),
        ]));

        $vendorB = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Vendor B',
            'email' => 'vendorb@test.com',
            'mobile' => '9999900002',
            'password' => Hash::make('pass123'),
            'pincode' => json_encode(['2']),
        ]));

        $bookingB = Booking::create([
            'booking_id' => 'BK-VENDOR-B-001',
            'assigned_subscriber_id' => $vendorB->id,
            'pincode' => '600002',
            'status' => 0,
            'category' => 1,
        ]);

        $tokenA = $vendorA->createToken('test')->plainTextToken;

        // Vendor A tries to view Vendor B's booking
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/bookings/' . $bookingB->booking_id);

        $response->assertStatus(404);
    }

    public function test_vendor_a_cannot_access_or_manage_vendor_b_rider()
    {
        $vendorA = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Vendor A',
            'email' => 'vendora2@test.com',
            'mobile' => '9999900003',
            'password' => Hash::make('pass123'),
        ]));

        $vendorB = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Vendor B',
            'email' => 'vendorb2@test.com',
            'mobile' => '9999900004',
            'password' => Hash::make('pass123'),
        ]));

        $riderB = Driver::create([
            'subscriberId' => $vendorB->id,
            'name' => 'Vendor B Rider',
            'mobile' => '8888800001',
            'password' => Hash::make('password123'),
            'language' => 'English',
            'pincode' => '600002',
            'aadharNo' => '123456789012',
            'aadharFrontImage' => 'front.jpg',
            'aadharBackImage' => 'back.jpg',
            'rcbook' => 'rc.jpg',
            'drivingLicence' => 'dl.jpg',
            'vehicleNo' => 'TN01AB1234',
            'vehicleModelNo' => '2022',
            'status' => 1,
        ]);

        $tokenA = $vendorA->createToken('test')->plainTextToken;

        // Vendor A tries to view Vendor B's rider
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/riders/' . $riderB->id);

        $response->assertStatus(404);

        // Vendor A tries to delete Vendor B's rider
        $deleteResponse = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->deleteJson('/api/vendor/riders/' . $riderB->id);

        $deleteResponse->assertStatus(404);
    }
}
