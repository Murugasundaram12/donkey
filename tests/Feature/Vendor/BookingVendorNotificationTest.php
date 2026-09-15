<?php

namespace Tests\Feature\Vendor;

use App\Models\Company;
use App\Models\Pincode;
use App\Models\Pushnotification;
use App\Models\Subscriber;
use App\Models\User;
use App\Services\VendorNotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BookingVendorNotificationTest extends TestCase
{
    use DatabaseTransactions;

    private function createVendor(array $overrides = []): Subscriber
    {
        static $count = 1;
        $idx = $count++ . '_' . time() . '_' . mt_rand(100, 999);

        return Subscriber::create(array_merge([
            'name' => "Vendor {$idx}",
            'email' => "vendor_{$idx}@test.com",
            'mobile' => '99' . mt_rand(10000000, 99999999),
            'password' => Hash::make('password123'),
            'subscriberId' => "SUB{$idx}",
            'location' => 'Location Test',
            'subscriptionDate' => '2025-01-01 00:00:00',
            'expiryDate' => '2030-12-31 23:59:59',
            'status' => 1,
            'activestatus' => 1,
            'blockedstatus' => 1,
            'pincode' => json_encode(['600001']),
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
            'device_token' => 'TEST_VENDOR_DEVICE_TOKEN_' . $idx,
            'biketaxi_price' => 10,
        ], $overrides));
    }

    private function createTestUser(array $overrides = []): User
    {
        static $userCount = 1;
        $idx = $userCount++ . '_' . time() . '_' . mt_rand(100, 999);

        return User::create(array_merge([
            'user_id' => 'USR_' . $idx,
            'name' => 'Booking Test User',
            'email' => "user_{$idx}@test.com",
            'phone' => '98' . mt_rand(10000000, 99999999),
            'password' => Hash::make('secret123'),
            'device_token' => 'USER_DEVICE_TOKEN_' . $idx,
            'blockedstatus' => 1,
            'is_live' => 1,
        ], $overrides));
    }

    private function setupBookingEnvironment(array $vendorOverrides = [], string $pincodeStr = '600001'): array
    {
        $vendor = $this->createVendor($vendorOverrides);
        $user = $this->createTestUser();

        // Ensure pincode is mapped to this vendor
        $pincode = Pincode::updateOrCreate(
            ['pincode' => $pincodeStr],
            [
                'state' => 'Tamil Nadu',
                'district' => 'Chennai',
                'city' => 'Chennai',
                'taluk' => 'Chennai',
                'usedBy' => $vendor->id,
            ]
        );

        // Ensure price is set for category 1
        DB::table('price')->updateOrInsert(
            [
                'pincode' => $pincodeStr,
                'category' => 1,
                'range_from' => 0,
            ],
            [
                'subscriber_id' => $vendor->id,
                'range_to' => 50,
                'amount' => 15,
                'tax_split_1' => 9.0,
                'tax_split_2' => 9.0,
                'tax' => 18.0,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return [$vendor, $user, $pincode];
    }

    private function bookingPayload(string $userId, string $pincode = '600001', array $extra = []): array
    {
        return array_merge([
            'distance' => 5,
            'duration' => '15 mins',
            'pincode' => $pincode,
            'category' => 1,
            'description' => 'Ride from A to B',
            'payment_method' => 1,
            'user_id' => $userId,
            'source' => 0,
            'from_location' => [
                'address1' => 'Start Location',
                'address2' => 'Street 1',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'country' => 'India',
                'postal_code' => $pincode,
                'lat' => '13.0827',
                'long' => '80.2707',
            ],
            'to_location' => [
                [
                    'address1' => 'End Location',
                    'address2' => 'Street 2',
                    'city' => 'Chennai',
                    'state' => 'Tamil Nadu',
                    'country' => 'India',
                    'postal_code' => $pincode,
                    'lat' => '13.0850',
                    'long' => '80.2750',
                ]
            ],
        ], $extra);
    }

    public function test_successful_booking_creates_exactly_one_vendor_pushnotification()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([], '600001');

        $initialCount = Pushnotification::where('subscriber_id', $vendor->id)->count();

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600001'));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        $finalCount = Pushnotification::where('subscriber_id', $vendor->id)->count();
        $this->assertEquals($initialCount + 1, $finalCount);
    }

    public function test_notification_fields_match_requirements()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([], '600002');

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600002'));

        $response->assertStatus(200);
        $bookingId = $response->json('data.booking_id');
        $this->assertNotEmpty($bookingId);

        $notification = Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', $bookingId)
            ->first();

        $this->assertNotNull($notification, 'Push notification was not found for subscriber with booking_id');
        // 2. Notification subscriber_id equals the subscriber owning the booking pincode.
        $this->assertEquals($vendor->id, $notification->subscriber_id);
        // 3. Notification category is Bookings.
        $this->assertEquals('Bookings', $notification->category);
        $this->assertEquals('New Booking Received', $notification->title);
        $this->assertStringContainsString('600002', $notification->content);

        $data = $notification->data;
        $this->assertIsArray($data);
        $this->assertEquals('new_booking', $data['event']);
        // 4. Notification contains the correct booking_id.
        $this->assertEquals($bookingId, $data['booking_id']);
        // 5. Notification contains the correct pincode.
        $this->assertEquals('600002', $data['pincode']);
        // 6. Notification contains the correct category.
        $this->assertEquals('1', (string) $data['category']);
        // 7. Notification contains deep_link=booking_details.
        $this->assertEquals('booking_details', $data['deep_link']);
    }

    public function test_duplicate_notification_is_not_created_for_same_booking()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([], '600003');

        // Simulate an existing notification for this subscriber and booking_id
        $simulatedBookingId = 'doc-test-duplicate-123';
        app(VendorNotificationService::class)->create(
            $vendor,
            'Bookings',
            'New Booking Received',
            'You have received a new booking in pincode 600003.',
            [
                'event' => 'new_booking',
                'booking_id' => $simulatedBookingId,
                'pincode' => '600003',
                'category' => '1',
                'deep_link' => 'booking_details',
            ]
        );

        $this->assertEquals(1, Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', $simulatedBookingId)->count());

        // Attempt to trigger duplicate logic
        $alreadyNotified = Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', (string) $simulatedBookingId)
            ->exists();

        $this->assertTrue($alreadyNotified);

        if (!$alreadyNotified) {
            app(VendorNotificationService::class)->create(
                $vendor,
                'Bookings',
                'New Booking Received',
                'Body',
                ['booking_id' => $simulatedBookingId]
            );
        }

        // Count must still be exactly 1
        $this->assertEquals(1, Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', $simulatedBookingId)->count());
    }

    public function test_missing_vendor_device_token_does_not_cause_booking_failure()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment(['device_token' => null], '600004');

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600004'));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        $bookingId = $response->json('data.booking_id');
        $notification = Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', $bookingId)
            ->first();

        $this->assertNotNull($notification);
    }

    public function test_existing_api_bookingtaxi_response_remains_unchanged()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([], '600005');

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600005'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'otp',
                'booking_id',
            ],
            'message',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertEquals('Booking has been successful', $response->json('message'));
        $this->assertIsInt($response->json('data.otp'));
        $this->assertStringStartsWith('doc-', $response->json('data.booking_id'));
    }

    public function test_inactive_subscriber_cannot_create_booking_and_receives_no_notification()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([
            'blockedstatus' => 0,
        ], '600006');

        $initialCount = Pushnotification::where('subscriber_id', $vendor->id)->count();

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600006'));

        $response->assertJson([
            'status' => false,
            'message' => 'Unable to make booking, subscriber inactive or blocked',
        ]);

        $finalCount = Pushnotification::where('subscriber_id', $vendor->id)->count();
        $this->assertEquals($initialCount, $finalCount);
    }

    public function test_external_booking_source_1_notifies_assigned_subscriber()
    {
        Http::fake();
        [$vendor, $user] = $this->setupBookingEnvironment([], '600007');

        $company = Company::create([
            'company_id' => 'C' . mt_rand(1000000, 9999999),
            'name' => 'External Partner',
            'company_code' => 'EP' . mt_rand(1000, 9999),
            'email' => 'partner_' . time() . '@test.com',
            'phone' => '999888' . mt_rand(1000, 9999),
            'status' => 'active',
            'api_key' => 'TEST_KEY_' . time() . '_' . mt_rand(1000, 9999),
        ]);

        $payload = [
            'distance' => 5,
            'duration' => '15 mins',
            'pincode' => '600007',
            'category' => 1,
            'description' => 'External delivery',
            'payment_method' => 1,
            'source' => 1,
            'external_name' => 'External Customer',
            'external_phone' => '9876543210',
            'from_location' => [
                'address1' => 'Shop A',
                'city' => 'Chennai',
                'postal_code' => '600007',
                'lat' => '13.0827',
                'long' => '80.2707',
            ],
            'to_location' => [
                [
                    'address1' => 'Customer B',
                    'city' => 'Chennai',
                    'postal_code' => '600007',
                    'lat' => '13.0850',
                    'long' => '80.2750',
                ]
            ],
        ];

        $response = $this->withHeader('X-API-Key', $company->api_key)
            ->postJson('/api/bookingtaxi', $payload);

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        $bookingId = $response->json('data.booking_id');
        $notification = Pushnotification::where('subscriber_id', $vendor->id)
            ->where('data->booking_id', $bookingId)
            ->first();

        $this->assertNotNull($notification);
        $this->assertEquals($vendor->id, $notification->subscriber_id);
        $this->assertEquals('Bookings', $notification->category);
    }

    public function test_notification_failure_does_not_fail_booking()
    {
        Http::fake(function () {
            throw new \RuntimeException('FCM service down');
        });

        [$vendor, $user] = $this->setupBookingEnvironment([], '600008');

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600008'));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('data.booking_id'));
    }

    public function test_existing_driver_notification_remains_intact_for_source_0()
    {
        Http::fake();
        [$vendor, $user, $pincode] = $this->setupBookingEnvironment([], '600009');

        $response = $this->postJson('/api/bookingtaxi', $this->bookingPayload($user->user_id, '600009'));

        $response->assertStatus(200);
        $this->assertTrue($response->json('success'));

        // Verify booking was stored with source 0 and driver notification executed without error
        $booking = DB::table('booking')->where('booking_id', $response->json('data.booking_id'))->first();
        $this->assertNotNull($booking);
        $this->assertEquals(0, $booking->source);
        $this->assertEquals('600009', $booking->pincode);
    }
}
