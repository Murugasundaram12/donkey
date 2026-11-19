<?php

namespace Tests\Feature\Subscriber;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Employee;
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

    /**
     * TC11: Authenticated subscriber accessing /dashboard is redirected to PBP dashboard.
     */
    public function test_tc11_subscriber_accessing_root_dashboard_redirects_to_pbp_dashboard()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Dashboard Redirect Subscriber',
            'email' => 'dash_sub@test.com',
            'mobile' => '9876543111',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC11',
        ]));

        $response = $this->actingAs($subscriber, 'subscriber')
            ->withSession(['subscribers' => $subscriber])
            ->get('/dashboard');

        $response->assertRedirect(route('subscribers.dashboard'));
    }

    /**
     * TC12: Subscriber credentials on Admin Login form are rejected.
     */
    public function test_tc12_subscriber_credentials_on_admin_login_are_rejected()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Main Login Subscriber',
            'email' => 'main_login_sub@test.com',
            'mobile' => '9876543112',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC12',
        ]));

        $response = $this->post('/login', [
            'email' => 'main_login_sub@test.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC13: Super Admin functionality is preserved at /dashboard and /login.
     */
    public function test_tc13_super_admin_functionality_preserved_at_login_and_dashboard()
    {
        $admin = \App\Models\Admin::find(32);
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin, 'web')->get('/dashboard');
        $response->assertOk();
    }

    /**
     * TC14: Super Admin dashboard is NOT hijacked to subscriber dashboard when subscriber guard is active.
     */
    public function test_tc14_admin_dashboard_not_hijacked_by_subscriber_guard()
    {
        $admin = \App\Models\Admin::find(32);
        $this->assertNotNull($admin);

        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Concurrent Subscriber',
            'email' => 'concurrent_sub@test.com',
            'mobile' => '9876543114',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC14',
        ]));

        Auth::guard('subscriber')->login($subscriber);
        $this->assertTrue(Auth::guard('subscriber')->check());

        $response = $this->actingAs($admin, 'web')->get('/dashboard');
        $response->assertOk();
    }

    /**
     * TC15: Admin login clears any leftover subscriber guard and routes to admin dashboard.
     */
    public function test_tc15_admin_login_clears_subscriber_session_and_routes_to_admin_dashboard()
    {
        $admin = \App\Models\Admin::find(32);
        $this->assertNotNull($admin);
        $admin->password = Hash::make('adminpass123');
        $admin->save();

        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Leftover Subscriber',
            'email' => 'leftover_sub@test.com',
            'mobile' => '9876543115',
            'password' => Hash::make('password123'),
            'subscriberId' => 'SUB-TC15',
        ]));

        Auth::guard('subscriber')->login($subscriber);
        session(['subscribers' => $subscriber]);
        $this->assertTrue(Auth::guard('subscriber')->check());

        $response = $this->post('/login', [
            'email' => 'Kesav2226@gmail.com',
            'password' => 'adminpass123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC16: Stale subscriber intended URL does not override Admin dashboard destination.
     */
    public function test_tc16_stale_subscriber_intended_url_does_not_override_admin_dashboard()
    {
        $admin = \App\Models\Admin::find(32);
        $this->assertNotNull($admin);
        $admin->password = Hash::make('adminpass123');
        $admin->save();

        $response = $this->withSession(['url.intended' => 'https://donkeydeliveries.com/donkey/subscribers/dashboard'])
            ->post('/login', [
                'email' => 'Kesav2226@gmail.com',
                'password' => 'adminpass123',
            ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::guard('web')->check());
    }

    /**
     * TC17: Invalid login credentials reject authentication.
     */
    public function test_tc17_invalid_admin_login_rejects_authentication()
    {
        $response = $this->post('/login', [
            'email' => 'Kesav2226@gmail.com',
            'password' => 'completely_wrong_password_xyz',
        ]);

        $response->assertSessionHasErrors();
        $this->assertFalse(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC18: Unauthenticated access to /dashboard redirects to /login.
     */
    public function test_tc18_unauthenticated_user_accessing_dashboard_redirects_to_login()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));
    }

    /**
     * TC19: Admin logout flushes session and allows logging in again to admin dashboard.
     */
    public function test_tc19_admin_logout_and_login_again_routes_to_admin_dashboard()
    {
        $admin = \App\Models\Admin::find(32);
        $this->assertNotNull($admin);
        $admin->password = Hash::make('adminpass123');
        $admin->save();

        // 1. Initial Login
        $this->post('/login', [
            'email' => 'Kesav2226@gmail.com',
            'password' => 'adminpass123',
        ])->assertRedirect(route('dashboard'));

        $this->assertTrue(Auth::guard('web')->check());

        // 2. Logout
        $this->post('/logout');
        $this->assertFalse(Auth::guard('web')->check());

        // 3. Login again
        $response = $this->post('/login', [
            'email' => 'Kesav2226@gmail.com',
            'password' => 'adminpass123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('subscriber')->check());
    }

    /**
     * TC20: Valid Employee login is preserved via dedicated subscriber login flow.
     */
    public function test_tc20_employee_login_preserved_via_subscriber_login_flow()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Emp Parent Subscriber',
            'email' => 'parent_sub_tc20@test.com',
            'mobile' => '9876543120',
            'password' => Hash::make('parentpass123'),
            'subscriberId' => 'SUB-TC20',
        ]));

        $employee = Employee::create([
            'name' => 'Sub Employee TC20',
            'email' => 'emp_tc20@test.com',
            'emp_id' => 'EMP-TC20-001',
            'official_mail' => 'emp_tc20@test.com',
            'mobile' => '9123456720',
            'official_mobile' => '9123456720',
            'password' => Hash::make('emppassword123'),
            'address' => 'Test Address',
            'aadhar' => '123456789012',
            'subscriber_id' => $subscriber->id,
        ]);

        $response = $this->post('/subscribers/loginaction', [
            'email' => 'emp_tc20@test.com',
            'password' => 'emppassword123',
        ]);

        $response->assertRedirect(route('subscribers.dashboard'));
        $this->assertTrue(Auth::guard('employee')->check());
        $this->assertEquals($employee->id, Auth::guard('employee')->id());
    }

    /**
     * TC21: Employee credentials on Admin Login form are rejected.
     */
    public function test_tc21_employee_credentials_on_admin_login_are_rejected()
    {
        $subscriber = Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Emp Parent Subscriber 21',
            'email' => 'parent_sub_tc21@test.com',
            'mobile' => '9876543121',
            'password' => Hash::make('parentpass123'),
            'subscriberId' => 'SUB-TC21',
        ]));

        $employee = Employee::create([
            'name' => 'Sub Employee TC21',
            'email' => 'emp_tc21@test.com',
            'emp_id' => 'EMP-TC21-001',
            'official_mail' => 'emp_tc21@test.com',
            'mobile' => '9123456721',
            'official_mobile' => '9123456721',
            'password' => Hash::make('emppassword123'),
            'address' => 'Test Address',
            'aadhar' => '123456789012',
            'subscriber_id' => $subscriber->id,
        ]);

        $response = $this->post('/login', [
            'email' => 'emp_tc21@test.com',
            'password' => 'emppassword123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::guard('web')->check());
        $this->assertFalse(Auth::guard('employee')->check());
        $this->assertFalse(Auth::guard('subscriber')->check());
    }
}

