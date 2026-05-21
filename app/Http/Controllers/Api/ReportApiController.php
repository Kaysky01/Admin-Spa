<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReportRequest;
use App\Http\Resources\ReportResource;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\FcmToken;
use App\Services\FirebaseService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ReportApiController extends Controller
{
    public function __construct(
        protected ReportService $reportService
    ) {}

    // =========================
    // GET USER REPORTS (PAGINATED)
    // =========================
    public function index(Request $request)
    {
        $user = $request->user();

        $reports = $this->reportService->getUserReports(
            $user,
            (int) $request->get('per_page', 20)
        );

        return response()->json([
            'success'    => true,
            'data'       => ReportResource::collection($reports),
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'last_page'    => $reports->lastPage(),
                'per_page'     => $reports->perPage(),
                'total'        => $reports->total(),
            ],
        ]);
    }

    // =========================
    // GET 3 RECENT REPORTS
    // =========================
    public function myRecent(Request $request)
    {
        $reports = $this->reportService->getRecentReports($request->user());

        return response()->json([
            'success' => true,
            'data'    => ReportResource::collection($reports),
        ]);
    }

    // =========================
    // CREATE REPORT
    // =========================
    public function store(StoreReportRequest $request, FirebaseService $firebase)
    {
        $user = $request->user();

        // Rate limit check
        $rateLimitError = $this->reportService->checkRateLimit($user);
        if ($rateLimitError) {
            return response()->json([
                'success' => false,
                'message' => $rateLimitError,
            ], 429);
        }

        // Create report with processed media
        $report = $this->reportService->createReport(
            $user,
            $request->validated(),
            $request->file('media', [])
        );

        // FCM notification (non-blocking — try/catch)
        $this->sendFcmNotification($firebase, $user);

        return response()->json([
            'success' => true,
            'data'    => new ReportResource($report->load('category')),
        ], 201);
    }

    // =========================
    // GET DETAIL REPORT
    // =========================
    public function show(int $id, Request $request)
    {
        $report = $this->reportService->getReportDetail($id, $request->user());

        return response()->json([
            'success' => true,
            'data'    => new ReportResource($report),
        ]);
    }

    // =========================
    // GET CATEGORIES (CACHED)
    // =========================
    public function getCategories()
    {
        $categories = Cache::remember('categories', 3600, function () {
            return Category::select(['id', 'name', 'icon', 'description'])->get();
        });

        return response()->json([
            'success' => true,
            'data'    => CategoryResource::collection($categories),
        ]);
    }

    // =========================
    // GET REPORT DETAIL (ADMIN)
    // =========================
    public function getReportDetail(int $id)
    {
        $report = $this->reportService->getReportDetailAdmin($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => new ReportResource($report),
        ]);
    }

    // =========================
    // STATISTICS (CACHED)
    // =========================
    public function getStatistics()
    {
        return response()->json([
            'success' => true,
            'data'    => $this->reportService->getStatistics(),
        ]);
    }

    // =========================
    // PRIVATE HELPERS
    // =========================
    private function sendFcmNotification(FirebaseService $firebase, $user): void
    {
        try {
            $tokens = FcmToken::where('user_id', $user->id)->pluck('fcm_token');

            foreach ($tokens as $fcmToken) {
                $sent = $firebase->sendNotification(
                    $fcmToken,
                    'Laporan Berhasil Dikirim',
                    'Laporan Anda sedang menunggu verifikasi admin.'
                );

                if (!$sent) {
                    FcmToken::where('fcm_token', $fcmToken)->delete();
                    Log::info('FCM: Deleted invalid token');
                }
            }
        } catch (\Exception $e) {
            Log::error('FCM batch send failed', ['error' => $e->getMessage()]);
        }
    }
}
