<?php

namespace Tests\Unit;

use App\Models\Enduser;
use Tests\TestCase;

class ReferralCountTest extends TestCase
{
    public function test_referral_relationship_counts_end_users_only(): void
    {
        $relation = (new Enduser())->referrals();

        $this->assertStringNotContainsString('is_driver', $relation->toSql());

        $query = Enduser::query()->withCount([
            'referrals' => function ($query) {
                $query->where('is_driver', 0);
            },
        ]);

        $this->assertStringContainsString('is_driver', $query->toSql());
        $this->assertContains(0, $query->getBindings());
    }
}
