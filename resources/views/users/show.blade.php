@php
    use Illuminate\Support\Str;
@endphp


@extends('layouts.app')

@section('title', 'Detail Pengguna')
@section('page-title', 'Detail Pengguna')

@section('content')
    <div class="row">
        <!-- User Info Card -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); color: white; font-size: 48px; font-weight: bold;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">
                        <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                    </p>
                    @if($user->google_id)
                        <span class="badge bg-success mb-3">
                            <i class="fab fa-google me-1"></i>Google Account
                        </span>
                    @endif
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- User Stats -->
            <div class="card shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Pengguna
                    </h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total Laporan</span>
                        <strong>{{ $user->reports->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Laporan Terverifikasi</span>
                        <strong>{{ $user->reports->where('is_verified', true)->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Laporan Selesai</span>
                        <strong>{{ $user->reports->where('status', 'Selesai')->count() }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Bergabung</span>
                        <strong>{{ $user->created_at->diffForHumans() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>Riwayat Laporan ({{ $user->reports->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->reports->count() > 0)
                        <div class="list-group">
                            @foreach($user->reports as $report)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $report->title }}</h6>
                                            <small class="text-muted">
                                                <i class="fas fa-tag me-1"></i>{{ $report->category->name }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge-status badge-{{ strtolower(str_replace(' ', '-', $report->status)) }}">
                                                {{ $report->status }}
                                            </span>
                                            <br>
                                            @if($report->is_verified)
                                                <span class="badge bg-success mt-1">
                                                    <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                                </span>
                                            @elseif($report->rejection_reason)
                                                <span class="badge bg-danger mt-1">
                                                    <i class="fas fa-times-circle me-1"></i>Ditolak
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark mt-1">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="mb-2 text-muted">{{ Str::limit($report->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="far fa-calendar me-1"></i>{{ $report->created_at->format('d M Y H:i') }}
                                        </small>
                                        <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            <p>Pengguna ini belum membuat laporan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
