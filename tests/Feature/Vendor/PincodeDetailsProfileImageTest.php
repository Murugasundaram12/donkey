<?php

namespace Tests\Feature\Vendor;

use App\Models\Pincode;
use App\Models\Subscriber;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PincodeDetailsProfileImageTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test pincodeDetails preserves raw filename in subscriber.image.
     */
    public function test_pincode_details_preserves_raw_image_filename(): void
    {
        $response = $this->getJson('/api/pincodeDetails?pincode=603210');

        $response->assertOk()
            ->assertJson([
                'status' => true,
                'message' => 'Details about the pincode.',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'subscriber' => [
                    'id',
                    'name',
                    'image',
                ],
                'driversCount',
                'driverOnlineCount',
                'driverOfflineCount',
                'othersCount',
                'driverOnrideCount',
                'categories',
            ]);

        $subscriber = $response->json('subscriber');
        $this->assertSame('1794985850_6afd4f7a5cbf8.jpg', $subscriber['image'], 'subscriber.image must remain the original filename');

        // Verify logo_url and image_url are NOT added
        $this->assertArrayNotHasKey('logo_url', $subscriber);
        $this->assertArrayNotHasKey('image_url', $subscriber);
    }

    /**
     * Test admin panel subscriber preserves raw filename in subscriber.image.
     */
    public function test_admin_subscriber_preserves_raw_image_filename(): void
    {
        $response = $this->getJson('/api/pincodeDetails?pincode=631001');

        $response->assertOk();

        $subscriber = $response->json('subscriber');
        $this->assertSame('1744362991.jpg', $subscriber['image']);
        $this->assertArrayNotHasKey('logo_url', $subscriber);
        $this->assertArrayNotHasKey('image_url', $subscriber);
    }
}
