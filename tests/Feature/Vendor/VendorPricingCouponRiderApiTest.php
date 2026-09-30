<?php

namespace Tests\Feature\Vendor;

use App\Models\Coupon;
use App\Models\Driver;
use App\Models\Pincode;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorPricingCouponRiderApiTest extends TestCase
{
    use DatabaseTransactions;

    private function vendor(string $suffix): Subscriber
    {
        return Subscriber::create([
            'name' => 'API Vendor ' . $suffix, 'email' => 'api' . $suffix . '@example.com',
            'mobile' => '90000000' . str_pad($suffix, 2, '0', STR_PAD_LEFT),
            'password' => Hash::make('password123'), 'location' => 'Chennai',
            'subscriptionDate' => '2025-01-01', 'expiryDate' => '2030-12-31',
            'status' => 1, 'blockedstatus' => 1, 'pincode' => '[]',
            'aadharNo' => '123456789012', 'aadharImage' => 'front.jpg', 'pancardImage' => 'pan.jpg',
            'customerdocument' => 'doc.pdf', 'account_type' => 'Individual', 'image' => 'profile.jpg',
            'created_by' => '1',
        ]);
    }

    public function test_rider_responses_return_pincode_values_and_keep_ownership_validation(): void
    {
        $vendor = $this->vendor('11');
        $pincode = Pincode::create(['pincode' => '631102', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$pincode->id])]);
        $rider = Driver::create(['subscriberId' => $vendor->id, 'name' => 'Rider', 'pincode' => json_encode([$pincode->id]), 'status' => 1]);
        $token = $vendor->createToken('test')->plainTextToken;

        $this->withToken($token)->getJson('/api/vendor/riders/' . $rider->id)
            ->assertOk()->assertJsonPath('data.rider.pincode', ['631102']);

        $this->withToken($token)->putJson('/api/vendor/riders/' . $rider->id, ['pincode' => [999999]])
            ->assertStatus(422)->assertJsonPath('errors.pincode.0', 'Selected pincode(s) do not belong to your vendor account.');
    }

    public function test_vendor_can_get_and_update_own_pricing(): void
    {
        $vendor = $this->vendor('12');
        $token = $vendor->createToken('test')->plainTextToken;
        $this->withToken($token)->putJson('/api/vendor/pricing', [
            'bike_taxi_service_fare' => 55, 'bike_taxi_1_to_5_km' => 60,
        ])->assertOk()->assertJsonPath('data.bike_taxi_service_fare', 55)->assertJsonPath('data.bike_taxi_1_to_5_km', 60);
        $this->assertSame('55', (string) $vendor->fresh()->biketaxi_price);
    }

    public function test_coupon_crud_is_isolated_to_creator(): void
    {
        $vendorA = $this->vendor('13');
        $vendorB = $this->vendor('14');
        $tokenA = $vendorA->createToken('test')->plainTextToken;
        $coupon = Coupon::create([
            'title' => 'Test Coupon', 'type' => 1, 'code' => 'TEST13', 'limit' => 10,
            'is_multiple' => 0, 'start_date' => '2026-01-01', 'expiry_date' => '2026-12-31',
            'discount_type' => 1, 'amount' => 10, 'status' => 1, 'created_by' => $vendorA->id,
        ]);

        $this->withToken($tokenA)->getJson('/api/vendor/coupons/' . $coupon->id)->assertOk();
        $this->withToken($vendorB->createToken('test')->plainTextToken)
            ->getJson('/api/vendor/coupons/' . $coupon->id)->assertNotFound();
        $this->withToken($tokenA)->putJson('/api/vendor/coupons/' . $coupon->id, ['title' => 'Updated'])
            ->assertOk()->assertJsonPath('data.coupon.title', 'Updated');
        $this->withToken($tokenA)->deleteJson('/api/vendor/coupons/' . $coupon->id)->assertOk();
    }

    private function withToken(string $token)
    {
        return $this->withHeader('Authorization', 'Bearer ' . $token);
    }
}
