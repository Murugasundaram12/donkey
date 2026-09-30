<?php

namespace App\Services;

use App\Models\Pushnotification;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Throwable;

class VendorNotificationService
{
    public const CATEGORIES = ['Payments', 'Riders', 'Bookings', 'System'];

    public function create(Subscriber|int|null $vendor, string $category, string $title, string $content, array $data = []): Pushnotification
    {
        return $this->createWithDelivery($vendor, $category, $title, $content, $data)['notification'];
    }

    /**
     * Create the notification record and report the push-delivery outcome separately.
     * Existing callers should continue using create(), which still returns the model.
     */
    public function createWithDelivery(Subscriber|int|null $vendor, string $category, string $title, string $content, array $data = []): array
    {
        $vendorId = null;
        if ($vendor instanceof Subscriber) {
            $vendorId = $vendor->id;
        } elseif (is_numeric($vendor) && (int) $vendor > 0) {
            $vendorId = (int) $vendor;
        }

        $notification = Pushnotification::create([
            'subscriber_id' => $vendorId,
            'category' => in_array($category, self::CATEGORIES, true) ? $category : 'System',
            'type' => $this->typeFor($category),
            'title' => $title,
            'content' => $content,
            'data' => $data ?: null,
        ]);

        $deliveryStatus = 'database_created';
        if ($vendorId) {
            $deliveryStatus = $this->sendPush(
                $vendor instanceof Subscriber ? $vendor : Subscriber::find($vendorId),
                $notification
            );
        }

        return [
            'notification' => $notification,
            'delivery_status' => $deliveryStatus,
        ];
    }

    public function forVendor(Subscriber $vendor)
    {
        return Pushnotification::query()
            ->where(function ($query) use ($vendor) {
                // NULL or 0 is reserved for admin-created global/system notices.
                $query->whereNull('subscriber_id')
                    ->orWhere('subscriber_id', 0)
                    ->orWhere('subscriber_id', $vendor->id);
            });
    }

    public function isRead(Pushnotification $notification, Subscriber $vendor): bool
    {
        if (empty($notification->subscriber_id)) {
            return DB::table('pushnotification_reads')->where([
                'pushnotification_id' => $notification->id,
                'subscriber_id' => $vendor->id,
            ])->exists();
        }
        return $notification->read_at !== null;
    }

    public function unreadCount(Subscriber $vendor): int
    {
        return $this->forVendor($vendor)->get()->reject(fn ($notification) => $this->isRead($notification, $vendor))->count();
    }

    public function markRead(Pushnotification $notification, Subscriber $vendor): void
    {
        if (empty($notification->subscriber_id)) {
            DB::table('pushnotification_reads')->updateOrInsert(
                ['pushnotification_id' => $notification->id, 'subscriber_id' => $vendor->id],
                ['read_at' => now(), 'updated_at' => now(), 'created_at' => now()]
            );
            return;
        }
        $notification->forceFill(['read_at' => now()])->save();
    }

    public function markAllRead(Subscriber $vendor): int
    {
        $updated = 0;
        foreach ($this->forVendor($vendor)->get() as $notification) {
            if (!$this->isRead($notification, $vendor)) {
                $this->markRead($notification, $vendor);
                $updated++;
            }
        }
        return $updated;
    }

    public function delete(Pushnotification $notification): bool
    {
        DB::table('pushnotification_reads')->where('pushnotification_id', $notification->id)->delete();
        return (bool) $notification->delete();
    }

    private function typeFor(string $category): int
    {
        return match ($category) {
            'Payments' => 1,
            'Riders' => 2,
            'Bookings' => 3,
            default => 4,
        };
    }

    private function sendPush(?Subscriber $vendor, Pushnotification $notification): string
    {
        $token = $vendor?->device_token;
        $projectId = config('services.firebase.vendor_project_id');
        if (!$token) {
            Log::warning('Vendor notification push skipped: device token is missing.', [
                'notification_id' => $notification->id,
            ]);
            return 'missing_device_token';
        }

        if (!$projectId || !config('services.firebase.vendor_credentials')) {
            Log::warning('Vendor notification push skipped: Firebase configuration is missing.', [
                'notification_id' => $notification->id,
            ]);
            return 'missing_configuration';
        }

        try {
            $accessToken = app(FirebaseAccessTokenService::class)->getVendorAccessToken();
            $response = Http::withToken($accessToken)->timeout(5)->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                ['message' => [
                    'token' => $token,
                    'notification' => ['title' => $notification->title, 'body' => $notification->content],
                    'data' => collect($notification->data ?: [])->map(fn ($value) => (string) $value)->all() + [
                        'notification_id' => (string) $notification->id,
                        'category' => (string) $notification->category,
                    ],
                ]]
            );

            if ($response->failed()) {
                $fcmError = $response->json('error');
                Log::warning('Vendor notification FCM delivery failed.', [
                    'notification_id' => $notification->id,
                    'http_status' => $response->status(),
                    'fcm_error_status' => is_array($fcmError) ? ($fcmError['status'] ?? null) : null,
                    'fcm_error_code' => is_array($fcmError) ? ($fcmError['code'] ?? null) : null,
                    'fcm_error_message' => is_array($fcmError) ? ($fcmError['message'] ?? null) : null,
                    'fcm_error_details' => $this->sanitizedFcmErrorDetails(
                        is_array($fcmError) ? ($fcmError['details'] ?? []) : []
                    ),
                ]);
                return 'push_failed';
            }

            return 'push_sent';
        } catch (Throwable $e) {
            Log::warning('Vendor notification FCM delivery failed.', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
            return 'push_failed';
        }
    }

    private function sanitizedFcmErrorDetails(mixed $details): array
    {
        if (!is_array($details)) {
            return [];
        }

        return collect($details)
            ->filter(fn ($detail) => is_array($detail))
            ->map(function (array $detail): array {
                return array_filter([
                    'type' => is_string($detail['@type'] ?? null) ? $detail['@type'] : null,
                    'error_code' => is_string($detail['errorCode'] ?? null) ? $detail['errorCode'] : null,
                    'reason' => is_string($detail['reason'] ?? null) ? $detail['reason'] : null,
                    'domain' => is_string($detail['domain'] ?? null) ? $detail['domain'] : null,
                    'field_violations' => $this->sanitizedFieldViolations($detail['fieldViolations'] ?? []),
                ], fn ($value) => $value !== null && $value !== []);
            })
            ->filter()
            ->values()
            ->all();
    }

    private function sanitizedFieldViolations(mixed $violations): array
    {
        if (!is_array($violations)) {
            return [];
        }

        return collect($violations)
            ->filter(fn ($violation) => is_array($violation))
            ->map(fn (array $violation): array => array_filter([
                    'field' => is_string($violation['field'] ?? null) ? $violation['field'] : null,
                ], fn ($value) => $value !== null))
            ->filter()
            ->values()
            ->all();
    }
}
