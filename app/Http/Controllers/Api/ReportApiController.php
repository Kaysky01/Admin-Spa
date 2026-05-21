<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\FcmToken;
use App\Services\FirebaseService;

class ReportApiController extends Controller
{
    // =========================
    // GET USER REPORTS
    // =========================
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $reports = Report::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate($request->get('per_page', 20));

        // 🔥 UBAH MEDIA JADI URL API
        $reports->getCollection()->transform(function ($report) {
            $report->media = collect($report->media ?? [])->map(function ($file) {
                $filename = basename($file);
                return url('api/v1/media/reports/' . $filename);
            });
            return $report;
        });

        return response()->json([
            'success' => true,
            'data' => $reports->items(),
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total' => $reports->total(),
            ],
        ]);
    }

    // =========================
    // GET 3 RECENT REPORTS
    // =========================
    public function myRecent(Request $request)
    {
        $user = $request->user();

        $reports = Report::where('user_id', $user->id)
                    ->latest()
                    ->take(3)
                    ->get();

        // 🔥 UBAH MEDIA JADI URL API
        $reports->map(function ($report) {

            $report->media = collect($report->media ?? [])->map(function ($file) {

                $filename = basename($file);

                return url('api/v1/media/reports/' . $filename);

            });

            return $report;
        });

        return response()->json([
            'success' => true,
            'data' => $reports
        ]);
    }

    // =========================
    // CREATE REPORT
    // =========================
    public function store(Request $request, FirebaseService $firebase)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'nullable|string',
            'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov|max:300240',
        ]);

        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // =====================
        // LIMIT PER MENIT
        // =====================
        $reportsLastMinute = Report::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($reportsLastMinute >= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak laporan dalam waktu singkat. Coba lagi nanti.'
            ], 429);
        }

        // =====================
        // LIMIT PER HARI
        // =====================
        $reportsToday = Report::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->count();

        if ($reportsToday >= 15) {
            return response()->json([
                'success' => false,
                'message' => 'Batas maksimal laporan hari ini sudah tercapai (15 laporan).'
            ], 429);
        }

        // =====================
        // UPLOAD MEDIA
        // =====================
        $media = [];

        if ($request->hasFile('media')) {

            foreach ($request->file('media') as $file) {

                $path = $file->store('reports', 'public');

                $media[] = $path;
            }
        }

        // =====================
        // CREATE REPORT
        // =====================
        $report = Report::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'media' => $media,
            'status' => 'Diproses',
            'is_verified' => false,
        ]);

        // =====================
        // NOTIFIKASI
        // =====================
        Notification::create([
            'user_id' => $user->id,
            'sender_role' => 'sistem',
            'message' => 'Laporan berhasil dikirim dan menunggu verifikasi.',
            'status' => 'pending',
            'is_read' => false,
        ]);
        // =====================
        // PUSH NOTIFICATION (FCM)
        // =====================
        try {
            $tokens = FcmToken::where('user_id', $user->id)->get();

            foreach ($tokens as $token) {
                $sent = $firebase->sendNotification(
                    $token->fcm_token,
                    'Laporan Berhasil Dikirim',
                    'Laporan Anda sedang menunggu verifikasi admin.'
                );

                // Auto-delete invalid/expired tokens
                if (!$sent) {
                    $token->delete();
                    Log::info('FCM: Deleted invalid token', ['token_id' => $token->id]);
                }
            }
        } catch (\Exception $e) {
            // FCM failure should NEVER block report creation
            Log::error('FCM batch send failed', ['error' => $e->getMessage()]);
        }

        return response()->json([
            'success' => true,
            'data' => $report
        ], 201);
    }

    // =========================
    // GET DETAIL REPORT
    // =========================
    public function show($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $report = Report::where('id', $id)
            ->where('user_id', $user->id)
            ->with('category')
            ->firstOrFail();

        // 🔥 UBAH MEDIA JADI URL API
        $report->media = collect($report->media ?? [])->map(function ($file) {

            $filename = basename($file);

            return url('api/v1/media/reports/' . $filename);

        });

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    // =========================
    // GET CATEGORIES
    // =========================
    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => Category::all()
        ]);
    }

    // =========================
    // GET REPORT DETAIL ADMIN
    // =========================
    public function getReportDetail($id)
    {
        $report = Report::with(['category', 'user'])->find($id);

        if (!$report) {
            return response()->json([
                'success' => false,
                'message' => 'Report not found'
            ], 404);
        }

        $report->media = collect($report->media ?? [])->map(function ($file) {

            $filename = basename($file);

            return url('api/v1/media/reports/' . $filename);

        });

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    // =========================
    // STATISTICS
    // =========================
    public function getStatistics()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_reports' => Report::count(),
                'by_status' => Report::selectRaw('status, count(*) as total')
                    ->groupBy('status')
                    ->get(),
                'by_category' => Report::selectRaw('category_id, count(*) as total')
                    ->groupBy('category_id')
                    ->with('category')
                    ->get(),
            ]
        ]);
    }
}
