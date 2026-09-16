<?php

namespace Tests\Feature\Vendor;

use App\Models\PaymentDetails;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SubscriptionRenewalPaymentTest extends TestCase
{
    use DatabaseTransactions;

    private function createVendor(array $overrides = []): Subscriber
    {
        static $count = 1;
        $idx = $count++ . '_' . time() . '_' . mt_rand(100, 999);

        return Subscriber::create(array_merge([
            'name' => "Vendor {$idx}",
            'email' => "vendor_{$idx}@test.com",
            'mobile' => '99' . mt_rand(10000000, 99999999),
            'password' => Hash::make('password123'),
            'subscriberId' => "SUB_{$idx}",
            'location' => 'Test City',
            'subscriptionDate' => now()->toDateTimeString(),
            'expiryDate' => now()->addDays(28)->toDateTimeString(),
            'subscription_price' => '1500',
            'platform_fee' => '0',
            'need_to_pay' => 0,
            'status' => 1,
            'activestatus' => 1,
            'blockedstatus' => 1,
            'pincode' => json_encode(['600001']),
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
            'device_token' => 'TOKEN_' . $idx,
        ], $overrides));
    }

    /**
     * Test Case 1:
     * Subscriber subscription_price = 1500, platform_fee = 0
     * Expected: base = 1500, GST = 270, total = 1770
     */
    public function test_subscriber_with_1500_subscription_price_and_zero_platform_fee()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '1500',
            'platform_fee' => '0',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'data' => [
                    'payment_type' => 'Subscription',
                    'subscription_price' => 1500,
                    'gst_percentage' => 18,
                    'gst_amount' => 270,
                    'total_payable' => 1770,
                    'currency' => 'INR',
                    'platform_fee' => 0,
                ]
            ]);

        // Verify validId endpoint
        $validIdResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $validIdResponse->assertStatus(200);
        $this->assertEquals(1770, (float) $validIdResponse->getContent());
    }

    /**
     * Test Case 2:
     * Subscriber subscription_price = 100, platform_fee = 500
     * Expected: base = 100, GST = 18, total = 118 (NOT 590)
     */
    public function test_subscriber_with_100_subscription_price_and_500_platform_fee()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '100',
            'platform_fee' => '500',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'data' => [
                    'payment_type' => 'Subscription',
                    'subscription_price' => 100,
                    'gst_percentage' => 18,
                    'gst_amount' => 18,
                    'total_payable' => 118,
                    'currency' => 'INR',
                    'platform_fee' => 500,
                ]
            ]);

        // Must NOT be 590 (500 * 1.18)
        $this->assertNotEquals(590, $response->json('data.total_payable'));

        // Verify validId endpoint
        $validIdResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $validIdResponse->assertStatus(200);
        $this->assertEquals(118, (float) $validIdResponse->getContent());
    }

    /**
     * Test Case 3:
     * Subscriber subscription_price = 2, platform_fee = 149.50
     * Expected: base = 2, GST = 0.36, total = 2.36
     */
    public function test_subscriber_with_2_subscription_price_and_149_50_platform_fee()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '2',
            'platform_fee' => '149.50',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'data' => [
                    'payment_type' => 'Subscription',
                    'subscription_price' => 2,
                    'gst_percentage' => 18,
                    'gst_amount' => 0.36,
                    'total_payable' => 2.36,
                    'currency' => 'INR',
                    'platform_fee' => 149.50,
                ]
            ]);

        // Verify validId endpoint
        $validIdResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $validIdResponse->assertStatus(200);
        $this->assertEquals(2.36, round((float) $validIdResponse->getContent(), 2));
    }

    /**
     * Test Case 4:
     * Current vendor API returns the authenticated vendor's own subscription price.
     */
    public function test_subscription_payment_api_returns_authenticated_vendor_own_price()
    {
        $vendorA = $this->createVendor(['subscription_price' => '2500']);
        $vendorB = $this->createVendor(['subscription_price' => '800']);

        $tokenA = $vendorA->createToken('vendor_test_token')->plainTextToken;

        $responseA = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/subscription-payment');

        $responseA->assertStatus(200);
        $this->assertEquals(2500, $responseA->json('data.subscription_price'));
        $this->assertEquals(2950, $responseA->json('data.total_payable'));
    }

    /**
     * Test Case 5:
     * Vendor cannot request another subscriber's renewal amount by sending another subscriberId.
     */
    public function test_vendor_cannot_request_another_subscribers_renewal_via_query_param()
    {
        $vendorA = $this->createVendor(['subscription_price' => '2000']);
        $vendorB = $this->createVendor(['subscription_price' => '500']);

        $tokenA = $vendorA->createToken('vendor_test_token')->plainTextToken;

        // Vendor A attempts to pass vendor B's subscriberId in query parameter
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/subscription-payment?subscriberId=' . $vendorB->subscriberId);

        $response->assertStatus(200);
        // API MUST strictly use authenticated vendor A's price (2000), ignoring query param
        $this->assertEquals(2000, $response->json('data.subscription_price'));
        $this->assertEquals(2360, $response->json('data.total_payable'));
    }

    /**
     * Test Case 6:
     * /validId still works correctly for valid and invalid subscribers.
     */
    public function test_valid_id_endpoint_behavior()
    {
        $vendor = $this->createVendor(['subscription_price' => '1200']);

        $validResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $validResponse->assertStatus(200);
        $this->assertEquals(1416, (float) $validResponse->getContent());

        // Non-existent subscriberId returns 0
        $invalidResponse = $this->get('/validId?subscriberId=NON_EXISTENT_ID');
        $invalidResponse->assertStatus(200);
        $this->assertEquals('0', (string) $invalidResponse->getContent());
    }

    /**
     * Test Case 7:
     * Existing GET /api/vendor/payments returns historical payment records unchanged.
     */
    public function test_existing_payments_history_endpoint_remains_unchanged()
    {
        $vendor = $this->createVendor();
        PaymentDetails::create([
            'invoice_no' => 'PBPS-TEST-1',
            'type' => 1,
            'subscriberId' => $vendor->subscriberId,
            'payment_id' => 'pay_hist_123',
            'status_code' => '200',
            'amount' => '177000', // in paise (1770.00)
            'signature' => 'sig_test',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'message' => 'Payments retrieved successfully',
            ]);

        $payments = $response->json('data.payments');
        $this->assertNotEmpty($payments);
        $this->assertEquals(1770, $payments[0]['amount']);
        $this->assertEquals('Razorpay', $payments[0]['payment_method']);
    }

    /**
     * Test Case 8:
     * Existing GET /api/vendor/payments/{id} remains unchanged.
     */
    public function test_existing_payment_details_endpoint_remains_unchanged()
    {
        $vendor = $this->createVendor();
        $payment = PaymentDetails::create([
            'invoice_no' => 'PBPS-TEST-2',
            'type' => 1,
            'subscriberId' => $vendor->subscriberId,
            'payment_id' => 'pay_hist_456',
            'status_code' => '200',
            'amount' => '99900',
            'signature' => 'sig_test_2',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments/' . $payment->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'message' => 'Payment details retrieved successfully',
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'amount' => 999.00,
                        'transaction_id' => 'pay_hist_456',
                    ]
                ]
            ]);
    }

    /**
     * Test Case 9:
     * Platform fee flow remains completely separate.
     */
    public function test_platform_fee_remains_separate()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '1500',
            'platform_fee' => '350.00',
            'need_to_pay' => 1,
        ]);

        $this->assertEquals('1500', $vendor->subscription_price);
        $this->assertEquals('350.00', $vendor->platform_fee);
        $this->assertEquals(1, $vendor->need_to_pay);

        // Platform fee route makePlatFormFee still receives subscriber
        $response = $this->get('/makePlatFormFee?Id=' . $vendor->id);
        $response->assertStatus(200);
        $response->assertViewHas('subscriber');
        $this->assertEquals($vendor->id, $response->original->getData()['subscriber']->id);
    }

    /**
     * Test Case 10:
     * Null or invalid subscription_price falls back to Rs. 2, NOT platform_fee.
     */
    public function test_null_or_invalid_subscription_price_falls_back_to_2_not_platform_fee()
    {
        $vendor = $this->createVendor([
            'subscription_price' => null,
            'platform_fee' => '850.00',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'data' => [
                    'subscription_price' => 2,
                    'gst_amount' => 0.36,
                    'total_payable' => 2.36,
                    'platform_fee' => 850.00,
                ]
            ]);

        // Must NOT use platform_fee (850) as subscription price
        $this->assertNotEquals(850, $response->json('data.subscription_price'));

        // validId must also return 2.36, NOT 850 * 1.18 = 1003
        $validIdResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $this->assertEquals(2.36, round((float) $validIdResponse->getContent(), 2));
    }

    /**
     * Test Case 11:
     * GET /api/vendor/me includes subscription renewal pricing fields:
     * subscription_price=100, platform_fee=924.83 => total payable 118
     * All existing keys must remain present and unchanged.
     */
    public function test_vendor_me_profile_includes_subscription_renewal_pricing_100_924()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '100',
            'platform_fee' => '924.83',
            'need_to_pay' => 1,
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Vendor profile fetched successfully',
                'data' => [
                    'vendor' => [
                        'id' => $vendor->id,
                        'subscriber_id' => $vendor->subscriberId,
                        'name' => $vendor->name,
                        'email' => $vendor->email,
                        'mobile' => $vendor->mobile,
                        'status' => 1,
                        'blocked_status' => 1,
                        'payment_status' => 1,
                        'need_to_pay' => 1,
                        'platform_fee' => 924.83,
                        'subscription_price' => 100,
                        'subscription_gst_percentage' => 18,
                        'subscription_gst_amount' => 18,
                        'subscription_total_payable' => 118,
                        'subscription_total_payable_in_paise' => 11800,
                    ]
                ]
            ]);

        $vendorData = $response->json('data.vendor');
        $this->assertArrayHasKey('created_at', $vendorData);
        $this->assertArrayHasKey('payment_expiry', $vendorData);
        $this->assertArrayHasKey('subscription_date', $vendorData);
        $this->assertArrayHasKey('expiry_date', $vendorData);
        $this->assertEquals(924.83, $vendorData['platform_fee']);
        $this->assertEquals(118, $vendorData['subscription_total_payable']);
    }

    /**
     * Test Case 12:
     * GET /api/vendor/me with subscription_price=1500, platform_fee=0 => total payable 1770
     */
    public function test_vendor_me_profile_1500_0()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '1500',
            'platform_fee' => '0',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'platform_fee' => 0,
                        'subscription_price' => 1500,
                        'subscription_gst_percentage' => 18,
                        'subscription_gst_amount' => 270,
                        'subscription_total_payable' => 1770,
                        'subscription_total_payable_in_paise' => 177000,
                    ]
                ]
            ]);
    }

    /**
     * Test Case 13:
     * GET /api/vendor/me with subscription_price=2, platform_fee=149.50 => total payable 2.36
     */
    public function test_vendor_me_profile_2_149_50()
    {
        $vendor = $this->createVendor([
            'subscription_price' => '2',
            'platform_fee' => '149.50',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'platform_fee' => 149.50,
                        'subscription_price' => 2,
                        'subscription_gst_percentage' => 18,
                        'subscription_gst_amount' => 0.36,
                        'subscription_total_payable' => 2.36,
                        'subscription_total_payable_in_paise' => 236,
                    ]
                ]
            ]);
    }

    /**
     * Test Case 14:
     * GET /api/vendor/me with null/invalid subscription_price => fallback 2.36, platform_fee remains separate
     */
    public function test_vendor_me_profile_null_fallback()
    {
        $vendor = $this->createVendor([
            'subscription_price' => null,
            'platform_fee' => '450.00',
        ]);

        $token = $vendor->createToken('vendor_test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'platform_fee' => 450.00,
                        'subscription_price' => 2,
                        'subscription_gst_percentage' => 18,
                        'subscription_gst_amount' => 0.36,
                        'subscription_total_payable' => 2.36,
                        'subscription_total_payable_in_paise' => 236,
                    ]
                ]
            ]);

        $this->assertNotEquals(450, $response->json('data.vendor.subscription_price'));
    }
}

