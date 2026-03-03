@extends('layouts.app')

@section('title', 'Dokumentasi')
@section('page-title', 'Dokumentasi Sistem')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-book me-2"></i>Panduan Penggunaan Sistem
                </h5>
                <a href="{{ route('help.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Dashboard -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-chart-line me-2 text-primary"></i>1. Dashboard & Statistik</h4>
                <p>Dashboard menampilkan overview lengkap dari sistem pelaporan akademik:</p>
                <ul>
                    <li>Ringkasan hari ini dengan 5 metrik utama</li>
                    <li>Grafik tren harian (7 hari terakhir)</li>
                    <li>Statistik per status dan kategori</li>
                    <li>Laporan terbaru dan aktivitas verifikasi</li>
                </ul>
            </section>

            <!-- Reports -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-file-alt me-2 text-success"></i>2. Kelola Laporan</h4>
                <p>Menu untuk mengelola semua laporan yang masuk dari pengguna:</p>
                <ul>
                    <li><strong>Filter & Search:</strong> Cari laporan berdasarkan kata kunci, status, atau kategori</li>
                    <li><strong>Verifikasi Laporan:</strong> Buka detail laporan → Klik "Verifikasi Laporan" atau "Tolak Laporan"</li>
                    <li><strong>Update Status:</strong> Ubah status (Diproses → Ditindaklanjuti → Selesai)</li>
                    <li><strong>Berikan Tanggapan:</strong> Tambahkan respon admin untuk user</li>
                    <li><strong>Hapus Laporan:</strong> Klik tombol hapus jika laporan tidak valid</li>
                </ul>
            </section>

            <!-- Categories -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-tags me-2 text-warning"></i>3. Kelola Kategori</h4>
                <p>Mengelola kategori pelanggaran:</p>
                <ul>
                    <li><strong>Tambah Kategori:</strong> Klik "Tambah Kategori" → Isi nama, ikon, deskripsi → Simpan</li>
                    <li><strong>Edit Kategori:</strong> Klik tombol edit → Ubah data → Simpan</li>
                    <li><strong>Hapus Kategori:</strong> Klik tombol hapus (pastikan tidak ada laporan terkait)</li>
                </ul>
            </section>

            <!-- Users -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-users me-2 text-info"></i>4. Manajemen Pengguna</h4>
                <p>Mengelola data pengguna aplikasi:</p>
                <ul>
                    <li>Lihat daftar semua pengguna</li>
                    <li>Cek statistik laporan per pengguna</li>
                    <li>Lihat riwayat lengkap laporan user</li>
                    <li>Hapus pengguna jika diperlukan</li>
                </ul>
            </section>

            <!-- Settings -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-cog me-2 text-secondary"></i>5. Pengaturan</h4>
                <p>Konfigurasi dan maintenance sistem:</p>
                <ul>
                    <li><strong>Bersihkan Cache:</strong> Hapus cache untuk update perubahan</li>
                    <li><strong>Optimasi Sistem:</strong> Tingkatkan performa aplikasi</li>
                    <li><strong>Mode Maintenance:</strong> Aktifkan saat maintenance</li>
                    <li><strong>Database Info:</strong> Lihat struktur database</li>
                </ul>
            </section>

            <!-- API Integration -->
            <section class="mb-4">
                <h4 class="mb-3"><i class="fas fa-plug me-2 text-danger"></i>6. Integrasi API</h4>
                <p>Sistem terintegrasi penuh dengan Flutter Mobile App melalui REST API:</p>
                <ul>
                    <li>Base URL: <code>http://127.0.0.1:8000/api/v1</code></li>
                    <li>Dokumentasi lengkap tersedia di file <strong>API_INTEGRATION.md</strong></li>
                    <li>Semua perubahan di admin panel otomatis tersinkronisasi ke mobile app</li>
                </ul>
            </section>

            <hr class="my-4">

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Butuh bantuan lebih lanjut?</strong> 
                Hubungi tim support melalui menu <a href="{{ route('help.contact') }}">Hubungi Support</a>
            </div>
        </div>
    </div>
@endsection
