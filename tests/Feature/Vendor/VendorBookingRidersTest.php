<?php

namespace Tests\Feature\Vendor;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorBookingRidersTest extends TestCase
{
    use DatabaseTransactions;

    private function vendor(string $suffix): Subscriber
    {
        return Subscriber::create([
            'name' => 'Booking Rider Vendor ' . $suffix,
            'email' => 'booking-rider-vendor-' . $suffix . '@example.com',
            'mobile' => '900000' . str_pad($suffix, 4, '0', STR_PAD_LEFT),
            'password' => Hash::make('password123'),
            'subscriberId' => 'BRV' . $suffix,
            'location' => 'Test Location',
            'subscriptionDate' => '2025-01-01',
            'expiryDate' => '2030-12-31',
            'status' => 1,
            'blockedstatus' => 1,
            'pincode' => '[]',
            'aadharNo' => '123456789012',
            'aadharImage' => 'front.jpg',
            'pancardImage' => 'pan.jpg',
            'bankstatement' => 'statement.jpg',
            'customerdocument' => 'doc.pdf',
            'account_type' => 'Individual',
            'image' => 'profile.jpg',
            'created_by' => '1',
        ]);
    }

    private function rider(Subscriber $vendor, int $pincodeId, string $name, string $phone, int $status = 1): Driver
    {
        $user = User::create([
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
            'phone' => $phone,
            'password' => Hash::make('password123'),
            'user_id' => 'BRU-' . uniqid(),
            'is_driver' => 1,
            'is_live' => 0,
        ]);

        return Driver::create([
            'subscriberId' => $vendor->id,
            'userid' => $user->id,
            'name' => $name,
            'location' => 'Test Location',
            'mobile' => $phone,
            'email' => $user->email,
            'password' => Hash::make('password123'),
            'pincode' => json_encode([$pincodeId]),
            'language' => 'English',
            'aadharNo' => '111122223333',
            'aadharFrontImage' => 'front.jpg',
            'aadharBackImage' => 'back.jpg',
            'drivingLicence' => 'dl.jpg',
            'vehicleNo' => 'TN01BR' . $pincodeId,
            'vehicleModelNo' => '2022',
            'rcbook' => 'rc.jpg',
            'insurance' => 'insurance.jpg',
            'licenceexpiry' => '2030-12-31',
            'customerdocument' => 'doc.pdf',
            'bike' => 'bike.jpg',
            'status' => $status,
        ]);
    }

    public function test_booking_details_returns_only_riders_from_booking_pincode(): void
    {
        $vendor = $this->vendor('1');
        $matchingPincode = Pincode::create(['state' => 'Tamil Nadu', 'district' => 'Coimbatore', 'pincode' => '641001', 'usedBy' => $vendor->id]);
        $otherPincode = Pincode::create(['state' => 'Tamil Nadu', 'district' => 'Coimbatore', 'pincode' => '641002', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$matchingPincode->id, $otherPincode->id])]);

        $this->rider($vendor, $matchingPincode->id, 'Matching Rider', '9000000001', 1);
        $this->rider($vendor, $otherPincode->id, 'Other Pincode Rider', '9000000002', 2);

        $booking = Booking::create([
            'booking_id' => 'BR-DETAIL-1',
            'pincode' => '641001',
            'status' => 0,
            'category' => 1,
            'assigned_subscriber_id' => $vendor->id,
        ]);
        $token = $vendor->createToken('booking-riders-test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/vendor/bookings/' . $booking->booking_id);

        $response->assertOk()
            ->assertJsonPath('data.booking.riders', [[
                'rider_name' => 'Matching Rider',
                'rider_phone' => '9000000001',
                'rider_pincode' => '641001',
                'rider_status' => 1,
            ]]);
    }

    public function test_booking_details_returns_empty_riders_for_unmatched_pincode(): void
    {
        $vendor = $this->vendor('2');
        $riderPincode = Pincode::create(['state' => 'Tamil Nadu', 'district' => 'Coimbatore', 'pincode' => '642001', 'usedBy' => $vendor->id]);
        $bookingPincode = Pincode::create(['state' => 'Tamil Nadu', 'district' => 'Coimbatore', 'pincode' => '642002', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$riderPincode->id, $bookingPincode->id])]);
        $this->rider($vendor, $riderPincode->id, 'Unmatched Rider', '9000000011');

        $booking = Booking::create([
            'booking_id' => 'BR-DETAIL-2',
            'pincode' => '642002',
            'status' => 0,
            'category' => 1,
            'assigned_subscriber_id' => $vendor->id,
        ]);
        $token = $vendor->createToken('booking-riders-test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/vendor/bookings/' . $booking->booking_id)
            ->assertOk()
            ->assertJsonPath('data.booking.riders', []);
    }

    public function test_invalid_booking_id_keeps_existing_error_response(): void
    {
        $vendor = $this->vendor('3');
        $token = $vendor->createToken('booking-riders-test')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/vendor/bookings/does-not-exist')
            ->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Booking not found or access denied.',
            ]);
    }
}
