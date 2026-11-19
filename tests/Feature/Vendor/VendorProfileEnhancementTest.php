<?php

namespace Tests\Feature\Vendor;

use App\Models\Category;
use App\Models\Pincode;
use App\Models\Pincodebasedcategory;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class VendorProfileEnhancementTest extends TestCase
{
    use DatabaseTransactions;

    private array $defaultVendorData = [
        'location' => 'Chennai',
        'subscriptionDate' => '2025-01-01 00:00:00',
        'expiryDate' => '2030-12-31 23:59:59',
        'status' => 1,
        'blockedstatus' => 1,
        'pincode' => '[]',
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

    private function createVendor(array $overrides = []): Subscriber
    {
        return Subscriber::create(array_merge($this->defaultVendorData, [
            'name' => 'Test Vendor ' . uniqid(),
            'email' => 'vendor_' . uniqid() . '@example.com',
            'mobile' => '98' . rand(10000000, 99999999),
            'password' => Hash::make('Secret123'),
            'subscriberId' => 'SUB-' . uniqid(),
            'device_token' => 'token_' . uniqid(),
        ], $overrides));
    }

    private function getVendorToken(Subscriber $vendor): string
    {
        return $vendor->createToken('test')->plainTextToken;
    }

    /*
     * -------------------------------------------------------------
     * 1. Pincode-wise Service Management Tests
     * -------------------------------------------------------------
     */

    public function test_vendor_can_get_services_for_owned_pincode(): void
    {
        $vendor = $this->createVendor();
        $pincode = Pincode::create(['pincode' => '600001', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$pincode->id])]);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->getJson("/api/vendor/pincodes/{$pincode->id}/services");

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => true,
                'data' => [
                    'pincode_id' => $pincode->id,
                    'pincode' => '600001',
                ],
            ]);

        $services = $response->json('data.services');
        $this->assertIsArray($services);
        $this->assertNotEmpty($services);

        // Verify default status is active (1 / is_active: true)
        foreach ($services as $service) {
            $this->assertTrue($service['is_active']);
            $this->assertEquals('active', $service['status']);
        }
    }

    public function test_vendor_cannot_get_services_for_unowned_pincode(): void
    {
        $vendor = $this->createVendor();
        $otherVendor = $this->createVendor();
        $pincode = Pincode::create(['pincode' => '600002', 'usedBy' => $otherVendor->id]);
        $otherVendor->update(['pincode' => json_encode([$pincode->id])]);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->getJson("/api/vendor/pincodes/{$pincode->id}/services");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'status' => false,
            ]);
    }

    public function test_vendor_can_update_single_service_status(): void
    {
        $vendor = $this->createVendor();
        $pincode = Pincode::create(['pincode' => '600003', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$pincode->id])]);
        $token = $this->getVendorToken($vendor);

        // Disable service #1 (Bike Taxi)
        $response = $this->withToken($token)
            ->putJson("/api/vendor/pincodes/{$pincode->id}/services/1/status", [
                'status' => 'inactive',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => true,
                'data' => [
                    'pincode_id' => $pincode->id,
                    'service_id' => 1,
                    'status' => 'inactive',
                    'is_active' => false,
                ],
            ]);

        $this->assertDatabaseHas('pincodebasedcategories', [
            'subscriber_id' => $vendor->id,
            'pincode_id' => $pincode->id,
            'category_id' => 1,
            'status' => 0,
        ]);

        // Re-enable service #1 with status = 1 (preventing duplicate rows via updateOrCreate)
        $response2 = $this->withToken($token)
            ->putJson("/api/vendor/pincodes/{$pincode->id}/services/1/status", [
                'status' => 1,
            ]);

        $response2->assertOk()
            ->assertJsonPath('data.status', 'active')
            ->assertJsonPath('data.is_active', true);

        $rowCount = Pincodebasedcategory::where('subscriber_id', $vendor->id)
            ->where('pincode_id', $pincode->id)
            ->where('category_id', 1)
            ->count();

        $this->assertEquals(1, $rowCount);
    }

    public function test_single_service_update_rejects_invalid_status(): void
    {
        $vendor = $this->createVendor();
        $pincode = Pincode::create(['pincode' => '600004', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$pincode->id])]);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->putJson("/api/vendor/pincodes/{$pincode->id}/services/1/status", [
                'status' => 'invalid_status_value',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'status' => false,
            ]);
    }

    public function test_vendor_can_batch_update_service_status(): void
    {
        $vendor = $this->createVendor();
        $pin1 = Pincode::create(['pincode' => '600005', 'usedBy' => $vendor->id]);
        $pin2 = Pincode::create(['pincode' => '600006', 'usedBy' => $vendor->id]);
        $vendor->update(['pincode' => json_encode([$pin1->id, $pin2->id])]);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->postJson('/api/vendor/pincodes/services/status', [
                'pincode_ids' => [$pin1->id, $pin2->id],
                'service_id' => 2,
                'status' => 'inactive',
            ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'status' => true,
                'data' => [
                    'service_id' => 2,
                    'status' => 'inactive',
                    'is_active' => false,
                    'total_updated' => 2,
                ],
            ]);

        $this->assertDatabaseHas('pincodebasedcategories', [
            'subscriber_id' => $vendor->id,
            'pincode_id' => $pin1->id,
            'category_id' => 2,
            'status' => 0,
        ]);

        $this->assertDatabaseHas('pincodebasedcategories', [
            'subscriber_id' => $vendor->id,
            'pincode_id' => $pin2->id,
            'category_id' => 2,
            'status' => 0,
        ]);
    }

    public function test_batch_update_rejects_if_any_pincode_is_unauthorized(): void
    {
        $vendor = $this->createVendor();
        $otherVendor = $this->createVendor();
        $pin1 = Pincode::create(['pincode' => '600007', 'usedBy' => $vendor->id]);
        $pin2 = Pincode::create(['pincode' => '600008', 'usedBy' => $otherVendor->id]);
        $vendor->update(['pincode' => json_encode([$pin1->id])]);
        $otherVendor->update(['pincode' => json_encode([$pin2->id])]);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->postJson('/api/vendor/pincodes/services/status', [
                'pincode_ids' => [$pin1->id, $pin2->id],
                'service_id' => 2,
                'status' => 'inactive',
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'status' => false,
            ]);

        // Ensure database transaction rolled back and no row was inserted for pin1
        $this->assertDatabaseMissing('pincodebasedcategories', [
            'subscriber_id' => $vendor->id,
            'pincode_id' => $pin1->id,
            'category_id' => 2,
        ]);
    }

    /*
     * -------------------------------------------------------------
     * 2. GST Number Tests
     * -------------------------------------------------------------
     */

    public function test_vendor_can_save_valid_gst_and_normalizes_to_uppercase(): void
    {
        $vendor = $this->createVendor();
        $token = $this->getVendorToken($vendor);

        $validGstin = '33aaaaa0000a1z5';

        $response = $this->withToken($token)
            ->postJson('/api/vendor/business/update', [
                'gst' => $validGstin,
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'gst' => '33AAAAA0000A1Z5',
                ],
            ]);

        $vendor->refresh();
        $this->assertEquals('33AAAAA0000A1Z5', $vendor->gst);

        // Check GET /api/vendor/business includes gst
        $getResponse = $this->withToken($token)->getJson('/api/vendor/business');
        $getResponse->assertOk()->assertJsonPath('data.gst', '33AAAAA0000A1Z5');

        // Check GET /api/vendor/me includes gst
        $meResponse = $this->withToken($token)->getJson('/api/vendor/me');
        $meResponse->assertOk()->assertJsonPath('data.vendor.gst', '33AAAAA0000A1Z5');
    }

    public function test_vendor_rejects_invalid_gstin_format(): void
    {
        $vendor = $this->createVendor();
        $token = $this->getVendorToken($vendor);

        $invalidGstins = [
            'INVALID_GSTIN',
            '33AAAA0000A1Z5', // 14 chars
            '33AAAAAA0000A1Z5', // 16 chars
            '123456789012345',
        ];

        foreach ($invalidGstins as $invalidGst) {
            $response = $this->withToken($token)
                ->postJson('/api/vendor/business/update', [
                    'gst' => $invalidGst,
                ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['gst']);
        }
    }

    public function test_vendor_can_clear_gst_with_null_or_empty(): void
    {
        $vendor = $this->createVendor(['gst' => '33AAAAA0000A1Z5']);
        $token = $this->getVendorToken($vendor);

        $response = $this->withToken($token)
            ->postJson('/api/vendor/business/update', [
                'gst' => '',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.gst', '');

        $vendor->refresh();
        $this->assertNull($vendor->gst);
    }

    /*
     * -------------------------------------------------------------
     * 3. QR Code Upload Tests
     * -------------------------------------------------------------
     */

    public function test_vendor_can_upload_qr_image(): void
    {
        $vendor = $this->createVendor();
        $token = $this->getVendorToken($vendor);

        $file = UploadedFile::fake()->image('test_qr.png', 300, 300);

        $response = $this->withToken($token)
            ->post('/api/vendor/business/update', [
                'qr' => $file,
            ]);

        $response->assertOk()
            ->assertJson([
                'status' => true,
            ]);

        $qrCodeUrl = $response->json('data.qr_code_url');
        $this->assertNotNull($qrCodeUrl);
        $this->assertStringContainsString('qr/', $qrCodeUrl);

        $vendor->refresh();
        $this->assertNotEmpty($vendor->qr);

        $createdFilePath = public_path('qr/' . $vendor->qr);
        $this->assertFileExists($createdFilePath);

        // Check GET /api/vendor/business includes qr_code_url
        $businessResponse = $this->withToken($token)->getJson('/api/vendor/business');
        $businessResponse->assertOk()
            ->assertJsonPath('data.qr_code_url', $qrCodeUrl);

        // Check GET /api/vendor/me includes qr_code_url
        $meResponse = $this->withToken($token)->getJson('/api/vendor/me');
        $meResponse->assertOk()
            ->assertJsonPath('data.vendor.qr_code_url', $qrCodeUrl);

        // Clean up created test file
        if (File::exists($createdFilePath)) {
            @unlink($createdFilePath);
        }
    }

    public function test_qr_upload_rejects_non_image_or_oversized_file(): void
    {
        $vendor = $this->createVendor();
        $token = $this->getVendorToken($vendor);

        // Non-image file (pdf)
        $invalidFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');
        $response = $this->withToken($token)
            ->post('/api/vendor/business/update', [
                'qr' => $invalidFile,
            ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['qr']);

        // SVG file rejection (Active Content / Stored XSS prevention)
        $svgFile = UploadedFile::fake()->create('malicious.svg', 10, 'image/svg+xml');
        $responseSvg = $this->withToken($token)
            ->post('/api/vendor/business/update', [
                'qr' => $svgFile,
            ]);
        $responseSvg->assertStatus(422)->assertJsonValidationErrors(['qr']);

        // Oversized file (> 4MB)
        $oversizedFile = UploadedFile::fake()->image('large.png')->size(5000);
        $response2 = $this->withToken($token)
            ->post('/api/vendor/business/update', [
                'qr' => $oversizedFile,
            ]);
        $response2->assertStatus(422)->assertJsonValidationErrors(['qr']);
    }

    public function test_qr_upload_replaces_old_file_safely(): void
    {
        $vendor = $this->createVendor();
        $token = $this->getVendorToken($vendor);

        // Step 1: Upload first QR
        $file1 = UploadedFile::fake()->image('first_qr.png', 200, 200);
        $res1 = $this->withToken($token)->post('/api/vendor/business/update', ['qr' => $file1]);
        $res1->assertOk();

        $vendor->refresh();
        $firstFilename = $vendor->qr;
        $firstFilePath = public_path('qr/' . $firstFilename);
        $this->assertFileExists($firstFilePath);

        // Step 2: Upload second QR
        $file2 = UploadedFile::fake()->image('second_qr.png', 200, 200);
        $res2 = $this->withToken($token)->post('/api/vendor/business/update', ['qr' => $file2]);
        $res2->assertOk();

        $vendor->refresh();
        $secondFilename = $vendor->qr;
        $secondFilePath = public_path('qr/' . $secondFilename);

        $this->assertNotEquals($firstFilename, $secondFilename);
        $this->assertFileExists($secondFilePath);
        // Verify old file was deleted
        $this->assertFileDoesNotExist($firstFilePath);

        // Clean up second file
        if (File::exists($secondFilePath)) {
            @unlink($secondFilePath);
        }
    }
}

