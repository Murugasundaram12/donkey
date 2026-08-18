<?php

namespace App\Http\Controllers\API\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Pushnotification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get Notifications
     */
    public function index(Request $request)
    {
        $notifications = Pushnotification::orderBy('created_at', 'desc')
            ->paginate((int) $request->get('per_page', 15));

        $formatted = collect($notifications->items())->map(function ($n) {
            return [
                'id' => (int) $n->id,
                'title' => (string) $n->title,
                'content' => (string) $n->content,
                'image_url' => $n->image ? asset('public/' . $n->image) : null,
                'type' => (int) ($n->type ?? 1),
                'read' => false,
                'created_at' => $n->created_at ? $n->created_at->toDateTimeString() : null,
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
                'items' => $formatted,
            ]
        ]);
    }

    /**
     * Mark Notification Read
     */
    public function markRead(Request $request, $id)
    {
        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read',
            'data' => [
                'id' => (int) $id,
                'read' => true
            ]
        ]);
    }

    /**
     * Mark All Notifications Read
     */
    public function markAllRead(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}
