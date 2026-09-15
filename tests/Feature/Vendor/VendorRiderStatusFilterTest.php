<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Driver;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class VendorRiderStatusFilterTest extends TestCase
{
    use DatabaseTransactions;

    private static int $counter = 0;

    private function createVendor(array $overrides = []): Subscriber
    {
        $idx = ++self::$counter;
        return Subscriber::create(array_merge([
            'name'             => "VendorRSF_{$idx}",
            'email'            => "vrsf_{$idx}_" . time() . "@test.com",
            'mobile'           => '9' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'password'         => Hash::make('password123'),
            'subscriberId'     => "VRSF{$idx}",
            'location'         => 'Test Location',
            'subscriptionDate' => '2025-01-01 00:00:00',
            'expiryDate'       => '2030-12-31 23:59:59',
            'status'           => 1,
            'blockedstatus'    => 1,
            'pincode'          => json_encode([(string) $idx]),
            'aadharNo'         => '123456789012',
            'aadharImage'      => 'front.jpg',
            'pancardImage'     => 'pan.jpg',
            'customerdocument' => 'doc.pdf',
            'account_type'     => 'Individual',
            'image'            => 'profile.jpg',
            'created_by'       => '1',
            'bankstatement'    => 'stmt.jpg',
            'aadharBackImage'  => 'back.jpg',
            'video'            => '',
            'gst'              => '',
            'qr'               => '',
        ], $overrides));
    }

    private function createDriver(Subscriber $vendor, array $overrides = []): Driver
    {
        $idx = ++self::$counter;
        $user = User::create([
            'name'     => "RiderUser_{$idx}",
            'email'    => "rider_{$idx}_" . time() . "@test.com",
            'phone'    => '8' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'password' => Hash::make('password123'),
            'user_id'  => 'DK-RSF-' . $idx,
            'is_driver' => 1,
            'is_live'   => 0,
        ]);

        return Driver::create(array_merge([
            'subscriberId'    => $vendor->id,
            'userid'          => $user->id,
            'name'            => "Rider_{$idx}",
            'mobile'          => '7' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'email'           => "rider_{$idx}@test.com",
            'password'        => Hash::make('password123'),
            'language'        => 'English',
            'pincode'         => json_encode([600001]),
            'aadharNo'        => '111122223333',
            'aadharFrontImage' => 'front.jpg',
            'aadharBackImage'  => 'back.jpg',
            'rcbook'           => 'rc.jpg',
            'drivingLicence'   => 'dl.jpg',
            'vehicleNo'        => 'TN01XX' . $idx,
            'vehicleModelNo'   => '2022',
            'status'           => 1,  // default: approved
        ], $overrides));
    }

    // --- Helper to get the linked User for a Driver ---
    private function getUserForDriver(Driver $driver): User
    {
        return User::findOrFail($driver->userid);
    }

    // ========================================================================
    // TC-1: Pending Approval rider must NOT appear in status=engaged
    // ========================================================================
    public function test_pending_approval_rider_does_not_appear_in_engaged()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        // Create a Pending Approval rider (status = 0)
        $nandhini = $this->createDriver($vendor, ['name' => 'Nandhini', 'status' => 0]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=engaged');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertFalse($ids->contains($nandhini->id),
            'Pending Approval rider Nandhini must NOT appear in status=engaged');
    }

    // ========================================================================
    // TC-2: Pending Approval rider must NOT appear in status=offline
    // ========================================================================
    public function test_pending_approval_rider_does_not_appear_in_offline()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $nandhini = $this->createDriver($vendor, ['name' => 'Nandhini', 'status' => 0]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=offline');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertFalse($ids->contains($nandhini->id),
            'Pending Approval rider Nandhini must NOT appear in status=offline');
    }

    // ========================================================================
    // TC-3: Pending Approval rider MUST appear in status=pending
    // ========================================================================
    public function test_pending_approval_rider_appears_in_pending()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $nandhini = $this->createDriver($vendor, ['name' => 'Nandhini', 'status' => 0]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=pending');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($nandhini->id),
            'Pending Approval rider Nandhini MUST appear in status=pending');
    }

    // ========================================================================
    // TC-4: Approved + offline rider appears in status=offline
    // ========================================================================
    public function test_approved_offline_rider_appears_in_offline()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        // Approved rider, is_live=0 (offline) by default
        $rider = $this->createDriver($vendor, ['status' => 1]);
        // Ensure the user is not live
        User::where('id', $rider->userid)->update(['is_live' => 0]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=offline');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($rider->id),
            'Approved offline rider must appear in status=offline');
    }

    // ========================================================================
    // TC-5: Approved + online + active booking appears in status=engaged
    // ========================================================================
    public function test_approved_online_rider_with_active_booking_appears_in_engaged()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createDriver($vendor, ['status' => 1]);
        // Set rider's linked user to online
        User::where('id', $rider->userid)->update(['is_live' => 1]);

        // Create an active booking (status=1) linked to this rider via vendor
        $booking = Booking::create([
            'booking_id'             => 'BK-ENGAGED-' . $rider->id,
            'assigned_subscriber_id' => $vendor->id,
            'status'                 => 1,
            'category'               => 1,
            'pincode'                => '600001',
            'driver_id'              => (string) $rider->id,
            'accepted'               => (string) $rider->userid,
        ]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=engaged');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($rider->id),
            'Approved online rider with active booking must appear in status=engaged');
    }

    // ========================================================================
    // TC-6: Approved rider with no active booking must NOT appear in status=engaged
    // ========================================================================
    public function test_approved_rider_without_active_booking_does_not_appear_in_engaged()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createDriver($vendor, ['status' => 1]);
        // No booking created for this rider

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=engaged');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertFalse($ids->contains($rider->id),
            'Approved rider without active booking must NOT appear in status=engaged');
    }

    // ========================================================================
    // TC-7: Blocked/Rejected rider must NOT appear in status=engaged or status=offline
    // ========================================================================
    public function test_blocked_rider_does_not_appear_in_engaged_or_offline()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createDriver($vendor, ['status' => 2]);

        auth()->forgetGuards();
        $engagedResp = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=engaged');
        $engagedResp->assertStatus(200);
        $this->assertFalse(
            collect($engagedResp->json('data.items'))->pluck('id')->contains($rider->id),
            'Blocked rider must NOT appear in status=engaged'
        );

        auth()->forgetGuards();
        $offlineResp = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=offline');
        $offlineResp->assertStatus(200);
        $this->assertFalse(
            collect($offlineResp->json('data.items'))->pluck('id')->contains($rider->id),
            'Blocked rider must NOT appear in status=offline'
        );
    }

    // ========================================================================
    // TC-8: Search + status=engaged filter work together
    // ========================================================================
    public function test_search_and_engaged_status_filter_work_together()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        // Engaged rider (active, live, active booking) with unique name
        $riderEngaged = $this->createDriver($vendor, [
            'status' => 1,
            'name'   => 'UniqueEngagedRSF',
        ]);
        User::where('id', $riderEngaged->userid)->update(['is_live' => 1]);
        Booking::create([
            'booking_id'             => 'BK-SEARCH-' . $riderEngaged->id,
            'assigned_subscriber_id' => $vendor->id,
            'status'                 => 1,
            'category'               => 1,
            'pincode'                => '600001',
            'driver_id'              => (string) $riderEngaged->id,
            'accepted'               => (string) $riderEngaged->userid,
        ]);

        // Another rider, not engaged
        $riderOther = $this->createDriver($vendor, ['name' => 'UniqueOfflineRSF', 'status' => 1]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=engaged&search=UniqueEngagedRSF');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');

        $this->assertTrue($ids->contains($riderEngaged->id),
            'Engaged rider with matching name must appear when search+engaged filter applied');
        $this->assertFalse($ids->contains($riderOther->id),
            'Non-matching rider must NOT appear when search+engaged filter applied');
    }

    // ========================================================================
    // TC-9: Pagination structure is preserved for status=offline
    // ========================================================================
    public function test_pagination_structure_preserved_for_offline_status()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=offline&per_page=5');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                    'items',
                ],
            ]);

        $this->assertEquals(5, $response->json('data.per_page'));
    }

    // ========================================================================
    // TC-10: Numeric status value 0 still works (backward-compat)
    // ========================================================================
    public function test_numeric_status_0_returns_pending_riders()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $pendingRider   = $this->createDriver($vendor, ['status' => 0]);
        $approvedRider  = $this->createDriver($vendor, ['status' => 1]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=0');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($pendingRider->id),
            'Numeric status=0 must return pending riders');
        $this->assertFalse($ids->contains($approvedRider->id),
            'Numeric status=0 must NOT return approved riders');
    }

    // ========================================================================
    // TC-11: Numeric status value 1 still works (backward-compat)
    // ========================================================================
    public function test_numeric_status_1_returns_approved_riders()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $pendingRider  = $this->createDriver($vendor, ['status' => 0]);
        $approvedRider = $this->createDriver($vendor, ['status' => 1]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders?status=1');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($approvedRider->id),
            'Numeric status=1 must return approved riders');
        $this->assertFalse($ids->contains($pendingRider->id),
            'Numeric status=1 must NOT return pending riders');
    }

    // ========================================================================
    // TC-12: No status param returns all vendor's riders
    // ========================================================================
    public function test_no_status_param_returns_all_vendor_riders()
    {
        $vendor = $this->createVendor();
        $token  = $vendor->createToken('test')->plainTextToken;

        $r0 = $this->createDriver($vendor, ['status' => 0]);
        $r1 = $this->createDriver($vendor, ['status' => 1]);
        $r2 = $this->createDriver($vendor, ['status' => 2]);

        auth()->forgetGuards();
        $response = $this->flushHeaders()
            ->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $ids = collect($response->json('data.items'))->pluck('id');
        $this->assertTrue($ids->contains($r0->id));
        $this->assertTrue($ids->contains($r1->id));
        $this->assertTrue($ids->contains($r2->id));
    }
}
