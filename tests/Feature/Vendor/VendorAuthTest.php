<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class VendorAuthTest extends TestCase
{
    use DatabaseTransactions;

    private array $defaultVendorData = [
        'location' => 'Location Test',
        'subscriptionDate' => '2025-01-01 00:00:00',
        'expiryDate' => '2030-12-31 23:59:59',
        'status' => 1,
        'blockedstatus' => 1,
        'pincode' => '["600001"]',
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
    ];

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_vendor_login_successful_with_valid_credentials()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor',
            'email' => 'vendor@test.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB1001',
            'device_token' => 'OLD_TOKEN_ABC',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor@test.com',
            'password' => 'password123',
            'device_token' => 'OLD_TOKEN_ABC',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ])
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'token_type',
                    'vendor' => [
                        'id',
                        'subscriber_id',
                        'name',
                        'email',
                        'mobile',
                    ]
                ]
            ]);
    }

    public function test_vendor_login_updates_different_device_token_without_mismatch_error()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Token Vendor',
            'email' => 'tokenvendor@test.com',
            'mobile' => '9876543219',
            'password' => Hash::make('password123'),
            'device_token' => 'OLD_TOKEN_ABC',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'tokenvendor@test.com',
            'password' => 'password123',
            'device_token' => 'NEW_TOKEN_XYZ',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);

        $vendor->refresh();
        $this->assertEquals('NEW_TOKEN_XYZ', $vendor->device_token);
    }

    public function test_vendor_login_fails_without_device_token()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'No Token Vendor',
            'email' => 'notoken@test.com',
            'mobile' => '9876543218',
            'password' => Hash::make('password123'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'notoken@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_vendor_login_fails_with_invalid_credentials()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor',
            'email' => 'vendor2@test.com',
            'mobile' => '9876543211',
            'password' => Hash::make('password123'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor2@test.com',
            'password' => 'wrongpassword',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_blocked_vendor_cannot_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Blocked Vendor',
            'email' => 'blocked@test.com',
            'mobile' => '9876543212',
            'password' => Hash::make('password123'),
            'blockedstatus' => 0,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'blocked@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your account has been blocked. Please contact admin.',
            ]);
    }

    public function test_authenticated_vendor_can_fetch_profile()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Active Vendor',
            'email' => 'active@test.com',
            'mobile' => '9876543213',
            'password' => Hash::make('password123'),
        ]));

        $token = $vendor->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'vendor' => [
                        'email' => 'active@test.com',
                    ]
                ]
            ]);
    }

    public function test_expired_vendor_can_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor',
            'email' => 'expired@test.com',
            'mobile' => '9876543299',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'expired@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token_type' => 'Bearer',
                    'vendor' => [
                        'payment_status' => 0,
                    ],
                ],
            ]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_login_rejects_inactive_status_fields()
    {
        foreach (['status' => ['email' => 'status-inactive@test.com', 'mobile' => '9876543295'], 'activestatus' => ['email' => 'active-status-inactive@test.com', 'mobile' => '9876543296']] as $field => $identity) {
            Subscriber::create(array_merge($this->defaultVendorData, [
                'name' => 'Inactive Vendor',
                'email' => $identity['email'],
                'mobile' => $identity['mobile'],
                'password' => Hash::make('password123'),
                $field => 0,
            ]));

            $this->postJson('/api/vendor/login', [
                'login' => $identity['email'],
                'password' => 'password123',
                'device_token' => 'TEST_TOKEN_123',
            ])->assertStatus(403);
        }
    }

    public function test_otp_login_rejects_blocked_and_inactive_vendors_but_allows_expired()
    {
        $rejectedCases = [
            ['email' => 'otp-blocked@test.com', 'mobile' => '9876543294', 'blockedstatus' => 0],
            ['email' => 'otp-status@test.com', 'mobile' => '9876543293', 'status' => 0],
            ['email' => 'otp-active-status@test.com', 'mobile' => '9876543292', 'activestatus' => 0],
        ];

        foreach ($rejectedCases as $case) {
            Subscriber::create(array_merge($this->defaultVendorData, [
                'name' => 'OTP Vendor',
                'password' => Hash::make('password123'),
                'notify' => '1234',
            ], $case));

            $this->postJson('/api/vendor/otp/verify', [
                'mobile' => $case['mobile'],
                'otp' => '1234',
            ])->assertStatus(403);
        }

        // Expired vendor can verify OTP and log in
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'OTP Expired Vendor',
            'email' => 'otp-expired@test.com',
            'mobile' => '9876543291',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
            'notify' => '1234',
        ]));

        $this->postJson('/api/vendor/otp/verify', [
            'mobile' => '9876543291',
            'otp' => '1234',
        ])->assertStatus(200)
          ->assertJson([
              'status' => true,
              'message' => 'OTP verified successfully',
              'data' => [
                  'token_type' => 'Bearer',
              ]
          ]);
    }

    /**
     * TC01: Active vendor login -> HTTP 200 and token.
     */
    public function test_tc01_active_vendor_login_returns_200_and_token()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Active Vendor TC01',
            'email' => 'active_tc01@test.com',
            'mobile' => '9876543201',
            'password' => Hash::make('password123'),
            'expiryDate' => Carbon::now()->addDays(20)->format('Y-m-d H:i:s'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'active_tc01@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token_type' => 'Bearer',
                    'vendor' => [
                        'payment_status' => 1,
                    ],
                ],
            ]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * TC02: Expired vendor login -> HTTP 200 and token.
     */
    public function test_tc02_expired_vendor_login_returns_200_and_token()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC02',
            'email' => 'expired_tc02@test.com',
            'mobile' => '9876543202',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc02@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC02',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'token_type' => 'Bearer',
                    'vendor' => [
                        'payment_status' => 0,
                    ],
                ],
            ]);
        $this->assertNotEmpty($response->json('data.token'));
    }

    /**
     * TC03: Expired vendor can access subscription-payment API.
     */
    public function test_tc03_expired_vendor_can_access_subscription_payment_api()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC03',
            'email' => 'expired_tc03@test.com',
            'mobile' => '9876543203',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc03@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC03',
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/subscription-payment');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'payment_type' => 'Subscription',
                    'payment_status' => 0,
                ],
            ]);
    }

    /**
     * TC04: Expired vendor can create a Razorpay order using sandbox/test configuration.
     */
    public function test_tc04_expired_vendor_can_create_razorpay_order_using_sandbox_test_config()
    {
        config([
            'services.razorpay.key_id' => 'rzp_test_mock_key',
            'services.razorpay.key_secret' => 'rzp_test_mock_secret',
        ]);

        $mockApi = new class {
            public object $order;
            public function __construct() {
                $this->order = new class {
                    public function create(array $params): object {
                        return (object) ['id' => 'order_mock_tc04'];
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

        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC04',
            'email' => 'expired_tc04@test.com',
            'mobile' => '9876543204',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc04@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC04',
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/subscription-payment/order');

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'razorpay_order_id' => 'order_mock_tc04',
                ],
            ]);
    }

    /**
     * TC05: Expired vendor cannot access protected booking APIs before renewal.
     */
    public function test_tc05_expired_vendor_cannot_access_protected_booking_apis_before_renewal()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC05',
            'email' => 'expired_tc05@test.com',
            'mobile' => '9876543205',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc05@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC05',
        ]);
        $token = $loginResponse->json('data.token');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings');

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your subscription/payment has expired. Please renew your payment.',
            ]);
    }

    /**
     * TC06: After verified renewal, vendor can access protected booking APIs.
     */
    public function test_tc06_after_verified_renewal_vendor_can_access_protected_booking_apis()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC06',
            'email' => 'expired_tc06@test.com',
            'mobile' => '9876543206',
            'password' => Hash::make('password123'),
            'expiryDate' => '2020-01-01 23:59:59',
        ]));

        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc06@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_TC06',
        ]);
        $token = $loginResponse->json('data.token');

        // Blocked before renewal
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings')
            ->assertStatus(403);

        // Simulate renewal by updating expiry to future date and status to 1
        $vendor->update([
            'expiryDate' => Carbon::now()->addDays(28)->format('Y-m-d H:i:s'),
            'status' => 1,
            'activestatus' => 1,
        ]);

        app('auth')->forgetGuards();

        // After verified renewal, booking API is accessible
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings');

        $response->assertStatus(200);
    }

    /**
     * TC07: Invalid credentials still return authentication failure.
     */
    public function test_tc07_invalid_credentials_still_return_authentication_failure()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Vendor TC07',
            'email' => 'vendor_tc07@test.com',
            'mobile' => '9876543207',
            'password' => Hash::make('correct_password'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'vendor_tc07@test.com',
            'password' => 'wrong_password',
            'device_token' => 'TEST_TOKEN_TC07',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => false,
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_vendor_expiring_today_can_login()
    {
        $todayStr = Carbon::now()->format('Y-m-d 23:59:59');
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Today Vendor',
            'email' => 'today@test.com',
            'mobile' => '9876543298',
            'password' => Hash::make('password123'),
            'expiryDate' => $todayStr,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'today@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_without_expiry_date_can_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'No Expiry Vendor',
            'email' => 'noexpiry@test.com',
            'mobile' => '9876543297',
            'password' => Hash::make('password123'),
            'expiryDate' => null,
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'noexpiry@test.com',
            'password' => 'password123',
            'device_token' => 'TEST_TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_logout_revokes_token()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Logout Vendor',
            'email' => 'logout@test.com',
            'mobile' => '9876543214',
            'password' => Hash::make('password123'),
        ]));

        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/logout');

        $response->assertStatus(200)
            ->assertJson(['status' => true]);
    }

    public function test_vendor_login_with_plain_text_password()
    {
        $vendor = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Plaintext Vendor',
            'email' => 'plain@test.com',
            'mobile' => '9876543301',
            'password' => 'secret123', // unhashed legacy
            'subscriberId' => 'SUB-PLAIN-01',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'plain@test.com',
            'password' => 'secret123',
            'device_token' => 'TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_login_with_mobile_variations()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Mobile Vendor',
            'email' => 'mobilevar@test.com',
            'mobile' => '9876543302',
            'password' => Hash::make('password123'),
        ]));

        $variations = [
            '9876543302',
            '+919876543302',
            '919876543302',
            '09876543302',
            '+91 98765 43302',
            ' 9876543302 ',
        ];

        foreach ($variations as $var) {
            $response = $this->postJson('/api/vendor/login', [
                'login' => $var,
                'password' => 'password123',
                'device_token' => 'TOKEN_123',
            ]);

            $response->assertStatus(200, "Failed logging in with mobile variation: {$var}")
                ->assertJson([
                    'status' => true,
                    'message' => 'Login successful',
                ]);
        }
    }

    public function test_vendor_login_with_subscriber_id()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'SubId Vendor',
            'email' => 'subid@test.com',
            'mobile' => '9876543303',
            'password' => Hash::make('password123'),
            'subscriberId' => 'DKS-TEST-SUB-99',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => '  DKS-TEST-SUB-99  ',
            'password' => 'password123',
            'device_token' => 'TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_vendor_login_with_alternate_field_names()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Alt Field Vendor',
            'email' => 'altfield@test.com',
            'mobile' => '9876543304',
            'password' => Hash::make('password123'),
        ]));

        // Using "mobile" key with +91
        $res1 = $this->postJson('/api/vendor/login', [
            'mobile' => '+919876543304',
            'password' => 'password123',
            'device_token' => 'TOKEN_123',
        ]);
        $res1->assertStatus(200)->assertJson(['status' => true]);

        // Using "phone" key
        $res2 = $this->postJson('/api/vendor/login', [
            'phone' => '9876543304',
            'password' => 'password123',
            'device_token' => 'TOKEN_123',
        ]);
        $res2->assertStatus(200)->assertJson(['status' => true]);

        // Using "email" key with spaces
        $res3 = $this->postJson('/api/vendor/login', [
            'email' => '  altfield@test.com  ',
            'password' => 'password123',
            'device_token' => 'TOKEN_123',
        ]);
        $res3->assertStatus(200)->assertJson(['status' => true]);
    }

    public function test_vendor_login_with_trailing_whitespace_in_password()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Whitespace Pwd Vendor',
            'email' => 'wspwd@test.com',
            'mobile' => '9876543305',
            'password' => Hash::make('securepwd1'),
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'wspwd@test.com',
            'password' => 'securepwd1 ', // trailing space from mobile keyboard
            'device_token' => 'TOKEN_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Login successful',
            ]);
    }
}
