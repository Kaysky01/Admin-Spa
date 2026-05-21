<?php

namespace App\Services;

use App\Models\Report;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;

class ReportService
{
    public function __construct(
        protected ImageService $imageService
    ) {}

    /**
     * Get paginated reports for a user.
     */
    public function getUserReports(User $user, int $perPage = 20)
    {
        return Report::where('user_id', $user->id)
            ->select([
                'id', 'user_id', 'category_id', 'title', 'description',
                'location', 'media', 'status', 'admin_response',
                'responded_at', 'is_verified', 'rejection_reason', 'created_at',
            ])
            ->with('category:id,name')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get recent reports for dashboard.
     */
    public function getRecentReports(User $user, int $limit = 3)
    {
        return Report::where('user_id', $user->id)
            ->select([
                'id', 'user_id', 'category_id', 'title',
                'status', 'media', 'created_at',
            ])
            ->with('category:id,name')
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get single report detail.
     */
    public function getReportDetail(int $id, ?User $user = null)
    {
        $query = Report::with(['category:id,name'])
            ->select([
                'id', 'user_id', 'category_id', 'title', 'description',
                'location', 'media', 'status', 'admin_response',
                'responded_at', 'is_verified', 'rejection_reason', 'created_at',
            ]);

        if ($user) {
            $query->where('user_id', $user->id);
        }

        return $query->findOrFail($id);
    }

    /**
     * Get report detail with user info (admin view).
     */
    public function getReportDetailAdmin(int $id)
    {
        return Report::with(['category:id,name', 'user:id,name,email'])
            ->select([
                'id', 'user_id', 'category_id', 'title', 'description',
                'location', 'media', 'status', 'admin_response',
                'responded_at', 'is_verified', 'rejection_reason', 'created_at',
            ])
            ->findOrFail($id);
    }

    /**
     * Check rate limits. Returns error message or null if OK.
     */
    public function checkRateLimit(User $user): ?string
    {
        $reportsLastMinute = Report::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinute())
            ->count();

        if ($reportsLastMinute >= 1) {
            return 'Terlalu banyak laporan dalam waktu singkat. Coba lagi nanti.';
        }

        $reportsToday = Report::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();

        if ($reportsToday >= 15) {
            return 'Batas maksimal laporan hari ini sudah tercapai (15 laporan).';
        }

        return null;
    }

    /**
     * Create a new report with media processing.
     */
    public function createReport(User $user, array $data, array $uploadedFiles = []): Report
    {
        $media = [];
        foreach ($uploadedFiles as $file) {
            $media[] = $this->imageService->processAndStore($file, 'reports');
        }

        $report = Report::create([
            'user_id'     => $user->id,
            'category_id' => $data['category_id'],
            'title'       => $data['title'],
            'description' => $data['description'],
            'location'    => $data['location'] ?? null,
            'media'       => $media,
            'status'      => 'Diproses',
            'is_verified' => false,
        ]);

        // Create system notification
        Notification::create([
            'user_id'     => $user->id,
            'sender_role' => 'sistem',
            'message'     => 'Laporan berhasil dikirim dan menunggu verifikasi.',
            'status'      => 'pending',
            'is_read'     => false,
        ]);

        // Clear statistics cache
        Cache::forget('report_statistics');

        return $report;
    }

    /**
     * Get cached statistics.
     */
    public function getStatistics(): array
    {
        return Cache::remember('report_statistics', 300, function () {
            $statusCounts = Report::selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->get();

            $categoryCounts = Report::selectRaw('category_id, count(*) as total')
                ->groupBy('category_id')
                ->with('category:id,name')
                ->get();

            return [
                'total_reports' => Report::count(),
                'by_status'     => $statusCounts,
                'by_category'   => $categoryCounts,
            ];
        });
    }
}
