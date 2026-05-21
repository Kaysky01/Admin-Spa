<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FcmController;
use Illuminate\Support\Facades\Response;

Route::prefix('v1')->group(function () {

    // =====================
    // AUTH API (MOBILE)
    // =====================
    Route::prefix('auth')->group(function () {
        Route::post('/login',  [AuthController::class, 'login']);
        Route::post('/google', [AuthController::class, 'google']);
        Route::post('/logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum');
    });

    // =====================
    // PROTECTED ROUTES
    // =====================
    Route::middleware('auth:sanctum')->group(function () {

        // User profile
        Route::get('/me', function (Request $request) {
            return response()->json($request->user());
        });
        Route::put('/profile', [AuthController::class, 'updateProfile']);

        // Reports
        Route::get('/reports', [ReportApiController::class, 'index']);
        Route::post('/reports', [ReportApiController::class, 'store']);
        Route::get('/reports/{id}', [ReportApiController::class, 'show']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::post('/notifications/read', [NotificationController::class, 'markAllRead']);

        // FCM Token
        Route::post('/fcm-token', [FcmController::class, 'store']);
        Route::post('/fcm-token/remove', [FcmController::class, 'remove']);

        // Test notification (protected — requires auth)
        Route::post('/notifications/test', [NotificationController::class, 'sendTestNotification']);
    });

    // =====================
    // PUBLIC ROUTES
    // =====================
    Route::get('/categories', [CategoryController::class, 'index']);

    // =====================
    // MEDIA FILE (Direct Serve with Cache Headers)
    // =====================
    Route::get('/media/{path}', function ($path) {
        $fullPath = storage_path('app/public/' . $path);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }

        $mime = mime_content_type($fullPath);

        // Security: only serve allowed mime types
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/webp', 'image/gif',
            'video/mp4', 'video/quicktime',
        ];

        if (!in_array($mime, $allowedMimes)) {
            abort(403, 'File type not allowed');
        }

        return Response::file($fullPath, [
            'Content-Type'  => $mime,
            'Cache-Control' => 'public, max-age=604800', // 7 days cache
        ]);
    })->where('path', '.*');
});
