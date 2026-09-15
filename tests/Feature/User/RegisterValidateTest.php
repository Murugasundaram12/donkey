<?php

namespace Tests\Feature\User;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RegisterValidateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * 10. Route exists as POST /api/registerValidate.
     */
    public function test_route_exists_as_post_api_register_validate(): void
    {
        $this->assertTrue(Route::has('api.registerValidate'));
        $route = Route::getRoutes()->getByName('api.registerValidate');
        $this->assertNotNull($route);
        $this->assertEquals(['POST'], $route->methods());
        $this->assertEquals('api/registerValidate', $route->uri());
    }

    /**
     * 1. Valid Indian registration validation succeeds.
     */
    public function test_valid_indian_registration_validation_succeeds(): void
    {
        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Valid User',
            'email' => 'valid_user_' . time() . '@example.com',
            'phone' => '987' . rand(1000000, 9999999),
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => '+91',
            'address1' => '123 Main St',
            'address2' => 'Apt 4B',
            'device_token' => 'sample_token_123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Validation Cleared Successfully',
            ]);

        // Assert that 'data' is a JSON object and NOT a string
        $this->assertIsArray($response->json('data'));
        $this->assertIsNotString($response->json('data'));
        $this->assertArrayHasKey('otp', $response->json('data'));
    }

    /**
     * 2. Valid validation does NOT create a users row.
     */
    public function test_valid_validation_does_not_create_users_row(): void
    {
        $uniqueEmail = 'not_persisted_' . time() . '@example.com';
        $uniquePhone = '999' . rand(1000000, 9999999);

        $beforeCount = User::where('email', $uniqueEmail)->orWhere('phone', $uniquePhone)->count();
        $this->assertEquals(0, $beforeCount);

        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Ephemeral User',
            'email' => $uniqueEmail,
            'phone' => $uniquePhone,
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => '+91',
        ]);

        $response->assertStatus(200)->assertJson(['success' => true]);

        $afterCount = User::where('email', $uniqueEmail)->orWhere('phone', $uniquePhone)->count();
        $this->assertEquals(0, $afterCount, 'registerValidate MUST NOT persist a User record');
    }

    /**
     * 3. Duplicate email is rejected.
     */
    public function test_duplicate_email_is_rejected(): void
    {
        $existingEmail = 'existing_' . time() . '@example.com';
        User::create([
            'user_id' => 'DK-' . uniqid(),
            'name' => 'Existing User',
            'email' => $existingEmail,
            'phone' => '911' . rand(1000000, 9999999),
            'password' => bcrypt('password'),
            'is_live' => 1,
        ]);

        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Another User',
            'email' => $existingEmail,
            'phone' => '922' . rand(1000000, 9999999),
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => '+91',
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Email address already registered',
            ]);
    }

    /**
     * 4. Duplicate phone is rejected.
     */
    public function test_duplicate_phone_is_rejected(): void
    {
        $existingPhone = '933' . rand(1000000, 9999999);
        User::create([
            'user_id' => 'DK-' . uniqid(),
            'name' => 'Phone User',
            'email' => 'phone_user_' . time() . '@example.com',
            'phone' => $existingPhone,
            'password' => bcrypt('password'),
            'is_live' => 1,
        ]);

        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Another User',
            'email' => 'different_' . time() . '@example.com',
            'phone' => $existingPhone,
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => '+91',
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'success' => false,
                'message' => 'Phone number address already registered',
            ]);
    }

    /**
     * 5. Password confirmation mismatch is rejected.
     */
    public function test_password_confirmation_mismatch_is_rejected(): void
    {
        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Mismatch User',
            'email' => 'mismatch_' . time() . '@example.com',
            'phone' => '944' . rand(1000000, 9999999),
            'password' => 'secret123',
            'c_password' => 'different_password',
            'country_code' => '+91',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
            ]);
        $this->assertArrayHasKey('c_password', $response->json('data'));
    }

    /**
     * 6. Missing required registration data is rejected.
     */
    public function test_missing_required_registration_data_is_rejected(): void
    {
        // Missing name and password, missing both email and phone
        $response = $this->postJson('/api/registerValidate', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
            ]);
        $errors = $response->json('data');
        $this->assertArrayHasKey('name', $errors);
        $this->assertArrayHasKey('password', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('phone', $errors);
    }

    /**
     * 7. Existing user registration endpoint /api/register still works unchanged.
     */
    public function test_existing_user_registration_endpoint_works_unchanged(): void
    {
        $email = 'registered_' . time() . '@example.com';
        $phone = '955' . rand(1000000, 9999999);

        $response = $this->postJson('/api/register', [
            'name' => 'Newly Registered User',
            'email' => $email,
            'phone' => $phone,
            'password' => 'password123',
            'c_password' => 'password123',
            'address1' => 'Street 1',
            'address2' => 'Street 2',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User register successfully.',
            ]);

        // Explicitly assert that 'data' is a JSON object and NOT a string
        $this->assertIsArray($response->json('data'));
        $this->assertIsNotString($response->json('data'));
        $this->assertArrayHasKey('token', $response->json('data'));
        $this->assertArrayHasKey('name', $response->json('data'));
        $this->assertArrayHasKey('user_id', $response->json('data'));
        $this->assertArrayHasKey('id', $response->json('data'));

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'phone' => $phone,
        ]);
    }

    /**
     * 8. Non-+91 OTP behavior works using the existing OTP infrastructure.
     */
    public function test_non_plus_91_otp_behavior_works_and_dispatches_otp_mail(): void
    {
        Mail::fake();

        $intlEmail = 'intl_user_' . time() . '@example.com';
        $response = $this->postJson('/api/registerValidate', [
            'name' => 'International User',
            'email' => $intlEmail,
            'phone' => '12025550199',
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => '+1',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Validation Cleared Successfully',
            ]);

        $otp = $response->json('data.otp');
        $this->assertNotEmpty($otp);
        $this->assertMatchesRegularExpression('/^\d{4}$/', (string) $otp);

        Mail::assertSent(OtpMail::class, function ($mail) use ($intlEmail) {
            return $mail->hasTo($intlEmail);
        });
    }

    /**
     * 9. Invalid country_code handling.
     */
    public function test_invalid_country_code_handling(): void
    {
        $response = $this->postJson('/api/registerValidate', [
            'name' => 'Bad Country Code User',
            'email' => 'bad_cc_' . time() . '@example.com',
            'phone' => '966' . rand(1000000, 9999999),
            'password' => 'secret123',
            'c_password' => 'secret123',
            'country_code' => 'INVALID_CODE',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation Error.',
            ]);
        $this->assertArrayHasKey('country_code', $response->json('data'));
    }
}
