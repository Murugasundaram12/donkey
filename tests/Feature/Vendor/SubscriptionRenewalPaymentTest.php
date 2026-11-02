<?php

namespace Tests\Feature\Vendor;

use App\Models\PaymentDetails;
use App\Models\Subscriber;
use App\Models\SubscriptionRenewal;
use App\Services\SubscriptionRenewalService;
use Carbon\Carbon;
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
     * Expected: base = 2, GST = 0.36, rounded payable = 2
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
        $this->assertEquals(2.36, (float) $validIdResponse->getContent());
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

        // validId must also return the exact default payable amount
        $validIdResponse = $this->get('/validId?subscriberId=' . $vendor->subscriberId);
        $this->assertEquals(2.36, (float) $validIdResponse->getContent());
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
     * GET /api/vendor/me with subscription_price=2, platform_fee=149.50 => rounded payable 2
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
                        'platform_fee' => 450,
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

    /**
     * TC01: ₹1.00 + 18% GST = ₹1.18 / 118 paise.
     */
    public function test_tc01_one_rupee_subscription_plus_18_percent_gst_equals_1_18_and_118_paise()
    {
        $vendor = $this->createVendor(['subscription_price' => '1.00']);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'subscription_price' => 1.0,
                    'gst_amount' => 0.18,
                    'exact_total_payable' => 1.18,
                    'total_payable' => 1.18,
                    'total_payable_in_paise' => 118,
                ]
            ]);
    }

    /**
     * TC02: ₹2.00 + GST = ₹2.36 / 236 paise.
     */
    public function test_tc02_two_rupee_subscription_plus_gst_equals_2_36_and_236_paise()
    {
        $vendor = $this->createVendor(['subscription_price' => '2.00']);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'subscription_price' => 2.0,
                    'gst_amount' => 0.36,
                    'exact_total_payable' => 2.36,
                    'total_payable' => 2.36,
                    'total_payable_in_paise' => 236,
                ]
            ]);
    }

    /**
     * TC03: ₹2.00 subscription + ₹30 penalty + GST = ₹37.76 / 3776 paise.
     */
    public function test_tc03_two_rupee_subscription_plus_30_penalty_plus_gst_equals_37_76_and_3776_paise()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));
        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00', // 2 penalty days
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'subscription_price' => 2.0,
                    'gst_amount' => 0.36,
                    'penalty_day' => 2,
                    'penalty_principal' => 30.0,
                    'penalty_gst' => 5.40,
                    'exact_total_payable' => 37.76,
                    'total_payable' => 37.76,
                    'total_payable_in_paise' => 3776,
                ]
            ]);

        Carbon::setTestNow();
    }

    /**
     * TC04: Razorpay order amount matches quote paise.
     */
    public function test_tc04_razorpay_order_amount_matches_quote_paise()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $createdOrderParams = [];
        $mockApi = new class($createdOrderParams) {
            public object $order;
            public function __construct(public array &$createdOrderParams) {
                $createdOrderParamsRef = &$this->createdOrderParams;
                $this->order = new class($createdOrderParamsRef) {
                    public function __construct(private array &$paramsRef) {}
                    public function create(array $params): object {
                        $this->paramsRef = $params;
                        return (object) ['id' => 'order_tc04_mock'];
                    }
                };
            }
        };

        $mockService = new class($mockApi) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $mockApi) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->mockApi;
            }
        };
        $this->app->instance(\App\Services\SubscriptionRenewalService::class, $mockService);

        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));
        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00', // 2 penalty days -> 3776 paise
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/order');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'razorpay_order_id' => 'order_tc04_mock',
                    'quote' => [
                        'exact_total_payable' => 37.76,
                        'total_payable' => 37.76,
                        'total_payable_in_paise' => 3776,
                    ]
                ]
            ]);

        $this->assertSame(3776, $createdOrderParams['amount']);
        $this->assertSame('INR', $createdOrderParams['currency']);

        Carbon::setTestNow();
    }

    /**
     * TC05: Settlement succeeds when payment verification crosses midnight.
     */
    public function test_tc05_settlement_succeeds_when_payment_verification_crosses_midnight()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        // Order created on Day 2 of penalty (2026-10-30) for 37.76 (3776 paise)
        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 2,
            'penalty_principal' => 30.0,
            'penalty_gst' => 5.40,
            'renewal_days' => 28,
            'bonus_days' => 0,
            'total_payable' => 37.76,
            'razorpay_order_id' => 'order_midnight_test',
        ]);

        // Customer pays 3776 paise, verification occurs past midnight on Day 3 (2026-10-31)
        Carbon::setTestNow(Carbon::create(2026, 10, 31, 0, 5, 0, 'Asia/Kolkata'));

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_midnight_test',
                    'amount' => 3776,
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        $result = $service->settle(
            $vendor,
            $renewal,
            'pay_midnight_test_123',
            'signature_midnight_test'
        );

        $this->assertFalse($result['already_paid']);
        $renewal->refresh();
        $vendor->refresh();

        // Preserves original renewal terms and amount without penalty skew
        $this->assertSame('paid', $renewal->status);
        $this->assertSame(37.76, (float) $renewal->total_payable);
        $this->assertSame(1, (int) $vendor->status);
        $this->assertSame(1, (int) $vendor->activestatus);
        $this->assertSame('2026-11-28', $vendor->expiryDate->toDateString());

        Carbon::setTestNow();
    }

    /**
     * TC06: Repeated payment verification does not duplicate renewal.
     */
    public function test_tc06_repeated_payment_verification_does_not_duplicate_renewal()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 2,
            'penalty_principal' => 30.0,
            'penalty_gst' => 5.40,
            'renewal_days' => 28,
            'bonus_days' => 0,
            'total_payable' => 37.76,
            'razorpay_order_id' => 'order_duplicate_test',
        ]);

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_duplicate_test',
                    'amount' => 3776,
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        // First verification
        $res1 = $service->settle($vendor, $renewal, 'pay_dup_123', 'sig_123');
        $this->assertFalse($res1['already_paid']);

        // Second (repeated) verification
        $res2 = $service->settle($vendor, $renewal, 'pay_dup_123', 'sig_123');
        $this->assertTrue($res2['already_paid']);

        // Assert payment record was only created once
        $this->assertSame(1, PaymentDetails::where('payment_id', 'pay_dup_123')->count());
    }

    /**
     * TC07: Existing payment signature verification remains enforced.
     */
    public function test_tc07_existing_payment_signature_verification_remains_enforced()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        // Missing/invalid signature returns 422
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/verify', [
                'renewal_id' => 1,
                'razorpay_payment_id' => 'pay_test_123',
                'razorpay_signature' => 'invalid-short-sig',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['razorpay_signature']);
    }

    /**
     * TC08: Expired vendor login still succeeds after all changes.
     */
    public function test_tc08_expired_vendor_login_still_succeeds_after_all_changes()
    {
        $vendor = $this->createVendor([
            'email' => 'tc08_expired@test.com',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]);

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'tc08_expired@test.com',
            'password' => 'password123',
            'device_token' => 'DEV_TOKEN_TC08',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'payment_status' => 0,
                    ]
                ]
            ]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * TC09: Expired vendor business APIs remain blocked until verified renewal.
     */
    public function test_tc09_expired_vendor_business_apis_remain_blocked_until_verified_renewal()
    {
        $vendor = $this->createVendor([
            'email' => 'tc09_expired@test.com',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        // Blocked before renewal
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings')
            ->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your subscription/payment has expired. Please renew your payment.',
            ]);

        // Access renewal endpoint is allowed
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment')
            ->assertStatus(200);

        // After verified renewal (update expiry to future)
        $vendor->update(['expiryDate' => Carbon::now()->addDays(28)->format('Y-m-d H:i:s')]);
        app('auth')->forgetGuards();

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings')
            ->assertStatus(200);
    }

    /**
     * TC10: Wrong Razorpay order ID is rejected.
     */
    public function test_tc10_wrong_razorpay_order_id_is_rejected()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor(['subscription_price' => '2.00']);
        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'total_payable' => 2.36,
            'razorpay_order_id' => 'order_expected_123',
        ]);

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_wrong_999', // Mismatched order_id
                    'amount' => 236,
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Razorpay payment does not match the renewal order.');

        $service->settle($vendor, $renewal, 'pay_tc10_123', 'sig_tc10_123');
    }

    /**
     * TC11: Payment matching a newly calculated quote but not the original order amount is rejected.
     */
    public function test_tc11_payment_matching_newly_calculated_quote_but_not_original_order_amount_is_rejected()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        // Original order was locked at ₹37.76 (3776 paise) on Day 2 overdue
        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 2,
            'penalty_principal' => 30.0,
            'penalty_gst' => 5.40,
            'total_payable' => 37.76,
            'razorpay_order_id' => 'order_strict_amount_test',
        ]);

        // Advance to Day 3 overdue: new quote would be ₹55.46 (5546 paise)
        Carbon::setTestNow(Carbon::create(2026, 10, 31, 10, 0, 0, 'Asia/Kolkata'));

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        // Simulated payment returns 5546 (matches Day 3 new quote, but does NOT match original order amount 3776)
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_strict_amount_test',
                    'amount' => 5546, // matches new quote, not locked renewal
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Razorpay payment does not match the renewal order amount.');

        try {
            $service->settle($vendor, $renewal, 'pay_tc11_wrong_amt', 'sig_tc11');
        } finally {
            Carbon::setTestNow();
        }
    }

    /**
     * TC12: Uncaptured payment status is rejected.
     */
    public function test_tc12_uncaptured_payment_is_rejected()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor(['subscription_price' => '2.00']);
        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'total_payable' => 2.36,
            'razorpay_order_id' => 'order_uncaptured_test',
        ]);

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_uncaptured_test',
                    'amount' => 236,
                    'status' => 'authorized', // Not 'captured'
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Razorpay payment does not match the renewal order.');

        $service->settle($vendor, $renewal, 'pay_uncaptured_123', 'sig_uncaptured');
    }

    /**
     * TC13: Reusing an existing payment ID for another renewal is rejected.
     */
    public function test_tc13_reusing_existing_payment_id_for_another_renewal_is_rejected()
    {
        config([
            'services.razorpay.key_id' => 'test-key',
            'services.razorpay.key_secret' => 'test-secret',
        ]);

        $vendor = $this->createVendor(['subscription_price' => '2.00']);

        // Existing processed payment
        PaymentDetails::create([
            'subscriberId' => $vendor->subscriberId,
            'payment_id' => 'pay_reused_id_999',
            'status_code' => '200',
            'amount' => '236',
            'signature' => 'sig_prior',
            'type' => 1,
        ]);

        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'total_payable' => 2.36,
            'razorpay_order_id' => 'order_reused_test',
        ]);

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_reused_test',
                    'amount' => 236,
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Payment has already been processed.');

        $service->settle($vendor, $renewal, 'pay_reused_id_999', 'sig_reused');
    }

    /**
     * TC14: Expired subscriber with historical paid renewal can create a new renewal.
     */
    public function test_tc14_expired_subscriber_with_historical_paid_renewal_can_create_new_renewal()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        // Historical paid renewal record
        $historical = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'payment_date' => '2026-09-28',
            'status' => 'paid',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 0,
            'penalty_principal' => 0.0,
            'penalty_gst' => 0.0,
            'renewal_days' => 30,
            'bonus_days' => 2,
            'total_payable' => 2.0,
            'razorpay_order_id' => 'order_historical_paid',
            'payment_id' => 'pay_historical_paid',
            'paid_at' => Carbon::create(2026, 9, 28, 8, 20, 0, 'Asia/Kolkata'),
        ]);

        $orderApi = new class {
            public int $createCallCount = 0;
            public function create(array $attributes): object {
                $this->createCallCount++;
                return (object) ['id' => 'order_new_renewal_' . $this->createCallCount];
            }
        };
        $api = new class($orderApi) {
            public function __construct(public object $order) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);
        $this->app->instance(\App\Services\SubscriptionRenewalService::class, $service);

        $token = $vendor->createToken('test_tc14')->plainTextToken;
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/order');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Renewal order created.',
                'data' => [
                    'already_paid' => false,
                    'status' => 'order_created',
                    'razorpay_order_id' => 'order_new_renewal_1',
                    'quote' => [
                        'exact_total_payable' => 37.76,
                        'total_payable' => 37.76,
                        'total_payable_in_paise' => 3776,
                        'penalty_day' => 2,
                    ],
                ],
            ]);

        $newRenewal = SubscriptionRenewal::whereKey($response->json('data.renewal_id'))->first();
        $this->assertNotNull($newRenewal);
        $this->assertNotEquals($historical->id, $newRenewal->id);
        $this->assertEquals($vendor->id . ':2026-10-28:2', $newRenewal->cycle_key);
        $this->assertSame('order_created', $newRenewal->status);

        Carbon::setTestNow();
    }

    /**
     * TC15: Repeated order requests reuse the same current renewal and do not create duplicate orders.
     */
    public function test_tc15_repeated_order_requests_reuse_same_current_renewal_without_duplicates()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'paid',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'total_payable' => 2.0,
            'paid_at' => now(),
        ]);

        $orderApi = new class {
            public int $createCallCount = 0;
            public function create(array $attributes): object {
                $this->createCallCount++;
                return (object) ['id' => 'order_single_creation'];
            }
        };
        $api = new class($orderApi) {
            public function __construct(public object $order) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(public object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);
        $this->app->instance(\App\Services\SubscriptionRenewalService::class, $service);

        $token = $vendor->createToken('test_tc15')->plainTextToken;

        // First request creates the order
        $res1 = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/order');
        $res1->assertStatus(200);

        // Second request reuses the same order
        $res2 = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/order');
        $res2->assertStatus(200);

        $this->assertSame($res1->json('data.renewal_id'), $res2->json('data.renewal_id'));
        $this->assertSame($res1->json('data.razorpay_order_id'), $res2->json('data.razorpay_order_id'));
        $this->assertSame(1, $orderApi->createCallCount);

        // Only 2 records in total: 1 historical paid + 1 current order_created
        $this->assertSame(2, SubscriptionRenewal::where('subscriber_id', $vendor->id)->count());

        Carbon::setTestNow();
    }

    /**
     * TC16: Successful payment settlement updates subscription expiry according to stored renewal_days.
     */
    public function test_tc16_successful_payment_settlement_updates_subscription_expiry_per_renewal_days()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        $renewal = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28:2',
            'due_date' => '2026-10-28',
            'status' => 'order_created',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 2,
            'penalty_principal' => 30.0,
            'penalty_gst' => 5.4,
            'renewal_days' => 28,
            'bonus_days' => 0,
            'total_payable' => 37.76,
            'razorpay_order_id' => 'order_tc16_settle',
        ]);

        $utility = new class {
            public function verifyPaymentSignature(array $attributes): void {}
        };
        $paymentApi = new class {
            public function fetch(string $paymentId): object {
                return (object) [
                    'order_id' => 'order_tc16_settle',
                    'amount' => 3776,
                    'status' => 'captured',
                ];
            }
        };
        $api = new class($utility, $paymentApi) {
            public function __construct(public object $utility, public object $payment) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);
        $result = $service->settle($vendor, $renewal, 'pay_tc16_unique', 'sig_tc16_valid');

        $this->assertFalse($result['already_paid']);
        $this->assertSame('paid', $result['renewal']->status);

        $vendor->refresh();
        $this->assertSame(1, (int) $vendor->status);
        $this->assertSame(1, (int) $vendor->activestatus);
        // Expiry must be payment date (2026-10-30) + 28 renewal_days = 2026-11-27
        $this->assertSame('2026-11-27', $vendor->expiryDate->toDateString());

        Carbon::setTestNow();
    }

    /**
     * TC17: Historical paid renewal remains unchanged after a new renewal is created.
     */
    public function test_tc17_historical_paid_renewal_remains_unchanged_after_new_renewal_created()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        $paidTimestamp = Carbon::create(2026, 9, 28, 8, 20, 53, 'Asia/Kolkata');
        $historical = SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'payment_date' => '2026-09-28',
            'status' => 'paid',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'penalty_day' => 0,
            'penalty_principal' => 0.0,
            'penalty_gst' => 0.0,
            'renewal_days' => 30,
            'bonus_days' => 2,
            'total_payable' => 2.0,
            'razorpay_order_id' => 'order_orig_hist_52',
            'payment_id' => 'pay_orig_hist_52',
            'paid_at' => $paidTimestamp,
        ]);

        $orderApi = new class {
            public function create(array $attributes): object {
                return (object) ['id' => 'order_new_subsequent'];
            }
        };
        $api = new class($orderApi) {
            public function __construct(public object $order) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(private object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);
        $result = $service->createOrder($vendor, 'idemp_tc17_new');

        $this->assertFalse($result['already_paid']);
        $this->assertNotEquals($historical->id, $result['renewal']->id);

        $reloaded = $historical->fresh();
        $this->assertSame('paid', $reloaded->status);
        $this->assertSame($vendor->id . ':2026-10-28', $reloaded->cycle_key);
        $this->assertSame(2.0, (float) $reloaded->total_payable);
        $this->assertSame('order_orig_hist_52', $reloaded->razorpay_order_id);
        $this->assertSame('pay_orig_hist_52', $reloaded->payment_id);
        $this->assertSame('2026-09-28', $reloaded->payment_date->toDateString());

        Carbon::setTestNow();
    }

    /**
     * TC18: Multiple requests around midnight do not create duplicate renewal cycles.
     */
    public function test_tc18_multiple_requests_around_midnight_do_not_create_duplicate_renewal_cycles()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 23, 59, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        SubscriptionRenewal::create([
            'subscriber_id' => $vendor->id,
            'cycle_key' => $vendor->id . ':2026-10-28',
            'due_date' => '2026-10-28',
            'status' => 'paid',
            'subscription_price' => 2.0,
            'subscription_gst' => 0.36,
            'total_payable' => 2.0,
            'paid_at' => now(),
        ]);

        $orderApi = new class {
            public int $callCount = 0;
            public function create(array $attributes): object {
                $this->callCount++;
                return (object) ['id' => 'order_midnight_' . $this->callCount];
            }
        };
        $api = new class($orderApi) {
            public function __construct(public object $order) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(public object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);

        // Request 1: 11:59 PM (day 2 of penalty -> ₹37.76)
        $result1 = $service->createOrder($vendor, 'idemp_midnight_1');
        $this->assertSame('order_midnight_1', $result1['renewal']->razorpay_order_id);
        $this->assertSame(37.76, (float) $result1['renewal']->total_payable);

        // Advance 2 minutes across midnight to 12:01 AM (day 3 of penalty -> ₹55.46)
        Carbon::setTestNow(Carbon::create(2026, 10, 31, 0, 1, 0, 'Asia/Kolkata'));

        // Request 2: Reuses the SAME renewal record, updates quote & order for new penalty day
        $result2 = $service->createOrder($vendor, 'idemp_midnight_2');

        $this->assertSame($result1['renewal']->id, $result2['renewal']->id);
        $this->assertSame($result1['renewal']->cycle_key, $result2['renewal']->cycle_key);
        $this->assertSame('order_midnight_2', $result2['renewal']->razorpay_order_id);
        $this->assertSame(55.46, (float) $result2['renewal']->total_payable);

        // Total renewal records for this subscriber is exactly 2 (1 historical paid + 1 current)
        $this->assertSame(2, SubscriptionRenewal::where('subscriber_id', $vendor->id)->count());

        Carbon::setTestNow();
    }

    /**
     * TC19: Concurrent order requests cannot create multiple active orders for the same renewal.
     */
    public function test_tc19_concurrent_order_requests_cannot_create_multiple_active_orders()
    {
        Carbon::setTestNow(Carbon::create(2026, 10, 30, 12, 0, 0, 'Asia/Kolkata'));

        $vendor = $this->createVendor([
            'subscription_price' => '2.00',
            'expiryDate' => '2026-10-28 00:00:00',
        ]);

        $orderApi = new class {
            public int $callCount = 0;
            public function create(array $attributes): object {
                $this->callCount++;
                return (object) ['id' => 'order_concurrent_1'];
            }
        };
        $api = new class($orderApi) {
            public function __construct(public object $order) {}
        };
        $service = new class($api) extends \App\Services\SubscriptionRenewalService {
            public function __construct(public object $api) {}
            protected function makeRazorpayApi(string $key, string $secret): object {
                return $this->api;
            }
        };

        config(['services.razorpay.key_id' => 'test-key', 'services.razorpay.key_secret' => 'test-secret']);

        // First order creation
        $res1 = $service->createOrder($vendor, 'idemp_conc_1');
        // Second concurrent order call for the same renewal
        $res2 = $service->createOrder($vendor, 'idemp_conc_2');

        $this->assertSame(1, $orderApi->callCount);
        $this->assertSame('order_concurrent_1', $res1['renewal']->razorpay_order_id);
        $this->assertSame('order_concurrent_1', $res2['renewal']->razorpay_order_id);
        $this->assertSame($res1['renewal']->id, $res2['renewal']->id);

        Carbon::setTestNow();
    }
}
