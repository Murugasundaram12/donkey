<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pushnotification;
use App\Services\VendorNotificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function unreadCount(Request $request)
    {
        $count = app(VendorNotificationService::class)->unreadCount($request->user());

        return response()->json(['status' => true, 'data' => ['unread_count' => $count]]);
    }

    /**
     * Get Notifications
     */
    public function index(Request $request)
    {
        $vendor = $request->user();
        $category = $request->input('category');
        $query = app(VendorNotificationService::class)->forVendor($vendor);

        if ($category && $category !== 'All') {
            $request->validate(['category' => ['required', Rule::in(VendorNotificationService::CATEGORIES)]]);
            $query->where('category', $category);
        }

        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate((int) $request->get('per_page', 15));

        $formatted = collect($notifications->items())->map(function ($n) use ($vendor) {
            $isRead = app(VendorNotificationService::class)->isRead($n, $vendor);
            return [
                'id' => (int) $n->id,
                'title' => (string) $n->title,
                'content' => (string) $n->content,
                'message' => (string) $n->content,
                'image_url' => $n->image ? asset('public/' . $n->image) : null,
                'type' => (int) ($n->type ?? 1),
                'category' => (string) ($n->category ?: 'System'),
                'is_read' => $isRead,
                'read' => $isRead,
                'created_at' => $n->created_at ? $n->created_at->toDateTimeString() : null,
                'data' => $n->data ?: null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Notifications retrieved successfully',
            'data' => [
                'current_page' => $notifications->currentPage(),
                'per_page' => $notifications->perPage(),
                'total' => $notifications->total(),
                'last_page' => $notifications->lastPage(),
                'unread_count' => app(VendorNotificationService::class)->unreadCount($vendor),
                'items' => $formatted,
            ]
        ]);
    }

    /**
     * Mark Notification Read
     */
    public function markRead(Request $request, $id)
    {
        $notification = app(VendorNotificationService::class)->forVendor($request->user())->where('id', $id)->first();
        if (!$notification) {
            return response()->json(['status' => false, 'message' => 'Notification not found.'], 404);
        }
        app(VendorNotificationService::class)->markRead($notification, $request->user());

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read',
            'data' => [
                'id' => (int) $notification->id,
                'read' => true
            ]
        ]);
    }

    /**
     * Mark All Notifications Read
     */
    public function markAllRead(Request $request)
    {
        $updated = app(VendorNotificationService::class)->markAllRead($request->user());

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read',
            'data' => ['updated' => $updated]
        ]);
    }

    /**
     * Send Test Notification to Authenticated Vendor
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'category' => ['nullable', 'string', Rule::in(VendorNotificationService::CATEGORIES)],
            'title' => 'required|string',
            'content' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $vendor = $request->user();
        $category = $request->input('category', 'System');
        $title = $request->input('title');
        $content = $request->input('content');
        $data = $request->input('data', []);

        $notification = app(VendorNotificationService::class)->create(
            $vendor,
            $category,
            $title,
            $content,
            $data
        );

        return response()->json([
            'status' => true,
            'message' => 'Test notification sent successfully',
            'data' => [
                'id' => (int) $notification->id,
                'title' => (string) $notification->title,
                'content' => (string) $notification->content,
                'category' => (string) $notification->category,
                'is_read' => false,
                'data' => $notification->data,
                'created_at' => $notification->created_at ? $notification->created_at->toDateTimeString() : null,
            ]
        ], 200);
    }
}
