<?php

namespace Tests\Feature\Subscriber;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SubscriberWebLoginTest extends TestCase
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

    /**
     * TC01: Plain-text password subscriber can log in.
     */
    public function test_tc01_plaintext_password_subscriber_can_login()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Plaintext Subscriber',
            'email' => 'plain_sub@test.com',
            'mobile' => '9876543101',
            'password' => 'plainpassword123',
            'subscriberId' => 'SUB-TC01',
        ]));

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'plain_sub@test.com',
            'password' => 'plainpassword123',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));
        $this->assertTrue(Auth::guard('subscriber')->check());
        $this->assertEquals($subscriber->id, Auth::guard('subscriber')->id());
    }

    /**
     * TC02: Bcrypt password subscriber can log in.
     */
    public function test_tc02_bcrypt_password_subscriber_can_login()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Bcrypt Subscriber',
            'email' => 'bcrypt_sub@test.com',
            'mobile' => '9876543102',
            'password' => Hash::make('bcryptpassword123'),
            'subscriberId' => 'SUB-TC02',
        ]));

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'bcrypt_sub@test.com',
            'password' => 'bcryptpassword123',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));
        $this->assertTrue(Auth::guard('subscriber')->check());
        $this->assertEquals($subscriber->id, Auth::guard('subscriber')->id());
    }

    /**
     * TC03: Incorrect password is rejected.
     */
    public function test_tc03_incorrect_password_is_rejected()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Reject Subscriber',
            'email' => 'reject_sub@test.com',
            'mobile' => '9876543103',
            'password' => Hash::make('correctpassword'),
            'subscriberId' => 'SUB-TC03',
        ]));

        $response = $this->from('/subscribers/login')->post('/subscribers/loginaction', [
            'email' => 'reject_sub@test.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect('/subscribers/login');
        $response->assertSessionHas('error', 'Wrong Credentials');
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC04: Expired subscriber can log in and access renewal page.
     */
    public function test_tc04_expired_subscriber_can_login_and_access_renewal_page()
    {
        $expiredDate = Carbon::now()->subDays(5)->format('Y-m-d H:i:s');
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Web Subscriber',
            'email' => 'expired_web@test.com',
            'mobile' => '9876543104',
            'password' => 'expiredpass123',
            'subscriberId' => 'SUB-TC04',
            'expiryDate' => $expiredDate,
            'status' => 1,
            'platform_fee' => 2.00,
        ]));

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'expired_web@test.com',
            'password' => 'expiredpass123',
        ]);

        // Authenticated and redirected to renewal/payment page
        $response->assertRedirect(route('makePlatFormFee', ['Id' => $subscriber->id]));
        $this->assertTrue(Auth::guard('subscriber')->check());

        // Access the renewal page
        $renewalPageResponse = $this->get(route('makePlatFormFee', ['Id' => $subscriber->id]));
        $renewalPageResponse->assertStatus(200);
    }

    /**
     * TC05: Expired subscriber status is not changed to 0.
     */
    public function test_tc05_expired_subscriber_status_is_not_changed_to_zero()
    {
        $expiredDate = Carbon::now()->subDays(3)->format('Y-m-d H:i:s');
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Status Retain Subscriber',
            'email' => 'retain_status@test.com',
            'mobile' => '9876543105',
            'password' => 'password123',
            'subscriberId' => 'SUB-TC05',
            'expiryDate' => $expiredDate,
            'status' => 1,
        ]));

        $this->post('/subscribers/loginaction', [
            'email' => 'retain_status@test.com',
            'password' => 'password123',
        ]);

        $subscriber->refresh();
        $this->assertEquals(1, (int) $subscriber->status);
    }

    /**
     * TC06: Blocked subscriber cannot log in.
     */
    public function test_tc06_blocked_subscriber_cannot_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Blocked Subscriber',
            'email' => 'blocked_sub@test.com',
            'mobile' => '9876543106',
            'password' => 'password123',
            'subscriberId' => 'SUB-TC06',
            'blockedstatus' => 0,
        ]));

        $response = $this->from('/subscribers/login')->post('/subscribers/loginaction', [
            'email' => 'blocked_sub@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/subscribers/login');
        $response->assertSessionHas('error', 'You have been  blocked.Please Contact admin!');
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC07: Inactive subscriber cannot log in.
     */
    public function test_tc07_inactive_subscriber_cannot_login()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Inactive Subscriber',
            'email' => 'inactive_sub@test.com',
            'mobile' => '9876543107',
            'password' => 'password123',
            'subscriberId' => 'SUB-TC07',
            'status' => 0,
        ]));

        $response = $this->from('/subscribers/login')->post('/subscribers/loginaction', [
            'email' => 'inactive_sub@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/subscribers/login');
        $response->assertSessionHas('error', 'Your Subscriber Admin not activated.Please Contact your admin!');
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC08: Existing active subscriber dashboard navigation works.
     */
    public function test_tc08_existing_active_subscriber_dashboard_navigation_works()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Active Nav Subscriber',
            'email' => 'active_nav@test.com',
            'mobile' => '9876543108',
            'password' => 'password123',
            'subscriberId' => 'SUB-TC08',
            'expiryDate' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
            'status' => 1,
        ]));

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'active_nav@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));
        $this->assertTrue(Auth::guard('subscriber')->check());

        // Access dashboard
        $dashboardResponse = $this->get(route('subscribers.dashboard'));
        $dashboardResponse->assertStatus(200);
    }

    /**
     * TC09: Vendor API login remains functional.
     */
    public function test_tc09_vendor_api_login_remains_functional()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'API Vendor Test',
            'email' => 'api_vendor_tc09@test.com',
            'mobile' => '9876543109',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC09',
        ]));

        $response = $this->postJson('/api/vendor/login', [
            'login' => 'api_vendor_tc09@test.com',
            'password' => 'password123',
            'device_token' => 'TOKEN_TC09',
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
                        'email',
                    ]
                ]
            ]);
    }

    /**
     * TC10: Vendor expiry middleware continues blocking protected business APIs.
     */
    public function test_tc10_vendor_expiry_middleware_continues_blocking_protected_business_apis()
    {
        Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Expired Vendor TC10',
            'email' => 'expired_tc10@test.com',
            'mobile' => '9876543110',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC10',
            'expiryDate' => '2020-01-01 23:59:59',
            'status' => 1,
        ]));

        $loginResponse = $this->postJson('/api/vendor/login', [
            'login' => 'expired_tc10@test.com',
            'password' => 'password123',
            'device_token' => 'TOKEN_TC10',
        ]);

        $token = $loginResponse->json('data.token');
        $this->assertNotEmpty($token);

        // Protected API must return 403
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/bookings');

        $response->assertStatus(403)
            ->assertJson([
                'status' => false,
                'message' => 'Your subscription/payment has expired. Please renew your payment.',
            ]);
    }
}

