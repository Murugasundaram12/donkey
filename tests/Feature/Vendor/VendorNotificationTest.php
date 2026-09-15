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

    public function test_vendor_can_send_test_notification()
    {
        $vendor = $this->createVendor(['device_token' => 'TEST_DEVICE_TOKEN_999']);
        $token = $vendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/notifications/send-test', [
                'category' => 'System',
                'title' => 'Test Notification',
                'content' => 'This is a test notification from Bruno.',
                'data' => [
                    'deep_link' => 'booking_details',
                    'booking_id' => 'BK-1001'
                ]
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Test notification sent successfully',
                'data' => [
                    'title' => 'Test Notification',
                    'content' => 'This is a test notification from Bruno.',
                    'category' => 'System',
                    'is_read' => false,
                    'data' => [
                        'deep_link' => 'booking_details',
                        'booking_id' => 'BK-1001'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('pushnotifications', [
            'subscriber_id' => $vendor->id,
            'title' => 'Test Notification',
            'category' => 'System',
        ]);
    }

    public function test_expired_vendor_cannot_send_test_notification_and_record_not_created()
    {
        $expiredVendor = $this->createVendor([
            'device_token' => 'EXPIRED_DEVICE_TOKEN',
            'expiryDate' => '2020-01-01 23:59:59',
        ]);
        $token = $expiredVendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/notifications/send-test', [
                'category' => 'System',
                'title' => 'Expired Test Notification',
                'content' => 'This should be blocked.',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your subscription/payment has expired. Please renew your payment.',
            ]);

        $this->assertDatabaseMissing('pushnotifications', [
            'subscriber_id' => $expiredVendor->id,
            'title' => 'Expired Test Notification',
        ]);
    }

    public function test_authenticated_vendor_deletes_own_notification()
    {
        $vendor = $this->createVendor();
        $service = app(VendorNotificationService::class);
        $notification = $service->create($vendor, 'Bookings', 'Booking Notice', 'Your booking update');
        $token = $vendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/vendor/notifications/' . $notification->id);

        $response->assertStatus(200)
            ->assertExactJson([
                'success' => true,
                'message' => 'Notification deleted successfully',
            ]);

        $this->assertDatabaseMissing('pushnotifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_vendor_cannot_delete_another_vendors_notification()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $service = app(VendorNotificationService::class);

        $notificationB = $service->create($vendorB, 'Bookings', 'Vendor B Notice', 'Notice for vendor B');
        $tokenA = $vendorA->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->deleteJson('/api/vendor/notifications/' . $notificationB->id);

        $response->assertStatus(403)
            ->assertExactJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);

        $this->assertDatabaseHas('pushnotifications', [
            'id' => $notificationB->id,
            'subscriber_id' => $vendorB->id,
        ]);
    }

    public function test_vendor_cannot_delete_global_system_notification()
    {
        $vendor = $this->createVendor();
        $service = app(VendorNotificationService::class);
        $globalNotification = $service->create(null, 'System', 'Global Notice', 'Notice for everyone');
        $token = $vendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/vendor/notifications/' . $globalNotification->id);

        $response->assertStatus(403)
            ->assertExactJson([
                'success' => false,
                'message' => 'Unauthorized',
            ]);

        $this->assertDatabaseHas('pushnotifications', [
            'id' => $globalNotification->id,
        ]);
    }

    public function test_non_existent_notification_id_returns_404()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/vendor/notifications/99999999');

        $response->assertStatus(404)
            ->assertExactJson([
                'success' => false,
                'message' => 'Notification not found',
            ]);
    }

    public function test_unauthenticated_request_returns_401()
    {
        $vendor = $this->createVendor();
        $service = app(VendorNotificationService::class);
        $notification = $service->create($vendor, 'Bookings', 'Test', 'Content');

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->deleteJson('/api/vendor/notifications/' . $notification->id);

        $response->assertStatus(401);

        $this->assertDatabaseHas('pushnotifications', [
            'id' => $notification->id,
        ]);
    }

    public function test_deleted_notification_is_no_longer_returned_in_list_and_other_vendor_untouched()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $service = app(VendorNotificationService::class);

        $nA1 = $service->create($vendorA, 'Bookings', 'Vendor A Notice 1', 'Content A1');
        $nA2 = $service->create($vendorA, 'Bookings', 'Vendor A Notice 2', 'Content A2');
        $nB1 = $service->create($vendorB, 'Bookings', 'Vendor B Notice 1', 'Content B1');

        $tokenA = $vendorA->createToken('test')->plainTextToken;
        $tokenB = $vendorB->createToken('test')->plainTextToken;

        // Vendor A deletes nA1
        auth()->forgetGuards();
        $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->deleteJson('/api/vendor/notifications/' . $nA1->id)
            ->assertStatus(200);

        // Verify Vendor A list notifications only contains nA2, not nA1
        auth()->forgetGuards();
        $resA = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/notifications');
        $resA->assertStatus(200);
        $idsA = collect($resA->json('data.items'))->pluck('id');
        $this->assertFalse($idsA->contains($nA1->id));
        $this->assertTrue($idsA->contains($nA2->id));

        // Verify Vendor B notifications are untouched
        auth()->forgetGuards();
        $resB = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $tokenB)
            ->getJson('/api/vendor/notifications');
        $resB->assertStatus(200);
        $idsB = collect($resB->json('data.items'))->pluck('id');
        $this->assertTrue($idsB->contains($nB1->id));
    }
}
