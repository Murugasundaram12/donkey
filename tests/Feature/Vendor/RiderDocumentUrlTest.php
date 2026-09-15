<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class RiderDocumentUrlTest extends TestCase
{
    use DatabaseTransactions;

    private string $testAadharDir;
    private string $testAadharFile = 'test_rider_aadhar_sample.pdf';
    private string $testProfileDir;
    private string $testProfileFile = 'test_rider_profile_sample.jpg';

    protected function setUp(): void
    {
        parent::setUp();

        $this->testAadharDir = public_path('subscriber/driver/aadhar');
        if (!File::exists($this->testAadharDir)) {
            File::makeDirectory($this->testAadharDir, 0755, true);
        }
        File::put($this->testAadharDir . DIRECTORY_SEPARATOR . $this->testAadharFile, "%PDF-1.4\n%sample rider test pdf\n%%EOF");

        $this->testProfileDir = public_path('subscriber/driver/profile');
        if (!File::exists($this->testProfileDir)) {
            File::makeDirectory($this->testProfileDir, 0755, true);
        }
        File::put($this->testProfileDir . DIRECTORY_SEPARATOR . $this->testProfileFile, "dummy image content");
    }

    protected function tearDown(): void
    {
        $aadharPath = $this->testAadharDir . DIRECTORY_SEPARATOR . $this->testAadharFile;
        if (File::exists($aadharPath)) {
            File::delete($aadharPath);
        }

        $profilePath = $this->testProfileDir . DIRECTORY_SEPARATOR . $this->testProfileFile;
        if (File::exists($profilePath)) {
            File::delete($profilePath);
        }

        parent::tearDown();
    }

    private function createVendor(): Subscriber
    {
        $unique = uniqid();
        return Subscriber::create([
            'name' => 'Test Vendor ' . $unique,
            'email' => 'vendor_' . $unique . '@test.com',
            'mobile' => '999' . substr(str_shuffle('0123456789'), 0, 7),
            'password' => Hash::make('password123'),
            'location' => 'Chennai',
            'subscriptionDate' => now()->subDay()->toDateTimeString(),
            'expiryDate' => now()->addYear()->toDateTimeString(),
            'status' => 1,
            'blockedstatus' => 1,
            'pincode' => json_encode(['600001']),
            'aadharNo' => '123456789012',
            'aadharImage' => 'front.jpg',
            'pancardImage' => 'pan.jpg',
            'account_type' => 'Individual',
            'image' => 'profile.jpg',
            'created_by' => '1',
            'bankstatement' => 'stmt.jpg',
            'aadharBackImage' => 'back.jpg',
            'video' => '',
            'gst' => '',
            'qr' => '',
            'customerdocument' => '',
        ]);
    }

    private function createRider(int $vendorId, array $attributes = []): Driver
    {
        $unique = uniqid();
        return Driver::create(array_merge([
            'subscriberId' => $vendorId,
            'name' => 'Rider ' . $unique,
            'mobile' => '888' . substr(str_shuffle('0123456789'), 0, 7),
            'password' => Hash::make('pass123'),
            'pincode' => json_encode(['600001']),
            'aadharNo' => '1234' . rand(10000000, 99999999),
            'vehicleNo' => 'TN01AB' . rand(1000, 9999),
            'vehicleModelNo' => 'Model X',
            'aadharFrontImage' => '',
            'aadharBackImage' => '',
            'drivingLicence' => '',
            'rcbook' => '',
            'bike' => '',
            'customerdocument' => '',
            'status' => 1,
            'type' => '1',
            'language' => 'English',
        ], $attributes));
    }

    /**
     * Test A: Existing physical file returns valid asset URL without /public/ segment and opens successfully.
     */
    public function test_existing_physical_file_returns_valid_asset_url_without_public_segment()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $user = User::create([
            'name' => 'Existing File Rider',
            'email' => 'rider_' . uniqid() . '@test.com',
            'phone' => '888' . substr(str_shuffle('0123456789'), 0, 7),
            'password' => Hash::make('pass123'),
            'user_id' => 'DK-' . uniqid(),
            'is_driver' => 1,
            'profile_image' => $this->testProfileFile,
            'image' => $this->testProfileFile,
        ]);

        $rider = $this->createRider($vendor->id, [
            'userid' => $user->id,
            'name' => 'Existing File Rider',
            'mobile' => $user->phone,
            'email' => $user->email,
            'aadharFrontImage' => $this->testAadharFile,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $items = $response->json('data.items');
        $this->assertNotEmpty($items);

        $foundRider = collect($items)->firstWhere('id', $rider->id);
        $this->assertNotNull($foundRider);

        // Verify valid asset URL generated for existing physical files
        $expectedAadharUrl = asset('subscriber/driver/aadhar/' . $this->testAadharFile);
        $expectedProfileUrl = asset('subscriber/driver/profile/' . $this->testProfileFile);

        $this->assertEquals($expectedAadharUrl, $foundRider['aadhar_front_image']);
        $this->assertEquals($expectedProfileUrl, $foundRider['profile_image']);

        // Verify URL does NOT contain /public/ segment
        $this->assertStringNotContainsString('/public/', $foundRider['aadhar_front_image']);
        $this->assertStringNotContainsString('/public/', $foundRider['profile_image']);

        // Verify physical file exists at resolved location
        $this->assertFileExists(public_path('subscriber/driver/aadhar/' . $this->testAadharFile));
        $this->assertFileExists(public_path('subscriber/driver/profile/' . $this->testProfileFile));
    }

    /**
     * Test B: Missing physical file returns null instead of a dead 404 URL.
     */
    public function test_missing_physical_file_returns_null()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $nonExistentFileName = 'ghost_file_1782215689.pdf';

        $rider = $this->createRider($vendor->id, [
            'name' => 'Ghost File Rider',
            'aadharFrontImage' => $nonExistentFileName,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $items = $response->json('data.items');
        $foundRider = collect($items)->firstWhere('id', $rider->id);

        $this->assertNotNull($foundRider);
        $this->assertNull($foundRider['aadhar_front_image'], 'Non-existent physical file must resolve to null, not a 404 URL.');
    }

    /**
     * Test C: Invalid / non-file value (e.g. driving licence number stored in column) returns null.
     */
    public function test_invalid_non_file_string_returns_null()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createRider($vendor->id, [
            'name' => 'Text Field Rider',
            'drivingLicence' => 'DL1234567890',
            'rcbook' => 'RC1234567890',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $items = $response->json('data.items');
        $foundRider = collect($items)->firstWhere('id', $rider->id);

        $this->assertNotNull($foundRider);
        $this->assertNull($foundRider['driving_licence'], 'Non-filename strings like DL1234567890 must return null.');
        $this->assertNull($foundRider['rc_book'], 'Non-filename strings like RC1234567890 must return null.');
    }

    /**
     * Test D: Empty or null DB values return null.
     */
    public function test_empty_or_null_db_value_returns_null()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createRider($vendor->id, [
            'name' => 'Empty Doc Rider',
            'aadharFrontImage' => '',
            'aadharBackImage' => '',
            'drivingLicence' => '',
            'rcbook' => '',
            'bike' => '',
            'customerdocument' => null,
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $items = $response->json('data.items');
        $foundRider = collect($items)->firstWhere('id', $rider->id);

        $this->assertNotNull($foundRider);
        $this->assertNull($foundRider['profile_image']);
        $this->assertNull($foundRider['aadhar_front_image']);
        $this->assertNull($foundRider['aadhar_back_image']);
        $this->assertNull($foundRider['driving_licence']);
        $this->assertNull($foundRider['rc_book']);
        $this->assertNull($foundRider['bike_image']);
        $this->assertNull($foundRider['customer_document']);
    }

    /**
     * Test E: Path traversal attempt is safely sanitized and rejected (returns null).
     */
    public function test_path_traversal_attempt_returns_null()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createRider($vendor->id, [
            'name' => 'Path Traversal Rider',
            'aadharFrontImage' => '../../../../../../etc/passwd',
            'drivingLicence' => '../../../index.php',
            'status' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200);
        $items = $response->json('data.items');
        $foundRider = collect($items)->firstWhere('id', $rider->id);

        $this->assertNotNull($foundRider);
        $this->assertNull($foundRider['aadhar_front_image']);
        $this->assertNull($foundRider['driving_licence']);
    }

    /**
     * Test F: Existing Riders List response keys remain unchanged and backwards-compatible.
     */
    public function test_riders_list_response_structure_and_keys_remain_unchanged()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $rider = $this->createRider($vendor->id, [
            'name' => 'Structure Rider',
            'status' => 0,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                    'items' => [
                        '*' => [
                            'id',
                            'user_id',
                            'subscriber_id',
                            'name',
                            'mobile',
                            'email',
                            'location',
                            'pincode',
                            'language',
                            'vehicle_no',
                            'vehicle_model_no',
                            'status',
                            'status_text',
                            'type',
                            'gender',
                            'dob',
                            'description',
                            'bankacno',
                            'ifsccode',
                            'licenceexpiry',
                            'aadhar_no',
                            'profile_image',
                            'aadhar_front_image',
                            'aadhar_back_image',
                            'driving_licence',
                            'rc_book',
                            'bike_image',
                            'customer_document',
                            'insurance',
                            'rider_agreement',
                            'created_at',
                        ]
                    ]
                ]
            ]);

        $items = $response->json('data.items');
        $foundRider = collect($items)->firstWhere('id', $rider->id);
        $this->assertNotNull($foundRider);

        // Ensure unsupported pan_card is NOT in the response, while confirmed insurance & rider_agreement are present
        $this->assertArrayNotHasKey('pan_card', $foundRider);
        $this->assertArrayHasKey('insurance', $foundRider);
        $this->assertArrayHasKey('rider_agreement', $foundRider);
    }
}
