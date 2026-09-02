<?php

namespace App\Services;

use App\Models\Pushnotification;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class VendorNotificationService
{
    public const CATEGORIES = ['Payments', 'Riders', 'Bookings', 'System'];

    public function create(Subscriber|int $vendor, string $category, string $title, string $content, array $data = []): Pushnotification
    {
        $vendorId = $vendor instanceof Subscriber ? $vendor->id : $vendor;

        $notification = Pushnotification::create([
            'subscriber_id' => $vendorId,
            'category' => in_array($category, self::CATEGORIES, true) ? $category : 'System',
            'type' => $this->typeFor($category),
            'title' => $title,
            'content' => $content,
            'data' => $data ?: null,
        ]);

        $this->sendPush($vendor instanceof Subscriber ? $vendor : Subscriber::find($vendorId), $notification);
        return $notification;
    }

    public function forVendor(Subscriber $vendor)
    {
        return Pushnotification::query()
            ->where(function ($query) use ($vendor) {
                // NULL is reserved for admin-created global/system notices.
                $query->whereNull('subscriber_id')->orWhere('subscriber_id', $vendor->id);
            });
    }

    public function isRead(Pushnotification $notification, Subscriber $vendor): bool
    {
        if ($notification->subscriber_id === null) {
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
        if ($notification->subscriber_id === null) {
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

    private function typeFor(string $category): int
    {
        return match ($category) {
            'Payments' => 1,
            'Riders' => 2,
            'Bookings' => 3,
            default => 4,
        };
    }

    private function sendPush(?Subscriber $vendor, Pushnotification $notification): void
    {
        $token = $vendor?->device_token;
        $projectId = config('services.firebase.vendor_project_id');
        $accessToken = config('services.firebase.vendor_access_token');
        if (!$token || !$projectId || !$accessToken) {
            return;
        }

        try {
            Http::withToken($accessToken)->timeout(5)->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                ['message' => [
                    'token' => $token,
                    'notification' => ['title' => $notification->title, 'body' => $notification->content],
                    'data' => collect($notification->data ?: [])->map(fn ($value) => (string) $value)->all() + [
                        'notification_id' => (string) $notification->id,
                        'category' => (string) $notification->category,
                    ],
                ]]
            )->throw();
        } catch (\Throwable $e) {
            Log::warning('Vendor notification FCM delivery failed', ['notification_id' => $notification->id, 'error' => $e->getMessage()]);
        }
    }
}
