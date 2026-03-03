@extends('layouts.app')

@section('title', 'Akses Ditolak')
@section('page-title', 'Akses Ditolak')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-ban" style="font-size: 100px; color: #dc3545;"></i>
                    </div>
                    <h2 class="mb-3">Akses Ditolak!</h2>
                    <p class="text-muted mb-4">
                        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                        Fitur ini hanya dapat diakses oleh <strong>Super Admin</strong>.
                    </p>

                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Role Anda:</strong> 
                        @if(session('admin_role') == 'super_admin')
                            Super Admin
                        @else
                            Admin
                        @endif
                    </div>

                    <div class="mb-4">
                        <h6 class="mb-3">Hak Akses Admin Biasa:</h6>
                        <ul class="list-unstyled text-start" style="max-width: 400px; margin: 0 auto;">
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Melihat Dashboard & Statistik
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Melihat daftar Laporan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Memverifikasi Laporan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Menolak Laporan dengan alasan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Mengubah Status Laporan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Memberikan Tanggapan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>Melihat Kategori
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-times-circle text-danger me-2"></i>Menghapus Laporan
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-times-circle text-danger me-2"></i>Mengelola Kategori
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-times-circle text-danger me-2"></i>Manajemen Pengguna
                            </li>
                            <li>
                                <i class="fas fa-times-circle text-danger me-2"></i>Pengaturan Sistem
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
