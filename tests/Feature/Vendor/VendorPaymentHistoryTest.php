<?php

namespace Tests\Feature\Vendor;

use App\Models\PaymentDetails;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorPaymentHistoryTest extends TestCase
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
            'location' => 'Location Test',
            'subscriptionDate' => '2025-01-01 00:00:00',
            'expiryDate' => '2030-12-31 23:59:59',
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

    private function createPayment(Subscriber $vendor, array $overrides = []): PaymentDetails
    {
        static $payCount = 1;
        $idx = $payCount++ . '_' . time() . '_' . mt_rand(100, 999);

        return PaymentDetails::create(array_merge([
            'invoice_no' => "PBPS-{$idx}",
            'type' => 1,
            'subscriberId' => $vendor->subscriberId,
            'payment_id' => "pay_test_{$idx}",
            'status_code' => '200',
            'amount' => '99900', // 999.00 in paise
            'signature' => "sig_secret_{$idx}",
            'invoice_id' => "inv_{$idx}",
            'created_at' => now(),
            'updated_at' => now(),
        ], $overrides));
    }

    public function test_authenticated_vendor_can_retrieve_own_payment_history()
    {
        $vendor = $this->createVendor();
        $payment = $this->createPayment($vendor, [
            'amount' => '150000', // ₹1,500.00
            'status_code' => '200',
            'type' => 1,
        ]);

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'message' => 'Payments retrieved successfully',
            ])
            ->assertJsonStructure([
                'status',
                'success',
                'message',
                'data' => [
                    'payments',
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ]);

        $payments = $response->json('data.payments');
        $this->assertNotEmpty($payments);

        $found = collect($payments)->firstWhere('id', $payment->id);
        $this->assertNotNull($found);
        $this->assertEquals(1500.00, $found['amount']);
        $this->assertEquals('Razorpay', $found['payment_method']);
        $this->assertEquals($payment->payment_id, $found['transaction_id']);
        $this->assertEquals('success', $found['status']);
        $this->assertEquals('Subscription', $found['plan']);
        // Verify data.items is not present and only data.payments exists
        $this->assertArrayNotHasKey('items', $response->json('data'), 'data.items should not be present in response');
        $this->assertArrayHasKey('payments', $response->json('data'), 'data.payments must be present in response');

        // Verify sensitive signature is NOT exposed
        $this->assertArrayNotHasKey('signature', $found);
    }

    public function test_payment_history_contains_only_that_vendors_payments()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();

        $paymentA = $this->createPayment($vendorA);
        $paymentB = $this->createPayment($vendorB);

        $tokenA = $vendorA->createToken('test')->plainTextToken;

        $responseA = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/payments');

        $responseA->assertStatus(200);
        $paymentsA = collect($responseA->json('data.payments'));

        $this->assertTrue($paymentsA->contains('id', $paymentA->id));
        $this->assertFalse($paymentsA->contains('id', $paymentB->id));
    }

    public function test_payments_are_ordered_newest_first()
    {
        $vendor = $this->createVendor();

        $older = $this->createPayment($vendor, [
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);
        $newer = $this->createPayment($vendor, [
            'created_at' => Carbon::now()->subHour(),
            'updated_at' => Carbon::now()->subHour(),
        ]);

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments');

        $response->assertStatus(200);
        $payments = $response->json('data.payments');

        $this->assertGreaterThanOrEqual(2, count($payments));

        // Find positions of newer and older in the returned payments list
        $newerIndex = null;
        $olderIndex = null;
        foreach ($payments as $index => $item) {
            if ($item['id'] === $newer->id) {
                $newerIndex = $index;
            }
            if ($item['id'] === $older->id) {
                $olderIndex = $index;
            }
        }

        $this->assertNotNull($newerIndex);
        $this->assertNotNull($olderIndex);
        $this->assertLessThan($olderIndex, $newerIndex, 'Newer payment must precede older payment');
    }

    public function test_pagination_works()
    {
        $vendor = $this->createVendor();

        // Create 3 payments for this vendor
        for ($i = 0; $i < 3; $i++) {
            $this->createPayment($vendor, [
                'created_at' => Carbon::now()->subMinutes($i),
            ]);
        }

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments?per_page=2&page=1');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'current_page' => 1,
                    'per_page' => 2,
                    'total' => 3,
                    'last_page' => 2,
                ],
            ]);

        $this->assertCount(2, $response->json('data.payments'));
    }

    public function test_vendor_can_retrieve_one_of_their_own_payment_details()
    {
        $vendor = $this->createVendor();
        $payment = $this->createPayment($vendor, [
            'amount' => '250000',
            'type' => 2, // Custom Subscription
            'status_code' => '200',
        ]);

        $token = $vendor->createToken('test')->plainTextToken;

        // By numeric ID
        $responseById = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments/' . $payment->id);

        $responseById->assertStatus(200)
            ->assertJson([
                'status' => true,
                'success' => true,
                'message' => 'Payment details retrieved successfully',
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                        'amount' => 2500.00,
                        'payment_method' => 'Razorpay',
                        'transaction_id' => $payment->payment_id,
                        'status' => 'success',
                        'plan' => 'Custom Subscription',
                    ],
                ],
            ]);

        // By Razorpay payment_id string
        $responseByPaymentId = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments/' . $payment->payment_id);

        $responseByPaymentId->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'payment' => [
                        'id' => $payment->id,
                    ],
                ],
            ]);
    }

    public function test_vendor_cannot_retrieve_another_vendors_payment()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();

        $paymentB = $this->createPayment($vendorB);

        $tokenA = $vendorA->createToken('test')->plainTextToken;

        // Vendor A attempts to access Vendor B's payment
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/payments/' . $paymentB->id);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'success' => false,
                'message' => 'Payment record not found or access denied.',
            ]);
    }

    public function test_non_existing_payment_returns_correct_404_response()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/payments/99999999');

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'success' => false,
                'message' => 'Payment record not found or access denied.',
            ]);
    }

    public function test_unauthenticated_request_is_rejected()
    {
        $responseIndex = $this->getJson('/api/vendor/payments');
        $responseIndex->assertStatus(401);

        $responseShow = $this->getJson('/api/vendor/payments/1');
        $responseShow->assertStatus(401);
    }

    public function test_existing_booking_payment_apis_remain_unchanged()
    {
        // Verify external/internal booking payment endpoints exist and are unaffected
        $response = $this->postJson('/api/payment_status', []);
        // Should return standard validation error or missing param response, not 404
        $this->assertNotEquals(404, $response->status());
    }
}
