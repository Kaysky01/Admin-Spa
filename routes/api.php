<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FcmController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Storage;
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
    Route::middleware('auth:sanctum')->put('/profile', [AuthController::class, 'updateProfile']);

    // =====================
    // REPORT API (MOBILE)
    // =====================
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/reports', [ReportApiController::class, 'index']);
        Route::post('/reports', [ReportApiController::class, 'store']);
        Route::get('/reports/{id}', [ReportApiController::class, 'show']);
    });

    Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json($request->user());
});
Route::middleware('auth:sanctum')->put('/profile', [AuthController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read', [NotificationController::class, 'markAllRead']);
});

// =====================
// FCM TOKEN API (MOBILE)
// =====================
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/fcm-token', [FcmController::class, 'store']);
    Route::post('/fcm-token/remove', [FcmController::class, 'remove']);
});
Route::post(
    '/notifications/test',
    [NotificationController::class, 'sendTestNotification']
);

//category//
 Route::get('/categories', [CategoryController::class, 'index']);



// =====================
// MEDIA FILE (Direct Serve — no redirect)
// =====================
Route::get('/media/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);

    if (!file_exists($fullPath)) {
        abort(404, 'File not found');
    }

    $mime = mime_content_type($fullPath);

    return Response::file($fullPath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=86400', // Cache 24 jam
    ]);
})->where('path', '.*');
});
