<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Get all notifications for a user
     */
    public function index($userId)
    {
        $notifications = Notification::where('user_id', $userId)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $notifications,
        ]);
    }

    /**
     * Create notification
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string|max:50',
            'reference_id' => 'nullable|integer',
            'reference_type' => 'nullable|string|max:50',
            'created_by' => 'nullable|integer',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'reference_id' => $request->reference_id,
            'reference_type' => $request->reference_type,
            'is_read' => false,
            'is_active' => true,
            'created_by' => $request->created_by,
            'created_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification created successfully',
            'data' => $notification,
        ]);
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->update([
            'is_read' => true,
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read',
        ]);
    }

    /**
     * Mark all notifications as read for user
     */
    public function markAllAsRead($userId)
    {
        Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now(),
            ]);

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    /**
     * Delete notification (soft delete using is_active)
     */
    public function delete($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->update([
            'is_active' => false,
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification deleted successfully',
        ]);
    }
}