<?php

namespace Tests\Feature\Vendor;

use App\Models\Pushnotification;
use App\Models\Subscriber;
use App\Services\VendorNotificationService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorNotificationTest extends TestCase
{
    use DatabaseTransactions;

    private function createVendor(array $overrides = []): Subscriber
    {
        static $count = 1;
        $idx = $count++;

        return Subscriber::create(array_merge([
            'name' => "Vendor {$idx}",
            'email' => "vendor_{$idx}_" . time() . "_{$idx}@test.com",
            'mobile' => '999' . str_pad((string)$idx, 7, '0', STR_PAD_LEFT),
            'password' => Hash::make('password123'),
            'subscriberId' => "SUB{$idx}",
            'location' => 'Location Test',
            'subscriptionDate' => '2025-01-01 00:00:00',
            'expiryDate' => '2030-12-31 23:59:59',
            'status' => 1,
            'blockedstatus' => 1,
            'pincode' => json_encode([(string)$idx]),
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
        ], $overrides));
    }

    public function test_login_saves_device_token()
    {
        $vendor = $this->createVendor();

        $response = $this->postJson('/api/vendor/login', [
            'login' => $vendor->email,
            'password' => 'password123',
            'device_token' => 'LOGIN_TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)->assertJson(['status' => true]);

        $vendor->refresh();
        $this->assertEquals('LOGIN_TEST_TOKEN_123', $vendor->device_token);
    }

    public function test_device_token_update_api()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/device-token', [
                'device_token' => 'UPDATED_TEST_TOKEN_456',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Device token updated successfully',
            ]);

        $vendor->refresh();
        $this->assertEquals('UPDATED_TEST_TOKEN_456', $vendor->device_token);
    }

    public function test_vendor_notifications_index_and_category_filtering()
    {
        $vendor = $this->createVendor();
        $service = app(VendorNotificationService::class);

        $notifBookings = $service->create($vendor, 'Bookings', 'New Booking', 'You received a booking', ['booking_id' => '123', 'deep_link' => 'booking_details']);
        $notifRiders = $service->create($vendor, 'Riders', 'Rider Approved', 'Rider active', ['rider_id' => 5]);
        $notifPayments = $service->create($vendor, 'Payments', 'Payment Due', 'Renew subscription', ['days_remaining' => 15]);

        $token = $vendor->createToken('test')->plainTextToken;

        // Fetch all notifications
        $responseAll = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/notifications?category=All');

        $responseAll->assertStatus(200)
            ->assertJson(['status' => true])
            ->assertJsonPath('data.total', 3);

        $items = $responseAll->json('data.items');
        $this->assertCount(3, $items);
        $this->assertArrayHasKey('message', $items[0]);
        $this->assertArrayHasKey('is_read', $items[0]);
        $this->assertArrayHasKey('data', $items[0]);

        // Filter by Bookings
        $responseBookings = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/notifications?category=Bookings');

        $responseBookings->assertStatus(200)
            ->assertJsonPath('data.total', 1)
            ->assertJsonPath('data.items.0.title', 'New Booking');
    }

    public function test_unread_count_and_marking_read()
    {
        $vendor = $this->createVendor();
        $service = app(VendorNotificationService::class);

        $n1 = $service->create($vendor, 'Bookings', 'Booking 1', 'Content 1');
        $n2 = $service->create($vendor, 'System', 'System Notice', 'Content 2');

        $token = $vendor->createToken('test')->plainTextToken;

        // Unread count should be 2
        $responseUnread = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/notifications/unread-count');

        $responseUnread->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['unread_count' => 2]]);

        // Mark n1 read
        $responseMark = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/notifications/' . $n1->id . '/read');

        $responseMark->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['id' => $n1->id, 'read' => true]]);

        // Unread count should now be 1
        $responseUnread2 = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/notifications/unread-count');

        $responseUnread2->assertJson(['data' => ['unread_count' => 1]]);

        // Mark all read
        $responseMarkAll = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/notifications/read-all');

        $responseMarkAll->assertStatus(200)
            ->assertJson(['status' => true, 'data' => ['updated' => 1]]);

        // Unread count should now be 0
        $responseUnread3 = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/notifications/unread-count');

        $responseUnread3->assertJson(['data' => ['unread_count' => 0]]);
    }

    public function test_vendor_isolation_for_notifications()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $service = app(VendorNotificationService::class);

        $nA = $service->create($vendorA, 'Bookings', 'Booking A', 'Vendor A notification');
        $nB = $service->create($vendorB, 'Bookings', 'Booking B', 'Vendor B notification');

        $tokenA = $vendorA->createToken('test')->plainTextToken;
        $tokenB = $vendorB->createToken('test')->plainTextToken;

        // Vendor A list notifications -> only nA
        $resA = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/notifications');
        $resA->assertStatus(200);
        $idsA = collect($resA->json('data.items'))->pluck('id');
        $this->assertTrue($idsA->contains($nA->id));
        $this->assertFalse($idsA->contains($nB->id));

        // Vendor A tries to mark Vendor B notification as read -> 404
        $resMarkErr = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->postJson('/api/vendor/notifications/' . $nB->id . '/read');
        $resMarkErr->assertStatus(404);

        // Vendor B list notifications -> only nB
        auth()->forgetGuards();
        $resB = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenB)
            ->getJson('/api/vendor/notifications');
        $resB->assertStatus(200);
        $idsB = collect($resB->json('data.items'))->pluck('id');
        $this->assertTrue($idsB->contains($nB->id));
        $this->assertFalse($idsB->contains($nA->id));
    }

    public function test_global_system_notification_read_isolation()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();

        // Admin creates global notification using service with null vendor
        $globalNotif = app(VendorNotificationService::class)->create(
            null,
            'System',
            'System Maintenance',
            'Server upgrade tonight.'
        );

        $tokenA = $vendorA->createToken('test')->plainTextToken;
        $tokenB = $vendorB->createToken('test')->plainTextToken;

        // Both Vendor A and B see global notification as unread
        auth()->forgetGuards();
        $resA1 = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenA)->getJson('/api/vendor/notifications/unread-count');
        auth()->forgetGuards();
        $resB1 = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenB)->getJson('/api/vendor/notifications/unread-count');
        $resA1->assertStatus(200)->assertJson(['data' => ['unread_count' => 1]]);
        $resB1->assertStatus(200)->assertJson(['data' => ['unread_count' => 1]]);

        // Vendor A marks global notification as read
        auth()->forgetGuards();
        $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenA)->postJson('/api/vendor/notifications/' . $globalNotif->id . '/read')->assertStatus(200);

        // Vendor A unread count = 0, Vendor B unread count still = 1
        auth()->forgetGuards();
        $resA2 = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenA)->getJson('/api/vendor/notifications/unread-count');
        auth()->forgetGuards();
        $resB2 = $this->flushHeaders()->withHeader('Authorization', 'Bearer ' . $tokenB)->getJson('/api/vendor/notifications/unread-count');
        $resA2->assertJson(['data' => ['unread_count' => 0]]);
        $resB2->assertJson(['data' => ['unread_count' => 1]]);
    }
}
