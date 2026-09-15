<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;

class VendorRiderUpdateTest extends TestCase
{
    use DatabaseTransactions;

    private static int $counter = 0;

    private function createVendor(array $overrides = []): Subscriber
    {
        $idx = ++self::$counter;
        return Subscriber::create(array_merge([
            'name'             => "VendorUpd_{$idx}",
            'email'            => "vupd_{$idx}_" . time() . "@test.com",
            'mobile'           => '9' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'password'         => Hash::make('password123'),
            'subscriberId'     => "VUPD{$idx}",
            'location'         => 'Test Location',
            'subscriptionDate' => '2025-01-01 00:00:00',
            'expiryDate'       => '2030-12-31 23:59:59',
            'status'           => 1,
            'blockedstatus'    => 1,
            'pincode'          => json_encode([600001, 600002, (int) $idx]),
            'aadharNo'         => '123456789012',
            'aadharImage'      => 'front.jpg',
            'pancardImage'     => 'pan.jpg',
            'customerdocument' => 'doc.pdf',
            'account_type'     => 'Individual',
            'image'            => 'profile.jpg',
            'created_by'       => '1',
            'bankstatement'    => 'stmt.jpg',
            'aadharBackImage'  => 'back.jpg',
            'video'            => '',
            'gst'              => '',
            'qr'               => '',
        ], $overrides));
    }

    private function createDriver(Subscriber $vendor, array $overrides = []): Driver
    {
        $idx = ++self::$counter;
        $user = User::create([
            'name'          => "RiderUser_{$idx}",
            'email'         => "rider_upd_{$idx}_" . time() . "@test.com",
            'phone'         => '8' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'password'      => Hash::make('password123'),
            'user_id'       => 'DK-UPD-' . $idx,
            'is_driver'     => 1,
            'is_live'       => 0,
            'gender'        => 'Male',
            'dob'           => '1995-05-15',
            'dop'           => '1995-05-15',
            'profile_image' => 'existing_profile.png',
            'image'         => 'existing_profile.png',
        ]);

        return Driver::create(array_merge([
            'subscriberId'     => $vendor->id,
            'userid'           => $user->id,
            'name'             => "Rider_{$idx}",
            'email'            => "driver_upd_{$idx}_" . time() . "@test.com",
            'mobile'           => '7' . str_pad((string) $idx, 9, '0', STR_PAD_LEFT),
            'password'         => Hash::make('password123'),
            'source'           => 'password123',
            'location'         => 'Chennai',
            'pincode'          => json_encode([600001]),
            'language'         => 'Tamil,English',
            'aadharNo'         => '5555' . str_pad((string) $idx, 8, '0', STR_PAD_LEFT),
            'vehicleNo'        => 'TN-01-AB-' . str_pad((string) $idx, 4, '0', STR_PAD_LEFT),
            'vehicleModelNo'   => 'Hero Splendor',
            'type'             => '1,2',
            'status'           => 0, // Pending Approval
            'bankacno'         => '1234567890',
            'ifsccode'         => 'HDFC0001234',
            'licenceexpiry'    => '2028-10-10',
            'description'      => 'Test rider description',
            'aadharFrontImage' => 'existing_aadhar_front.jpg',
            'aadharBackImage'  => 'existing_aadhar_back.jpg',
            'drivingLicence'   => 'existing_dl.jpg',
            'rcbook'           => 'existing_rc.jpg',
            'bike'             => 'existing_bike.jpg',
            'customerdocument' => 'existing_doc.pdf',
            'insurance'        => 'existing_insurance.pdf',
            'riderAgreement'   => 'existing_agreement.pdf',
        ], $overrides));
    }

    /**
     * 1. Vendor can update own rider (name, mobile, email, location, vehicleNo)
     */
    public function test_vendor_can_update_own_rider()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $newMobile = '7999888777';
        $newEmail = 'updated_rider_' . time() . '@test.com';

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'name'           => 'Updated Rider Name',
                'mobile'         => $newMobile,
                'email'          => $newEmail,
                'location'       => 'Updated Location',
                'vehicleNo'      => 'TN-09-ZZ-9999',
                'vehicleModelNo' => 'Honda Activa',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Rider updated successfully',
                'data' => [
                    'rider' => [
                        'id' => $rider->id,
                        'name' => 'Updated Rider Name',
                        'mobile' => $newMobile,
                        'email' => $newEmail,
                        'location' => 'Updated Location',
                        'vehicle_no' => 'TN-09-ZZ-9999',
                        'vehicle_model_no' => 'Honda Activa',
                    ]
                ]
            ]);

        $fresh = $rider->fresh();
        $this->assertEquals('Updated Rider Name', $fresh->name);
        $this->assertEquals($newMobile, $fresh->mobile);
        $this->assertEquals($newEmail, $fresh->email);

        $user = User::find($rider->userid);
        $this->assertEquals('Updated Rider Name', $user->name);
        $this->assertEquals($newMobile, $user->phone);
        $this->assertEquals($newEmail, $user->email);
    }

    /**
     * 2. Vendor cannot update another vendor's rider (404 access denied)
     */
    public function test_vendor_cannot_update_another_vendors_rider()
    {
        $vendorA = $this->createVendor();
        $vendorB = $this->createVendor();
        $riderB = $this->createDriver($vendorB);

        $tokenA = $vendorA->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->putJson('/api/vendor/riders/' . $riderB->id, [
                'name' => 'Hacked Name',
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ]);

        $this->assertNotEquals('Hacked Name', $riderB->fresh()->name);
    }

    /**
     * 3. Non-existent rider returns 404
     */
    public function test_update_non_existent_rider_returns_404()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/99999999', [
                'name' => 'Non Existent',
            ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'message' => 'Rider not found or access denied.'
            ]);
    }

    /**
     * 4. Unauthenticated request returns 401
     */
    public function test_unauthenticated_rider_update_returns_401()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);

        $response = $this->putJson('/api/vendor/riders/' . $rider->id, [
            'name' => 'Unauthorized Update',
        ]);

        $response->assertStatus(401);
    }

    /**
     * 5. Omitted fields remain unchanged
     */
    public function test_omitted_fields_remain_unchanged()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $originalAadhar = $rider->aadharNo;
        $originalBankacno = $rider->bankacno;
        $originalVehicleNo = $rider->vehicleNo;
        $originalLicenceExpiry = $rider->licenceexpiry;
        $originalAadharFront = $rider->aadharFrontImage;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/vendor/riders/' . $rider->id, [
                'name' => 'Name Changed Only',
            ]);

        $response->assertStatus(200);

        $fresh = $rider->fresh();
        $this->assertEquals('Name Changed Only', $fresh->name);
        $this->assertEquals($originalAadhar, $fresh->aadharNo);
        $this->assertEquals($originalBankacno, $fresh->bankacno);
        $this->assertEquals($originalVehicleNo, $fresh->vehicleNo);
        $this->assertEquals($originalLicenceExpiry, $fresh->licenceexpiry);
        $this->assertEquals($originalAadharFront, $fresh->aadharFrontImage);
    }

    /**
     * 6. Mobile/email uniqueness ignores current rider (self-update succeeds)
     */
    public function test_mobile_and_email_uniqueness_ignores_current_rider()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'mobile' => $rider->mobile,
                'email'  => $rider->email,
                'name'   => 'Self Update Works',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Rider updated successfully',
            ]);
    }

    /**
     * 7. Duplicate mobile from another rider is rejected (422)
     */
    public function test_duplicate_mobile_from_another_rider_is_rejected()
    {
        $vendor = $this->createVendor();
        $rider1 = $this->createDriver($vendor);
        $rider2 = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider2->id, [
                'mobile' => $rider1->mobile,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['mobile']);
    }

    /**
     * 8. New profile image updates users.image and users.profile_image
     */
    public function test_profile_image_update()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/riders/' . $rider->id, [
                '_method' => 'PUT',
                'profile' => $file,
            ]);

        $response->assertStatus(200);

        $user = User::find($rider->userid);
        $this->assertNotNull($user->profile_image);
        $this->assertEquals($user->profile_image, $user->image);
        $this->assertStringEndsWith('.jpg', $user->profile_image);

        // Clean up uploaded file
        $filePath = public_path('subscriber/driver/profile/' . $user->profile_image);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
    }

    /**
     * 9. Existing document preserved when no new file uploaded
     */
    public function test_existing_documents_preserved_when_not_reuploaded()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $this->assertEquals('existing_rc.jpg', $rider->rcbook);
        $this->assertEquals('existing_dl.jpg', $rider->drivingLicence);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'location' => 'Madurai',
            ]);

        $response->assertStatus(200);

        $fresh = $rider->fresh();
        $this->assertEquals('existing_rc.jpg', $fresh->rcbook);
        $this->assertEquals('existing_dl.jpg', $fresh->drivingLicence);
    }

    /**
     * 10. Pending rider status remains 0 after profile update
     */
    public function test_status_field_is_immutable_via_update_endpoint()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor, ['status' => 0]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'name'   => 'Attempt Status Change',
                'status' => 1, // Try to activate directly
            ]);

        $response->assertStatus(200);

        $fresh = $rider->fresh();
        $this->assertEquals(0, (int) $fresh->status, 'Rider status must NOT be changed via update endpoint');
    }

    /**
     * 11. Updated rider appears correctly in GET /api/vendor/riders
     */
    public function test_updated_rider_appears_correctly_in_riders_list()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'name' => 'Distinctive List Name 12345',
            ]);

        $listResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders');

        $listResponse->assertStatus(200);

        $riders = $listResponse->json('data.items');
        $found = collect($riders)->firstWhere('id', $rider->id);

        $this->assertNotNull($found);
        $this->assertEquals('Distinctive List Name 12345', $found['name']);
    }

    /**
     * 12. Updated rider appears correctly in GET /api/vendor/riders/overview
     */
    public function test_updated_rider_appears_correctly_in_overview()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor, ['status' => 0]); // Pending
        $token = $vendor->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider->id, [
                'name' => 'Overview Test Name',
            ]);

        $overviewResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/riders/overview');

        $overviewResponse->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'total_riders',
                    'pending_approval_riders',
                    'online',
                    'engaged',
                    'offline',
                ]
            ]);

        $this->assertGreaterThanOrEqual(1, $overviewResponse->json('data.pending_approval_riders'));
    }

    /**
     * 13. aadharNo update accepted (unique:driver,aadharNo,{id}), duplicate rejected
     */
    public function test_aadharno_update_and_duplicate_protection()
    {
        $vendor = $this->createVendor();
        $rider1 = $this->createDriver($vendor);
        $rider2 = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        // 13a. Self-update with same aadharNo succeeds
        $resSelf = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider1->id, [
                'aadharNo' => $rider1->aadharNo,
            ]);
        $resSelf->assertStatus(200);

        // 13b. Update with a new valid unique aadharNo succeeds
        $newAadhar = '998877665544';
        $resNew = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider1->id, [
                'aadharNo' => $newAadhar,
            ]);
        $resNew->assertStatus(200);
        $this->assertEquals($newAadhar, $rider1->fresh()->aadharNo);

        // 13c. Duplicate aadharNo from another rider is rejected
        $resDup = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/vendor/riders/' . $rider2->id, [
                'aadharNo' => $newAadhar,
            ]);
        $resDup->assertStatus(422)
            ->assertJsonValidationErrors(['aadharNo']);
    }

    /**
     * 14. Insurance and riderAgreement documents can be uploaded and updated
     */
    public function test_insurance_and_rider_agreement_upload()
    {
        $vendor = $this->createVendor();
        $rider = $this->createDriver($vendor);
        $token = $vendor->createToken('test')->plainTextToken;

        $insuranceFile = UploadedFile::fake()->create('insurance_doc.pdf', 150, 'application/pdf');
        $agreementFile = UploadedFile::fake()->create('agreement_doc.pdf', 150, 'application/pdf');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/vendor/riders/' . $rider->id, [
                '_method'        => 'PUT',
                'insurance'      => $insuranceFile,
                'riderAgreement' => $agreementFile,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'Rider updated successfully',
            ]);

        $fresh = $rider->fresh();
        $this->assertNotEmpty($fresh->insurance);
        $this->assertNotEmpty($fresh->riderAgreement);
        $this->assertStringEndsWith('.pdf', $fresh->insurance);
        $this->assertStringEndsWith('.pdf', $fresh->riderAgreement);

        // Check formatRider contains insurance and rider_agreement keys
        $riderData = $response->json('data.rider');
        $this->assertArrayHasKey('insurance', $riderData);
        $this->assertArrayHasKey('rider_agreement', $riderData);

        // Clean up files
        $insPath = public_path('subscriber/driver/insurance/' . $fresh->insurance);
        $agrPath = public_path('subscriber/driver/riderAgreement/' . $fresh->riderAgreement);
        if (file_exists($insPath)) {
            @unlink($insPath);
        }
        if (file_exists($agrPath)) {
            @unlink($agrPath);
        }
    }
}
