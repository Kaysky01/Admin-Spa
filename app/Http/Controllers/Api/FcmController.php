<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FcmController extends Controller
{
    /**
     * Store or update an FCM token for the authenticated user.
     *
     * Endpoint: POST /api/v1/fcm-token
     *
     * If the token already exists (on any user), it will be reassigned
     * to the current authenticated user. This handles the case where
     * a device was previously logged in with a different account.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token'   => 'required|string|max:500',
            'device_type' => 'nullable|string|in:android,ios,web',
        ]);

        try {
            $user = Auth::user();

            // updateOrCreate keyed on fcm_token ensures:
            // - New token → insert
            // - Existing token (same or different user) → update user_id + device_type
            $fcmToken = FcmToken::updateOrCreate(
                [
                    'fcm_token' => $request->fcm_token,
                ],
                [
                    'user_id'     => $user->id,
                    'device_type' => $request->device_type ?? 'android',
                ]
            );

            Log::info('FCM token saved', [
                'user_id'     => $user->id,
                'device_type' => $fcmToken->device_type,
                'action'      => $fcmToken->wasRecentlyCreated ? 'created' : 'updated',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'FCM token registered successfully.',
                'data'    => [
                    'id'          => $fcmToken->id,
                    'device_type' => $fcmToken->device_type,
                    'created'     => $fcmToken->wasRecentlyCreated,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('FCM token save failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to save FCM token.',
            ], 500);
        }
    }

    /**
     * Remove an FCM token (e.g. on logout).
     *
     * Endpoint: POST /api/v1/fcm-token/remove
     *
     * Only removes the token if it belongs to the authenticated user,
     * preventing one user from removing another user's token.
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required|string|max:500',
        ]);

        try {
            $user = Auth::user();

            $deleted = FcmToken::where('fcm_token', $request->fcm_token)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted) {
                Log::info('FCM token removed', [
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'FCM token removed successfully.',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'message' => 'FCM token not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('FCM token removal failed', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to remove FCM token.',
            ], 500);
        }
    }
}
