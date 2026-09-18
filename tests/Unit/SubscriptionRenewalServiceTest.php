<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use App\Services\SubscriptionRenewalService;
use Carbon\Carbon;
use Tests\TestCase;

class SubscriptionRenewalServiceTest extends TestCase
{
    private function vendor(string $expiry = '2026-10-15'): Subscriber
    {
        $vendor = new Subscriber();
        $vendor->subscription_price = '1500';
        $vendor->expiryDate = $expiry;
        $vendor->id = 7;
        return $vendor;
    }

    public function test_due_date_is_not_penalized_and_gets_bonus_days(): void
    {
        $quote = app(SubscriptionRenewalService::class)->quote($this->vendor(), Carbon::create(2026, 10, 15, 23, 59, 0, 'Asia/Kolkata'));

        $this->assertSame(0, $quote['penalty_day']);
        $this->assertSame(2, $quote['bonus_days']);
        $this->assertSame(30, $quote['renewal_days']);
        $this->assertSame(1770.0, $quote['total_payable']);
    }

    /** @dataProvider penaltyDays */
    public function test_penalty_days_are_cumulative(int $day, int $principal, float $gst): void
    {
        $quote = app(SubscriptionRenewalService::class)->quote($this->vendor(), Carbon::create(2026, 10, 15, 12, 0, 0, 'Asia/Kolkata')->addDays($day));

        $this->assertSame($day, $quote['penalty_day']);
        $this->assertSame((float) $principal, $quote['penalty_principal']);
        $this->assertSame($gst, $quote['penalty_gst']);
        $this->assertSame(28, $quote['renewal_days']);
        $this->assertSame(0, $quote['bonus_days']);
    }

    public static function penaltyDays(): array
    {
        return [[1, 15, 2.70], [2, 30, 5.40], [3, 45, 8.10], [4, 60, 10.80], [5, 75, 13.50], [6, 90, 16.20], [7, 105, 18.90]];
    }

    public function test_default_price_and_rounded_payment_amount_are_preserved(): void
    {
        $vendor = $this->vendor();
        $vendor->subscription_price = null;
        $quote = app(SubscriptionRenewalService::class)->quote($vendor, Carbon::create(2026, 10, 15, 12, 0, 0, 'Asia/Kolkata'));

        $this->assertSame(2.0, $quote['subscription_price']);
        $this->assertSame(0.36, $quote['subscription_gst']);
        $this->assertSame(2.36, $quote['exact_total_payable']);
        $this->assertSame(2.0, $quote['total_payable']);
        $this->assertSame(200, $quote['total_payable_in_paise']);
    }
}
