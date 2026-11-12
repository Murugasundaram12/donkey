<?php

namespace Tests\Feature\Vendor;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorEarningsReportTest extends TestCase
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
            'name' => 'Earnings Vendor',
            'email' => 'earnings_' . uniqid() . '@example.com',
            'mobile' => '98' . rand(10000000, 99999999),
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-EARN-' . uniqid(),
            'device_token' => 'tok_' . uniqid(),
        ], $attributes));
    }

    /**
     * Unconfigured commission: commission_note must return "0" string and unavailable.
     */
    public function test_unconfigured_commission_returns_zero_string_note_and_unavailable(): void
    {
        config([
            'services.vendor.commission_rule' => null,
            'services.vendor.commission_note' => null,
            'services.commission.rule' => null,
            'services.commission.note' => null,
            'donkey.commission_rule' => null,
            'donkey.commission_note' => null,
        ]);

        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Earnings and reports retrieved successfully',
            ]);

        $summary = $response->json('data.summary');

        $this->assertSame('0', $summary['commission_note']);
        $this->assertNull($summary['commission']);
        $this->assertNull($summary['net_earnings']);
        $this->assertFalse($summary['commission_available']);
        $this->assertNotEquals(
            'No per-booking commission rule is configured; net earnings cannot be calculated from existing business rules.',
            $summary['commission_note']
        );
    }

    /**
     * Configured commission rule and meaningful note: returns the configured note.
     */
    public function test_configured_commission_rule_and_note_returns_configured_note(): void
    {
        config([
            'services.vendor.commission_rule' => 'standard_percentage',
            'services.vendor.commission_note' => 'Standard 10% platform commission deducted per booking.',
        ]);

        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports');

        $response->assertOk();

        $summary = $response->json('data.summary');

        $this->assertTrue($summary['commission_available']);
        $this->assertSame('Standard 10% platform commission deducted per booking.', $summary['commission_note']);
    }

    /**
     * Configured string commission rule without separate note: uses rule description as note.
     */
    public function test_configured_string_rule_without_separate_note_uses_rule_as_note(): void
    {
        config([
            'services.vendor.commission_rule' => 'Flat ₹15 platform fee applied',
            'services.vendor.commission_note' => null,
        ]);

        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports');

        $response->assertOk();

        $summary = $response->json('data.summary');

        $this->assertTrue($summary['commission_available']);
        $this->assertSame('Flat ₹15 platform fee applied', $summary['commission_note']);
    }

    /**
     * Note in config without configured rule: returns "0" string (rule required).
     */
    public function test_note_without_configured_rule_returns_zero_string(): void
    {
        config([
            'services.vendor.commission_rule' => null,
            'services.vendor.commission_note' => 'Dangling note with no rule',
            'donkey.commission_rule' => null,
        ]);

        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports');

        $response->assertOk();

        $summary = $response->json('data.summary');

        $this->assertFalse($summary['commission_available']);
        $this->assertSame('0', $summary['commission_note']);
    }

    /**
     * Preserves existing response structure and metrics.
     */
    public function test_earnings_report_preserves_all_summary_and_report_fields(): void
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports');

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'summary' => [
                        'total_earnings',
                        'today_earnings',
                        'week_earnings',
                        'month_earnings',
                        'selected_range_earnings',
                        'total_bookings',
                        'completed_bookings',
                        'in_progress_bookings',
                        'cancelled_bookings',
                        'selected_total_bookings',
                        'selected_completed_bookings',
                        'selected_incomplete_bookings',
                        'selected_cancelled_bookings',
                        'avg_order_value',
                        'commission',
                        'net_earnings',
                        'commission_available',
                        'commission_note',
                    ],
                    'earnings_chart',
                    'top_services',
                    'filters_applied' => [
                        'period',
                        'start_date',
                        'end_date',
                        'status',
                        'category_id',
                        'rider_id',
                    ],
                    'reports' => [
                        'current_page',
                        'per_page',
                        'total',
                        'last_page',
                        'bookings',
                    ],
                ],
            ]);
    }

    /**
     * Custom period filter applies date range and updates summary metrics accordingly.
     */
    public function test_custom_period_filter_applies_date_range(): void
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports?period=custom&start_date=2026-10-01&end_date=2026-11-03');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'filters_applied' => [
                        'period' => 'custom',
                    ],
                ],
            ]);
    }

    /**
     * Dynamic percentages: Calculates comparisons between current and previous periods safely.
     */
    public function test_dynamic_percentage_calculations_for_bookings_and_earnings(): void
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $startDate = '2026-11-05';
        $endDate = '2026-11-07'; // 3 days: 5, 6, 7. Previous period: Nov 2, 3, 4.

        // Seed 1 completed booking in previous period (Nov 3) with amount 100
        $prevBooking = \App\Models\Booking::create([
            'booking_id' => 'PREV-' . uniqid(),
            'assigned_subscriber_id' => $vendor->id,
            'status' => 2,
            'category' => 1,
            'created_at' => '2026-11-03 10:00:00',
        ]);
        \Illuminate\Support\Facades\DB::table('booking_payment')->insert([
            'booking_id' => $prevBooking->booking_id,
            'total' => 100.0,
            'type' => 'Cash',
            'created_at' => '2026-11-03 10:00:00',
        ]);

        // Seed 2 completed bookings in current period (Nov 6) with total amount 300
        for ($i = 0; $i < 2; $i++) {
            $currBooking = \App\Models\Booking::create([
                'booking_id' => 'CURR-' . uniqid(),
                'assigned_subscriber_id' => $vendor->id,
                'status' => 2,
                'category' => 1,
                'created_at' => '2026-11-06 10:00:00',
            ]);
            \Illuminate\Support\Facades\DB::table('booking_payment')->insert([
                'booking_id' => $currBooking->booking_id,
                'total' => 150.0,
                'type' => 'Cash',
                'created_at' => '2026-11-06 10:00:00',
            ]);
        }

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/vendor/earnings-reports?period=custom&start_date={$startDate}&end_date={$endDate}");

        $response->assertOk();
        $summary = $response->json('data.summary');
        $comparison = $response->json('data.comparison');

        // Total Bookings: current 2 vs prev 1 -> +100% change, trend 'up'
        $this->assertEquals(2, $summary['total_bookings']);
        $this->assertEquals(100.0, $summary['total_bookings_percentage']);
        $this->assertSame('up', $summary['total_bookings_trend']);

        // Total Earnings: current 300 vs prev 100 -> +200% change, trend 'up'
        $this->assertEquals(300.0, $summary['total_earnings']);
        $this->assertEquals(200.0, $summary['total_earnings_percentage']);
        $this->assertSame('up', $summary['total_earnings_trend']);

        // Verify comparison block is populated
        $this->assertTrue($comparison['available']);
        $this->assertEquals(1, $comparison['previous_period']['total_bookings']);
        $this->assertEquals(100.0, $comparison['previous_period']['total_earnings']);

        // Assert that static hardcoded percentages are NOT present
        $this->assertNotEquals(14.5, $summary['total_bookings_percentage']);
        $this->assertNotEquals(18.2, $summary['total_earnings_percentage']);
    }

    /**
     * Division by zero safety: Handles zero previous period data gracefully without crashing.
     */
    public function test_safe_division_by_zero_when_previous_period_has_zero_data(): void
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        // Custom range with no bookings in previous period
        $startDate = '2026-10-10';
        $endDate = '2026-10-12';

        $currBooking = \App\Models\Booking::create([
            'booking_id' => 'CURR-ZERO-' . uniqid(),
            'assigned_subscriber_id' => $vendor->id,
            'status' => 2,
            'category' => 1,
            'created_at' => '2026-10-11 12:00:00',
        ]);
        \Illuminate\Support\Facades\DB::table('booking_payment')->insert([
            'booking_id' => $currBooking->booking_id,
            'total' => 250.0,
            'type' => 'Cash',
            'created_at' => '2026-10-11 12:00:00',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson("/api/vendor/earnings-reports?period=custom&start_date={$startDate}&end_date={$endDate}");

        $response->assertOk();
        $summary = $response->json('data.summary');

        // When prev is 0 and current is 1: change is 100.0% and trend is 'up'
        $this->assertEquals(100.0, $summary['total_bookings_percentage']);
        $this->assertSame('up', $summary['total_bookings_trend']);

        // Cancelled bookings: current 0 and prev 0: change is 0.0% and trend is 'neutral'
        $this->assertEquals(0.0, $summary['cancelled_bookings_percentage']);
        $this->assertSame('neutral', $summary['cancelled_bookings_trend']);
    }

    /**
     * Uncomparable data (period='all'): returns null percentage and trend without misleading metrics.
     */
    public function test_uncomparable_period_all_returns_null_percentages_and_trends(): void
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/earnings-reports?period=all');

        $response->assertOk();
        $summary = $response->json('data.summary');
        $comparison = $response->json('data.comparison');

        $this->assertFalse($summary['comparison_available']);
        $this->assertNull($summary['total_bookings_percentage']);
        $this->assertNull($summary['total_bookings_trend']);
        $this->assertNull($summary['total_earnings_percentage']);
        $this->assertNull($summary['total_earnings_trend']);
        $this->assertFalse($comparison['available']);
        $this->assertNull($comparison['previous_period']['start_date']);
    }
}

