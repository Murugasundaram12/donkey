<?php

namespace Tests\Feature\Vendor;

use App\Models\Enquiry;
use App\Models\Pincode;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorBookingVisibilityAndInquiryApiTest extends TestCase
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

    private function createVendor(array $attributes = []): Subscriber
    {
        return Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor ' . uniqid(),
            'email' => 'vendor_' . uniqid() . '@example.com',
            'mobile' => '98' . rand(10000000, 99999999),
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TEST-' . uniqid(),
            'device_token' => 'tok_' . uniqid(),
        ], $attributes));
    }

    /*
    |--------------------------------------------------------------------------
    | TASK 1: Booking Visibility Tests
    |--------------------------------------------------------------------------
    */

    public function test_vendor_can_see_completed_booking_on_2026_11_18_with_named_status(): void
    {
        $vendor = Subscriber::find(61);
        $this->assertNotNull($vendor, 'Subscriber 61 must exist');

        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings?status=completed&date=2026-11-18');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Bookings retrieved successfully',
            ]);

        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds, 'Booking doc-7254307167 must be visible with status=completed');
        $this->assertEquals(1, $response->json('data.total'));
    }

    public function test_vendor_can_see_completed_booking_with_numeric_status_and_date_range(): void
    {
        $vendor = Subscriber::find(61);
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings?status=2&start_date=2026-11-18&end_date=2026-11-18');

        $response->assertOk();
        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds);
        $this->assertEquals(1, $response->json('data.total'));
    }

    public function test_vendor_does_not_see_completed_booking_under_pending_filter(): void
    {
        $vendor = Subscriber::find(61);
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings?status=pending&date=2026-11-18');

        $response->assertOk();
        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertNotContains('doc-7254307167', $bookingIds);
    }

    public function test_other_vendor_cannot_see_booking_4523(): void
    {
        $otherVendor = $this->createVendor();
        $token = $otherVendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings?status=completed&date=2026-11-18');

        $response->assertOk();
        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertNotContains('doc-7254307167', $bookingIds, 'Other vendors must not see vendor 61 bookings');
    }

    public function test_earnings_report_remains_consistent_with_bookings(): void
    {
        $vendor = Subscriber::find(61);
        $token = $vendor->createToken('test_token')->plainTextToken;

        $earningsResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports?start_date=2026-11-18&end_date=2026-11-18');

        $earningsResponse->assertOk();
        $totalBookings = $earningsResponse->json('data.summary.total_bookings');
        $this->assertGreaterThanOrEqual(1, $totalBookings);

        $bookingsResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings?status=completed&date=2026-11-18');

        $bookingsResponse->assertOk();
        $this->assertNotEmpty($bookingsResponse->json('data.items'));
    }

    /*
    |--------------------------------------------------------------------------
    | TASK 2: Public Website Inquiry API Tests
    |--------------------------------------------------------------------------
    */

    public function test_public_inquiry_submission_success(): void
    {
        $uniqueMobile = '98' . rand(10000000, 99999999);

        $payload = [
            'name' => 'Arun Kumar',
            'email' => 'arun.' . uniqid() . '@example.com',
            'mobile' => $uniqueMobile,
            'area' => 'Tambaram',
            'pincode' => '600045',
            'inquiry_for' => 'Rider',
            'message' => 'Interested in joining as delivery partner',
            'immediate_join' => true,
        ];

        $response = $this->postJson('/api/inquiry', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Inquiry submitted successfully. Our team will contact you shortly.',
                'data' => [
                    'name' => 'Arun Kumar',
                    'mobile' => $uniqueMobile,
                    'area' => 'Tambaram',
                    'pincode' => '600045',
                    'inquiry_for' => 'Rider',
                    'category' => 'Rider',
                    'immediate_join' => true,
                ],
            ]);

        $enquiryId = $response->json('data.id');
        $enquiry = Enquiry::find($enquiryId);
        $this->assertNotNull($enquiry);
        $this->assertSame('web', $enquiry->emp_id);
        $this->assertSame('Rider', $enquiry->category);
        $this->assertSame('1', (string) $enquiry->ficon);
    }

    public function test_public_inquiry_service_provider_category_mapping(): void
    {
        $uniqueMobile = '97' . rand(10000000, 99999999);

        $payload = [
            'name' => 'Senthil Nathan',
            'email' => 'senthil.' . uniqid() . '@example.com',
            'mobile' => $uniqueMobile,
            'area' => 'Chromepet',
            'pincode' => '600044',
            'inquiry_for' => 'Service Provider',
            'message' => 'Want to partner as vendor',
            'immediate_join' => false,
        ];

        $response = $this->postJson('/api/inquiry', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'data' => [
                    'category' => 'Subscriber',
                    'immediate_join' => false,
                ],
            ]);

        $enquiryId = $response->json('data.id');
        $enquiry = Enquiry::find($enquiryId);
        $this->assertSame('Subscriber', $enquiry->category);
        $this->assertSame('0', (string) $enquiry->ficon);
    }

    public function test_public_inquiry_with_priority_no_payload(): void
    {
        $uniqueMobile = '98' . rand(10000000, 99999999);

        $payload = [
            'name' => 'Arun Kumar',
            'email' => 'arun.' . uniqid() . '@example.com',
            'mobile' => $uniqueMobile,
            'area' => 'Urapakkam',
            'pincode' => '603210',
            'inquiry_for' => 'Rider',
            'message' => 'Interested in joining',
            'Priority' => 'no',
        ];

        $response = $this->postJson('/api/inquiry', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => true,
                'message' => 'Inquiry submitted successfully. Our team will contact you shortly.',
                'data' => [
                    'name' => 'Arun Kumar',
                    'mobile' => $uniqueMobile,
                    'area' => 'Urapakkam',
                    'pincode' => '603210',
                    'inquiry_for' => 'Rider',
                    'category' => 'Rider',
                    'message' => 'Interested in joining',
                    'Priority' => false,
                    'immediate_join' => false,
                ],
            ]);

        $enquiryId = $response->json('data.id');
        $enquiry = Enquiry::find($enquiryId);
        $this->assertNotNull($enquiry);
        $this->assertSame('0', (string) $enquiry->ficon);
    }

    public function test_public_inquiry_validation_failures(): void
    {
        $response = $this->postJson('/api/inquiry', [
            'name' => '',
            'email' => 'not-an-email',
            'mobile' => '12345',
            'pincode' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation failed',
            ])
            ->assertJsonValidationErrors(['name', 'email', 'mobile', 'area', 'pincode', 'inquiry_for']);
    }

    public function test_public_inquiry_duplicate_prevention_within_15_minutes(): void
    {
        $mobile = '96' . rand(10000000, 99999999);

        $payload = [
            'name' => 'First Submission',
            'email' => 'first.' . uniqid() . '@example.com',
            'mobile' => $mobile,
            'area' => 'Guindy',
            'pincode' => '600032',
            'inquiry_for' => 'Rider',
        ];

        $firstResponse = $this->postJson('/api/inquiry', $payload);
        $firstResponse->assertStatus(201);

        // Immediate second submission with same mobile
        $secondResponse = $this->postJson('/api/inquiry', [
            'name' => 'Second Submission',
            'email' => 'second.' . uniqid() . '@example.com',
            'mobile' => $mobile,
            'area' => 'Guindy',
            'pincode' => '600032',
            'inquiry_for' => 'Rider',
        ]);

        $secondResponse->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'An inquiry with this mobile number has already been submitted recently. Please wait before submitting again.',
            ])
            ->assertJsonValidationErrors(['mobile']);
    }

    public function test_existing_web_inquiry_store_route_remains_functional(): void
    {
        $pincode = Pincode::first();
        $pincodeVal = $pincode ? $pincode->pincode : '600001';
        $mobile = '95' . rand(10000000, 99999999);

        $response = $this->from('/')
            ->withSession([
                'subscriber_onboarding_otp_verified' => [
                    'mobile' => $mobile,
                    'verified_at' => now()->timestamp,
                ],
            ])
            ->post('/inquirystore', [
                'name' => 'WebTester',
                'mailId' => 'web.' . uniqid() . '@example.com',
                'mobile' => $mobile,
                'area' => 'Anna Nagar',
                'pincode' => $pincodeVal,
                'category' => 'Rider',
                'description' => 'Web inquiry test',
                'ficon' => '1',
            ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('show_success_modal', true);
    }
}
