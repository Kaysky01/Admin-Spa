<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\FcmToken;
use App\Services\FirebaseService;

class NotificationController extends Controller
{
    // GET /api/notifications
    public function index(Request $request)
    {
        return Notification::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // POST /api/notifications/read
    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }
    
    public function sendTestNotification(
    Request $request,
    FirebaseService $firebase
)
{
    $user = $request->user();

    $tokens = FcmToken::where('user_id', $user->id)->get();

    foreach ($tokens as $token) {

        $firebase->sendNotification(
            $token->fcm_token,
            'Test Notification',
            'Halo dari Laravel 🚀'
        );
    }

    return response()->json([
        'success' => true,
        'message' => 'Notification sent'
    ]);
}
}
