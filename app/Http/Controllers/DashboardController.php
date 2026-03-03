<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik umum
        $totalReports = Report::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        
        // Statistik waktu
        $reportsToday = Report::whereDate('created_at', today())->count();
        $reportsThisWeek = Report::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $reportsThisMonth = Report::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        
        // Statistik pengguna aktif
        $activeUsersToday = Report::whereDate('created_at', today())->distinct('user_id')->count('user_id');
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        
        // Statistik verifikasi
        $verifiedReports = Report::where('is_verified', true)->count();
        $pendingVerification = Report::where('is_verified', false)->whereNull('rejection_reason')->count();
        $rejectedReports = Report::whereNotNull('rejection_reason')->count();
        $verificationRate = $totalReports > 0 ? round(($verifiedReports / $totalReports) * 100, 1) : 0;
        
        // Laporan berdasarkan status
        $reportsByStatus = Report::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        
        // Persentase status
        $totalForPercentage = $totalReports > 0 ? $totalReports : 1;
        $statusPercentages = [
            'Diproses' => round(($reportsByStatus->where('status', 'Diproses')->first()->total ?? 0) / $totalForPercentage * 100, 1),
            'Ditindaklanjuti' => round(($reportsByStatus->where('status', 'Ditindaklanjuti')->first()->total ?? 0) / $totalForPercentage * 100, 1),
            'Selesai' => round(($reportsByStatus->where('status', 'Selesai')->first()->total ?? 0) / $totalForPercentage * 100, 1)
        ];
        
        // Response rate admin (laporan yang sudah ditanggapi)
        $respondedReports = Report::whereNotNull('admin_response')->count();
        $responseRate = $totalReports > 0 ? round(($respondedReports / $totalReports) * 100, 1) : 0;
        
        // Rata-rata waktu response (dalam jam)
        $avgResponseTime = Report::whereNotNull('responded_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, responded_at)) as avg_hours')
            ->first()->avg_hours ?? 0;
        $avgResponseTime = round($avgResponseTime, 1);
        
        // Laporan berdasarkan kategori
        $reportsByCategory = Report::with('category')
            ->select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->orderBy('total', 'desc')
            ->get();
        
        // Top 3 kategori
        $topCategories = $reportsByCategory->take(3);
        
        // Laporan dengan media
        $reportsWithMedia = Report::whereNotNull('media')->count();
        $mediaPercentage = $totalReports > 0 ? round(($reportsWithMedia / $totalReports) * 100, 1) : 0;
        
        // Laporan terbaru
        $recentReports = Report::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();
        
        // Data grafik: Laporan per hari (7 hari terakhir)
        $dailyReports = Report::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Data grafik: Laporan per bulan (6 bulan terakhir)
        $monthlyReports = Report::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', compact(
            'totalReports',
            'totalUsers',
            'totalCategories',
            'reportsToday',
            'reportsThisWeek',
            'reportsThisMonth',
            'activeUsersToday',
            'newUsersThisMonth',
            'verifiedReports',
            'pendingVerification',
            'rejectedReports',
            'verificationRate',
            'reportsByStatus',
            'statusPercentages',
            'responseRate',
            'avgResponseTime',
            'reportsByCategory',
            'topCategories',
            'reportsWithMedia',
            'mediaPercentage',
            'recentReports',
            'dailyReports',
            'monthlyReports'
        ));
    }
}
