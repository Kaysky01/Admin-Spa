<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use App\Models\FcmToken;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /api/v1/notifications
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->select(['id', 'sender_role', 'message', 'status', 'is_read', 'created_at'])
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success'    => true,
            'data'       => NotificationResource::collection($notifications),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
                'total'        => $notifications->total(),
            ],
        ]);
    }

    // POST /api/v1/notifications/read
    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read',
        ]);
    }

    public function sendTestNotification(Request $request, FirebaseService $firebase)
    {
        $user   = $request->user();
        $tokens = FcmToken::where('user_id', $user->id)->pluck('fcm_token');

        foreach ($tokens as $token) {
            $firebase->sendNotification($token, 'Test Notification', 'Halo dari Laravel 🚀');
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification sent',
        ]);
    }
}
