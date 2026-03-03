

@extends('layouts.app')

@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
    <div class="row">
        <!-- Report Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>Detail Laporan #{{ $report->id }}
                        </h5>
                        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4>{{ $report->title }}</h4>
                        <span class="badge-status badge-{{ strtolower(str_replace(' ', '-', $report->status)) }}">
                            {{ $report->status }}
                        </span>
                        <span class="badge bg-secondary ms-2">{{ $report->category->name }}</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-align-left me-1"></i>Deskripsi
                        </h6>
                        <p style="white-space: pre-wrap;">{{ $report->description }}</p>
                    </div>

                    @if($report->location)
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i>Lokasi
                        </h6>
                        <p>{{ $report->location }}</p>
                    </div>
                    @endif

                   @if($report->media && count($report->media) > 0)
<div class="mb-4">
    <h6 class="text-muted mb-3">
        <i class="fas fa-images me-1"></i>Media Lampiran
    </h6>
    
    <div class="row g-3">
        @foreach($report->media as $media)
            {{-- Cek Tipe File (Image vs Video) --}}
            @php
                $isImage = \Illuminate\Support\Str::endsWith(strtolower($media), ['.jpg', '.jpeg', '.png', '.gif', '.webp']);
                $isVideo = \Illuminate\Support\Str::endsWith(strtolower($media), ['.mp4', '.mov', '.avi', '.webm']);
            @endphp

            {{-- TAMPILAN GAMBAR --}}
            @if($isImage)
                <div class="col-md-6 col-lg-4">
                    {{-- Container Kotak Abu-abu --}}
                    <div class="border rounded bg-light p-2 d-flex align-items-center justify-content-center" style="height: 300px;">
                        {{-- Link supaya bisa diklik --}}
                        <a href="{{ asset('storage/' . $media) }}" target="_blank" title="Klik untuk memperbesar">
                            <img src="{{ asset('storage/' . $media) }}" 
                                 class="rounded shadow-sm" 
                                 style="max-height: 280px; max-width: 100%; object-fit: contain;" 
                                 alt="Bukti Laporan">
                        </a>
                    </div>
                    <div class="text-center mt-1">
                        <small class="text-muted"><i class="fas fa-search-plus me-1"></i>Klik untuk zoom</small>
                    </div>
                </div>

            {{-- TAMPILAN VIDEO --}}
            @elseif($isVideo)
                <div class="col-md-6 col-lg-4">
                    {{-- Container Kotak Abu-abu --}}
                    <div class="border rounded bg-light p-2 d-flex align-items-center justify-content-center" style="height: 300px;">
                        <video controls class="rounded shadow-sm" style="max-height: 280px; max-width: 100%;">
                            <source src="{{ asset('storage/' . $media) }}" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                    <div class="text-center mt-1">
                        <small class="text-muted"><i class="fas fa-video me-1"></i>Video Bukti</small>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif

                    @if($report->admin_response)
                    <div class="alert alert-info">
                        <h6 class="mb-2">
                            <i class="fas fa-reply me-1"></i>Tanggapan Admin
                        </h6>
                        <p class="mb-1" style="white-space: pre-wrap;">{{ $report->admin_response }}</p>
                        <small class="text-muted">
                            <i class="far fa-clock me-1"></i>{{ $report->responded_at->format('d M Y H:i') }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Report Info & Actions -->
        <div class="col-md-4">
            <!-- Reporter Info -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-user me-2"></i>Informasi Pelapor
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Nama</small>
                        <p class="mb-0"><strong>{{ $report->user->name ?? 'N/A' }}</strong></p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Email</small>
                        <p class="mb-0">{{ $report->user->email ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Tanggal Laporan</small>
                        <p class="mb-0">{{ $report->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <div>
                        <small class="text-muted">Terakhir Diupdate</small>
                        <p class="mb-0">{{ $report->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Verification Status -->
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-check me-2"></i>Status Verifikasi
                    </h6>
                </div>
                <div class="card-body">
                   @if($report->is_verified)
    <div class="alert alert-success mb-3">
        <i class="fas fa-check-circle me-2"></i><strong>Terverifikasi</strong>
        <p class="mb-0 mt-2 small">
            Oleh: {{ $report->verified_by }}<br>
            Pada: {{ $report->verified_at->format('d M Y H:i') }}
        </p>
    </div>
     </form>

@elseif($report->rejection_reason)
    <div class="alert alert-danger mb-3">
        <i class="fas fa-times-circle me-2"></i><strong>Ditolak</strong>
        <p class="mb-0 mt-2 small">
            Alasan: {{ $report->rejection_reason }}<br>
            Oleh: {{ $report->verified_by }}<br>
            Pada: {{ $report->verified_at->format('d M Y H:i') }}
        </p>
    </div>

@else
    <div class="alert alert-warning mb-3">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Menunggu Verifikasi</strong>
        <p class="mb-0 mt-2 small">Laporan ini perlu diverifikasi oleh admin</p>
    </div>
@endif


                    @if(!$report->is_verified && !$report->rejection_reason)
                        <form action="{{ route('reports.verify', $report->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-check me-1"></i>Verifikasi Laporan
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-1"></i>Tolak Laporan
                        </button>
                    @elseif($report->rejection_reason)
                        <form action="{{ route('reports.verify', $report->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-redo me-1"></i>Verifikasi Ulang
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Update Status -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Update Status
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('reports.update-status', $report->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Diproses" {{ $report->status == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="Ditindaklanjuti" {{ $report->status == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                                <option value="Selesai" {{ $report->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggapan Admin</label>
                            <textarea name="admin_response" class="form-control" rows="4" placeholder="Berikan tanggapan untuk laporan ini...">{{ $report->admin_response }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-times-circle me-2"></i>Tolak Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('reports.reject', $report->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Anda akan menolak laporan ini. Silakan berikan alasan penolakan.
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Contoh: Bukti tidak valid, duplikat laporan, dll"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>Tolak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 10px;
    }
    
    .card-header {
        border-bottom: 1px solid #E9ECEF;
        padding: 20px;
    }
</style>
@endsection
