<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;

class SubscriberActivationPopupTest extends TestCase
{
    /**
     * Test valid future subscription expiry date is NOT past (not expired).
     */
    public function test_valid_future_expiry_date_is_not_expired()
    {
        $expiryDate = '31-10-2026';
        $parsed = Carbon::parse($expiryDate)->endOfDay();
        $this->assertFalse($parsed->isPast());
    }

    /**
     * Test past subscription expiry date IS past (expired).
     */
    public function test_past_expiry_date_is_expired()
    {
        $expiryDate = '01-01-2020';
        $parsed = Carbon::parse($expiryDate)->endOfDay();
        $this->assertTrue($parsed->isPast());
    }
}
