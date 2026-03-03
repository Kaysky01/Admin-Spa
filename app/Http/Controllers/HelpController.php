<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara memverifikasi laporan?',
                'answer' => 'Buka detail laporan, lalu klik tombol "Verifikasi Laporan" untuk menyetujui atau "Tolak Laporan" untuk menolak dengan memberikan alasan.'
            ],
            [
                'question' => 'Bagaimana cara menambah kategori baru?',
                'answer' => 'Buka menu "Kelola Kategori", klik tombol "Tambah Kategori", isi form yang tersedia, dan simpan.'
            ],
            [
                'question' => 'Bagaimana cara menghapus laporan?',
                'answer' => 'Buka halaman "Kelola Laporan", klik tombol hapus (ikon trash) pada laporan yang ingin dihapus, lalu konfirmasi penghapusan.'
            ],
            [
                'question' => 'Bagaimana cara mengubah status laporan?',
                'answer' => 'Buka detail laporan, scroll ke bawah, ubah status pada dropdown, tambahkan tanggapan admin jika perlu, lalu klik "Update Status".'
            ],
            [
                'question' => 'Bagaimana cara melihat statistik?',
                'answer' => 'Buka menu "Dashboard & Statistik" untuk melihat overview lengkap semua laporan, grafik, dan metrik penting.'
            ],
            [
                'question' => 'Apa perbedaan status verifikasi?',
                'answer' => 'Pending: Menunggu review admin. Terverifikasi: Laporan valid dan akan ditindaklanjuti. Ditolak: Laporan tidak memenuhi kriteria dengan alasan yang diberikan.'
            ],
        ];

        return view('help.index', compact('faqs'));
    }

    public function documentation()
    {
        return view('help.documentation');
    }

    public function contact()
    {
        return view('help.contact');
    }

    public function submitTicket(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ]);

        // In real application, save ticket to database or send email
        
        return redirect()->back()->with('success', 'Tiket bantuan berhasil dikirim! Tim kami akan segera merespons.');
    }
}
