<?php

namespace App\Http\Controllers;

use App\Models\PortalNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = PortalNotification::where('user_id', auth()->id())
            ->latest()
            ->limit(5)
            ->get();

        $unreadCount = PortalNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count'  => $unreadCount,
        ]);
    }

    public function markRead($id)
    {
        PortalNotification::where('_id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllRead()
    {
        PortalNotification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
