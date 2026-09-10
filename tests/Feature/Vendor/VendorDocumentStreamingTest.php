<?php

namespace Tests\Feature\Vendor;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class VendorDocumentStreamingTest extends TestCase
{
    use DatabaseTransactions;

    private string $testFilePath;
    private string $testFileName = 'test_stream_document_sample.pdf';

    protected function setUp(): void
    {
        parent::setUp();

        $testDir = public_path('subscriber/document');
        if (!File::exists($testDir)) {
            File::makeDirectory($testDir, 0755, true);
        }

        $this->testFilePath = $testDir . DIRECTORY_SEPARATOR . $this->testFileName;
        File::put($this->testFilePath, "%PDF-1.4\n%test pdf content for streaming\n%%EOF");
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testFilePath)) {
            File::delete($this->testFilePath);
        }

        parent::tearDown();
    }

    private function createVendor(array $attributes = []): Subscriber
    {
        $unique = uniqid();
        return Subscriber::create(array_merge([
            'name' => 'Test Vendor ' . $unique,
            'email' => 'vendor_' . $unique . '@test.com',
            'mobile' => '999' . substr(str_shuffle('0123456789'), 0, 7),
            'password' => Hash::make('password123'),
            'location' => 'Location Test',
            'subscriptionDate' => now()->subDay()->toDateTimeString(),
            'expiryDate' => now()->addYear()->toDateTimeString(),
            'status' => 1,
            'blockedstatus' => 1,
            'pincode' => json_encode(['600001']),
            'aadharNo' => '123456789012',
            'aadharImage' => 'dummy_front.jpg',
            'aadharBackImage' => '',
            'pancardImage' => 'dummy_pan.jpg',
            'bankstatement' => 'dummy_stmt.jpg',
            'customerdocument' => '',
            'account_type' => 'Individual',
            'image' => 'dummy_profile.jpg',
            'created_by' => '1',
            'video' => '',
            'gst' => '',
            'qr' => '',
        ], $attributes));
    }

    /**
     * Test 1: Authenticated vendor can access their own document and get streamed response with correct Content-Type.
     */
    public function test_authenticated_vendor_can_stream_own_document()
    {
        $vendor = $this->createVendor([
            'customerdocument' => $this->testFileName,
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get('/api/vendor/documents/customer_document/file');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('inline; filename="' . $this->testFileName . '"', $response->headers->get('Content-Disposition'));
    }

    /**
     * Test 2: Unauthenticated request is rejected with 401.
     */
    public function test_unauthenticated_request_is_rejected()
    {
        $response = $this->getJson('/api/vendor/documents/customer_document/file');
        $response->assertStatus(401);
    }

    /**
     * Test 3: Invalid document type returns 400.
     */
    public function test_invalid_document_type_returns_400()
    {
        $vendor = $this->createVendor();
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/documents/invalid_type_here/file');

        $response->assertStatus(400);
        $response->assertJson([
            'status' => false,
            'message' => 'Invalid document type requested.',
        ]);
    }

    /**
     * Test 4: Missing document (no file in DB or not on disk) returns 404.
     */
    public function test_missing_document_returns_404()
    {
        $vendor = $this->createVendor([
            'customerdocument' => null,
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/documents/customer_document/file');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => false,
            'message' => 'Document not found.',
        ]);
    }

    /**
     * Test 5: Vendor A cannot access Vendor B's document (isolated to authenticated vendor).
     */
    public function test_vendor_a_cannot_access_vendor_b_document()
    {
        // Vendor A has NO document
        $vendorA = $this->createVendor([
            'customerdocument' => null,
        ]);
        $tokenA = $vendorA->createToken('test')->plainTextToken;

        // Vendor B has a document
        $vendorB = $this->createVendor([
            'customerdocument' => $this->testFileName,
        ]);

        // Vendor A tries to request customer_document -> gets 404 because Vendor A doesn't have one
        $response = $this->withHeader('Authorization', 'Bearer ' . $tokenA)
            ->getJson('/api/vendor/documents/customer_document/file');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => false,
            'message' => 'Document not found.',
        ]);
    }

    /**
     * Test 6: Path traversal attempt in DB attribute cannot escape allowed directory.
     */
    public function test_path_traversal_attempt_is_rejected()
    {
        // Even if database has malicious traversal string
        $vendor = $this->createVendor([
            'customerdocument' => '../../../../../../etc/passwd',
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/documents/customer_document/file');

        // basename() reduces it to passwd which does not exist in the allowed document directories
        $response->assertStatus(404);
    }

    /**
     * Test 7: Existing GET /api/vendor/documents response remains backward-compatible.
     */
    public function test_existing_documents_index_remains_unchanged()
    {
        $vendor = $this->createVendor([
            'customerdocument' => $this->testFileName,
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/documents');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'documents' => [
                    'aadhar_no',
                    'aadhar_front',
                    'aadhar_back',
                    'pan_card',
                    'bank_statement',
                    'customer_document',
                    'qr_code',
                    'profile_image',
                    'verification_video',
                ]
            ]
        ]);

        $customerDocUrl = $response->json('data.documents.customer_document');
        $this->assertIsString($customerDocUrl);
        $this->assertStringContainsString($this->testFileName, $customerDocUrl);
        $this->assertStringNotContainsString('/public/', $customerDocUrl);
    }

    /**
     * Test 8: Historical documents in admin/subscriber path resolve to admin/subscriber URL and stream properly.
     */
    public function test_historical_document_resolves_to_admin_subscriber_path()
    {
        $vendor = $this->createVendor([
            'customerdocument' => '1711621018.pdf',
        ]);
        $token = $vendor->createToken('test')->plainTextToken;

        // 1. Check index URL generation
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/vendor/documents');

        $response->assertStatus(200);
        $customerDocUrl = $response->json('data.documents.customer_document');
        $this->assertIsString($customerDocUrl);
        $this->assertStringContainsString('admin/subscriber/document/1711621018.pdf', $customerDocUrl);
        $this->assertStringNotContainsString('/public/', $customerDocUrl);

        // 2. Check authenticated streaming endpoint still returns HTTP 200
        $streamResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->get('/api/vendor/documents/customer_document/file');

        $streamResponse->assertStatus(200);
        $this->assertStringContainsString('application/pdf', $streamResponse->headers->get('Content-Type'));
    }
}
