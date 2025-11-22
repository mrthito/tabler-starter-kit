<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get all notifications.
     */
    public function index()
    {
        $notifications = AdminNotification::latest()->paginate(10);
        return response()->json([
            'notifications' => $notifications,
            'message' => 'Notifications fetched successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        $count = AdminNotification::where('read', false)->count();
        return response()->json([
            'count' => $count,
            'message' => 'Unread count fetched successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Mark as read and return url.
     */
    public function update(Request $request, AdminNotification $notification)
    {
        // mark as read and return url
        $notification->update(['read' => true]);

        // Refresh the model to get the latest state
        $notification->refresh();

        return response()->json([
            'url' => $notification->url,
            'message' => 'Notification marked as read successfully',
            'status' => 'success',
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy(AdminNotification $notification)
    {
        $notification->delete();
        return response()->json([
            'message' => 'Notification deleted successfully',
            'status' => 'success',
        ]);
    }
}
