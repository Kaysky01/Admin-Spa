
@php
    use Illuminate\Support\Str;
@endphp


@extends('layouts.app')

@section('title', 'Profile Admin')
@section('page-title', 'Profile Admin')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($admin->avatar)
                            <img src="{{ asset('storage/' . $admin->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); color: white; font-size: 60px; font-weight: bold;">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas {{ $admin->role == 'super_admin' ? 'fa-crown' : 'fa-shield-alt' }} me-1"></i>
                        {{ $admin->role == 'super_admin' ? 'Super Admin' : 'Admin' }}
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-key me-2"></i>Ubah Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Aktivitas Anda
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Laporan Diverifikasi</span>
                        <strong>{{ \App\Models\Report::where('is_verified', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Laporan Ditangani</span>
                        <strong>{{ \App\Models\Report::whereIn('status', ['Ditindaklanjuti', 'Selesai'])->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Bergabung Sejak</span>
                        <strong>{{ $admin->created_at->format('M Y') }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Informasi Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Nama Lengkap</label>
                            <p class="mb-0 fw-bold">{{ $admin->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Email</label>
                            <p class="mb-0 fw-bold">{{ $admin->email }}</p>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted mb-1">Role</label>
                            <p class="mb-0">
                                <span class="badge {{ $admin->role == 'super_admin' ? 'bg-danger' : 'bg-primary' }}">
                                    {{ $admin->role == 'super_admin' ? 'Super Admin' : 'Admin' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">
                                <i class="fas fa-phone me-1"></i>Nomor Telepon
                            </label>
                            <p class="mb-0 fw-bold">{{ $admin->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">
                                <i class="fas fa-calendar me-1"></i>Bergabung
                            </label>
                            <p class="mb-0 fw-bold">{{ $admin->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="text-muted mb-1">
                                <i class="fas fa-map-marker-alt me-1"></i>Alamat
                            </label>
                            <p class="mb-0 fw-bold">{{ $admin->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Info -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                        <div>
                            <h6 class="mb-1">Password</h6>
                            <small class="text-muted">Terakhir diubah: 30 hari yang lalu</small>
                        </div>
                        <a href="{{ route('profile.change-password') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-key me-1"></i>Ubah
                        </a>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="mb-1">Autentikasi Dua Faktor</h6>
                            <small class="text-muted">Tingkatkan keamanan akun Anda</small>
                        </div>
                        <span class="badge bg-secondary">Segera Hadir</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Aktivitas Terkini
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $recentReports = \App\Models\Report::with('category')
                            ->where('is_verified', true)
                            ->latest('verified_at')
                            ->take(5)
                            ->get();
                    @endphp

                    @if($recentReports->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentReports as $report)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">Memverifikasi laporan</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>{{ $report->category->name }} • 
                                                {{ $report->verified_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <span class="badge bg-success">Verified</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted mb-0">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
