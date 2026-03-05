<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Category;
use App\Models\Notification;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // =========================
    // LIST REPORT (ADMIN)
    // =========================
    public function index(Request $request)
    {
        $query = Report::with(['user', 'category']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $reports = Report::with(['user', 'category'])
    ->orderBy('created_at', 'desc')
    ->paginate(15);
    	$categories = Category::all();
	return view('reports.index', compact('reports', 'categories'));
    }
    // =========================
    // DETAIL REPORT
    // =========================
    public function show($id)
    {
        $report = Report::with(['user', 'category'])->findOrFail($id);
        return view('reports.show', compact('report'));
    }

    // =========================
    // UPDATE STATUS (ADMIN)
    // =========================
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diproses,Ditindaklanjuti,Selesai,Ditolak',
            'admin_response' => 'nullable|string'
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'responded_at' => now()
        ]);

        // 🔔 NOTIFIKASI STATUS UPDATE
        Notification::create([
    'user_id' => $report->user_id,
    'sender_role' => 'admin',
    'message' => $request->admin_response 
        ? "Status laporan diperbarui menjadi {$request->status}.\n\nTanggapan Admin:\n{$request->admin_response}"
        : "Status laporan diperbarui menjadi {$request->status}.",
    'status' => match ($request->status) {
        'Ditolak' => 'rejected',
        'Selesai' => 'approved',
        default => 'processing',
    },
    'is_read' => false,
]);

        return redirect()->back()
            ->with('success', 'Status laporan berhasil diperbarui');
    }

    // =========================
    // VERIFY REPORT
    // =========================

public function store(Request $request)
{
    $user = auth()->user();

    $pendingCount = Report::where('user_id', $user->id)
        ->whereIn('status', ['Diproses'])
        ->count();

    if ($pendingCount >= 2) {
        return response()->json([
            'message' => 'Maksimal 10 laporan yang belum selesai.'
        ], 403);
    }

    $request->validate([
        'title' => 'required|string',
        'description' => 'required|string',
        'media.*' => 'file|mimes:jpg,jpeg,png,mp4,mov,avi|max:50480'
    ]);

    $mediaPaths = [];

    if ($request->hasFile('media')) {

        foreach ($request->file('media') as $file) {

            $path = $file->store('reports', 'public');

           $mediaPaths[] = $path;
        }
    }

    $report = Report::create([
        'user_id' => $user->id,
        'title' => $request->title,
        'description' => $request->description,
        'media' => $mediaPaths,
        'status' => 'Diproses'
    ]);

    return response()->json([
        'message' => 'Laporan berhasil dikirim',
        'report' => $report
    ]);
}

public function verify(Request $request, $id)
    {
        $report = Report::findOrFail($id);

        $report->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => auth()->user()->name ?? 'Admin',
            'rejection_reason' => null,
            'status' => 'Ditindaklanjuti'
        ]);

        // 🔔 NOTIFIKASI VERIFIKASI
        Notification::create([
            'user_id' => $report->user_id,
            'sender_role' => 'admin',
            'message' => 'Laporan Anda telah diverifikasi dan ditindaklanjuti.',
            'status' => 'approved',
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Laporan berhasil diverifikasi');
    }

    // =========================
    // REJECT REPORT
    // =========================
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $report = Report::findOrFail($id);

        $report->update([
            'is_verified' => false,
            'verified_at' => now(),
            'verified_by' => auth()->user()->name ?? 'Admin',
            'rejection_reason' => $request->rejection_reason,
            'status' => 'Ditolak'
        ]);

        // 🔔 NOTIFIKASI PENOLAKAN
        Notification::create([
            'user_id' => $report->user_id,
            'sender_role' => 'admin',
            'message' => 'Laporan Anda ditolak. Alasan: ' . $request->rejection_reason,
            'status' => 'rejected',
            'is_read' => false,
        ]);

        return redirect()->back()
            ->with('success', 'Laporan ditolak');
    }

   public function unverify($id)
{
    $report = Report::findOrFail($id);

    $report->update([
        'is_verified' => false,
        'verified_at' => null,
        'verified_by' => null,
        'rejection_reason' => null,
        'status' => 'Diproses', // ⬅️ WAJIB
    ]);

    // 🔔 NOTIFIKASI KE USER
    Notification::create([
        'user_id' => $report->user_id,
        'sender_role' => 'admin',
        'message' => 'Verifikasi laporan dibatalkan. Laporan kembali ke status diproses.',
        'status' => 'pending',
        'is_read' => false,
    ]);

    return redirect()->back()
        ->with('success', 'Verifikasi laporan berhasil dibatalkan');
}


    // =========================
    // DELETE REPORT
    // =========================
    public function destroy($id)
    {
        $report = Report::findOrFail($id);

        if ($report->media) {
            foreach ($report->media as $mediaPath) {
                if (file_exists(public_path($mediaPath))) {
                    unlink(public_path($mediaPath));
                }
            }
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
}
