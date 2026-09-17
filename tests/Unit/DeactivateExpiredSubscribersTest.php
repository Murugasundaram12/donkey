<?php

namespace Tests\Unit;

use App\Console\Commands\DeactivateExpiredSubscribers;
use ReflectionMethod;
use Tests\TestCase;

class DeactivateExpiredSubscribersTest extends TestCase
{
    public function test_expiry_deactivation_query_uses_business_date_and_active_fields(): void
    {
        $command = new DeactivateExpiredSubscribers();
        $method = new ReflectionMethod($command, 'expiredActiveSubscribersQuery');
        $method->setAccessible(true);

        $query = $method->invoke($command, '2026-09-17');
        $sql = $query->toSql();

        $this->assertStringContainsString('expiryDate', $sql);
        $this->assertStringContainsString('status', $sql);
        $this->assertStringContainsString('activestatus', $sql);
        $this->assertContains('2026-09-17', $query->getBindings());
        $this->assertContains(0, $query->getBindings());
    }
}
