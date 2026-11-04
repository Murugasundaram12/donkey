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
}

