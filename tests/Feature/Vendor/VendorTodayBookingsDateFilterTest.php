<?php

namespace Tests\Feature\Vendor;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VendorTodayBookingsDateFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function getVendorAndToken(int $vendorId = 61): array
    {
        $vendor = Subscriber::find($vendorId);
        $this->assertNotNull($vendor, "Subscriber {$vendorId} must exist");
        $token = $vendor->createToken('test_token')->plainTextToken;

        return [$vendor, $token];
    }

    /**
     * Test GET /api/vendor/bookings/today?date=2026-11-18&status=2
     */
    public function test_today_bookings_with_date_and_numeric_completed_status(): void
    {
        [, $token] = $this->getVendorAndToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings/today?date=2026-11-18&status=2');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Today\'s bookings retrieved successfully',
            ]);

        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds, 'Booking doc-7254307167 must be returned for 2026-11-18 status=2');
        $this->assertGreaterThanOrEqual(1, $response->json('data.total'));
    }

    /**
     * Test GET /api/vendor/bookings/today?date=2026-11-18&status=completed
     */
    public function test_today_bookings_with_date_and_named_completed_status(): void
    {
        [, $token] = $this->getVendorAndToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings/today?date=2026-11-18&status=completed');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Today\'s bookings retrieved successfully',
            ]);

        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds);
    }

    /**
     * Test GET /api/vendor/bookings/today?date=2026-11-18 (all statuses for date)
     */
    public function test_today_bookings_with_date_without_status(): void
    {
        [, $token] = $this->getVendorAndToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings/today?date=2026-11-18');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Today\'s bookings retrieved successfully',
            ]);

        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds);
        $this->assertGreaterThanOrEqual(1, $response->json('data.total'));
    }

    /**
     * Test GET /api/vendor/bookings/today?status=2 (defaults to current date in Asia/Kolkata)
     */
    public function test_today_bookings_defaults_to_today_when_date_absent(): void
    {
        [, $token] = $this->getVendorAndToken();

        // When mocked to 2026-11-18, the booking must be visible under default today
        Carbon::setTestNow(Carbon::parse('2026-11-18 12:00:00', 'Asia/Kolkata'));

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings/today?status=2');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Today\'s bookings retrieved successfully',
            ]);

        $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
        $this->assertContains('doc-7254307167', $bookingIds);
    }

    /**
     * Test pending, ongoing, completed, and cancelled status filters
     */
    public function test_status_filters_pending_ongoing_completed_cancelled(): void
    {
        [$vendor, $token] = $this->getVendorAndToken();

        $customerId = User::first()->user_id;
        $testDate = '2026-12-15';
        $pincode = '603210';

        // Create bookings for each status on testDate
        $statuses = [
            'pending' => 0,
            'in_progress' => 1,
            'completed' => 2,
            'cancelled' => 3,
        ];
        $createdBookings = [];

        foreach ($statuses as $name => $code) {
            $createdBookings[$name] = Booking::create([
                'booking_id' => 'test-status-' . $name . '-' . uniqid(),
                'customer_id' => $customerId,
                'assigned_subscriber_id' => $vendor->id,
                'provider_accepted_by' => $vendor->id,
                'pincode' => $pincode,
                'status' => $code,
                'category' => 1,
                'distance' => 5,
                'created_at' => Carbon::parse("{$testDate} 10:00:00", 'Asia/Kolkata'),
                'updated_at' => Carbon::parse("{$testDate} 10:00:00", 'Asia/Kolkata'),
            ]);
        }

        // Test each status individually
        foreach ($statuses as $name => $code) {
            $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson("/api/vendor/bookings/today?date={$testDate}&status={$name}");

            $response->assertOk();
            $bookingIds = collect($response->json('data.items'))->pluck('booking_id')->all();
            $this->assertContains($createdBookings[$name]->booking_id, $bookingIds, "Status {$name} filter should include booking");

            // Other statuses should not be in this result
            foreach ($statuses as $otherName => $otherCode) {
                if ($otherName !== $name) {
                    $this->assertNotContains($createdBookings[$otherName]->booking_id, $bookingIds, "Status {$name} filter should not include {$otherName} booking");
                }
            }
        }
    }

    /**
     * Test Asia/Kolkata midnight boundaries (startOfDay to endOfDay)
     */
    public function test_asia_kolkata_midnight_boundaries(): void
    {
        [$vendor, $token] = $this->getVendorAndToken();

        $customerId = User::first()->user_id;
        $targetDate = '2026-12-25';
        $pincode = '603210';

        // 1. Exactly at start of day in Asia/Kolkata (00:00:00 IST)
        $startBooking = Booking::create([
            'booking_id' => 'test-boundary-start-' . uniqid(),
            'customer_id' => $customerId,
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => $pincode,
            'status' => 2,
            'category' => 1,
            'distance' => 1,
            'created_at' => Carbon::parse("{$targetDate} 00:00:00", 'Asia/Kolkata'),
            'updated_at' => Carbon::parse("{$targetDate} 00:00:00", 'Asia/Kolkata'),
        ]);

        // 2. Exactly at end of day in Asia/Kolkata (23:59:59 IST)
        $endBooking = Booking::create([
            'booking_id' => 'test-boundary-end-' . uniqid(),
            'customer_id' => $customerId,
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => $pincode,
            'status' => 2,
            'category' => 1,
            'distance' => 1,
            'created_at' => Carbon::parse("{$targetDate} 23:59:59", 'Asia/Kolkata'),
            'updated_at' => Carbon::parse("{$targetDate} 23:59:59", 'Asia/Kolkata'),
        ]);

        // 3. 1 second BEFORE start of day (2026-12-24 23:59:59 IST)
        $beforeBooking = Booking::create([
            'booking_id' => 'test-boundary-before-' . uniqid(),
            'customer_id' => $customerId,
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => $pincode,
            'status' => 2,
            'category' => 1,
            'distance' => 1,
            'created_at' => Carbon::parse("2026-12-24 23:59:59", 'Asia/Kolkata'),
            'updated_at' => Carbon::parse("2026-12-24 23:59:59", 'Asia/Kolkata'),
        ]);

        // 4. 1 second AFTER end of day (2026-12-26 00:00:00 IST)
        $afterBooking = Booking::create([
            'booking_id' => 'test-boundary-after-' . uniqid(),
            'customer_id' => $customerId,
            'assigned_subscriber_id' => $vendor->id,
            'pincode' => $pincode,
            'status' => 2,
            'category' => 1,
            'distance' => 1,
            'created_at' => Carbon::parse("2026-12-26 00:00:00", 'Asia/Kolkata'),
            'updated_at' => Carbon::parse("2026-12-26 00:00:00", 'Asia/Kolkata'),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/vendor/bookings/today?date={$targetDate}&status=2");

        $response->assertOk();
        $ids = collect($response->json('data.items'))->pluck('booking_id')->all();

        $this->assertContains($startBooking->booking_id, $ids, '00:00:00 IST booking must be included');
        $this->assertContains($endBooking->booking_id, $ids, '23:59:59 IST booking must be included');
        $this->assertNotContains($beforeBooking->booking_id, $ids, 'Previous day booking must NOT be included');
        $this->assertNotContains($afterBooking->booking_id, $ids, 'Next day booking must NOT be included');
    }

    /**
     * Test dashboard count consistency with vendor rider bookings
     */
    public function test_dashboard_count_consistency_with_vendor_rider_bookings(): void
    {
        [$vendor, $token] = $this->getVendorAndToken();

        // Salman is subscriber 61's rider (driver_id / userid: 1369)
        // Booking 4523 has accepted = 1369
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/dashboard');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'bookings_summary' => [
                        'total',
                        'today',
                        'pending',
                        'in_progress',
                        'completed',
                        'cancelled',
                        'incomplete',
                    ],
                ],
            ]);

        $completedCount = $response->json('data.bookings_summary.completed');
        $this->assertGreaterThanOrEqual(1, $completedCount, 'Dashboard completed count must include vendor rider bookings');
    }
}
