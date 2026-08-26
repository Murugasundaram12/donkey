<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Subscriber;
use Carbon\Carbon;

class SubscriberActivationApiTest extends TestCase
{
    public function test_subscriber_with_status_0_and_future_expiry_date_is_active()
    {
        $subscriber = (object) [
            'status' => 0,
            'blockedstatus' => 1,
            'expiryDate' => '31-10-2026',
        ];

        $this->assertTrue(Subscriber::isSubscriberActive($subscriber));
    }

    public function test_subscriber_with_status_0_and_past_expiry_date_is_inactive()
    {
        $subscriber = (object) [
            'status' => 0,
            'blockedstatus' => 1,
            'expiryDate' => '01-01-2020',
        ];

        $this->assertFalse(Subscriber::isSubscriberActive($subscriber));
    }

    public function test_subscriber_blocked_is_inactive()
    {
        $subscriber = (object) [
            'status' => 1,
            'blockedstatus' => 0,
            'expiryDate' => '31-10-2026',
        ];

        $this->assertFalse(Subscriber::isSubscriberActive($subscriber));
    }
}
