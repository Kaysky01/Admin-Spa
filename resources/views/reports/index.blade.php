@php
    use Illuminate\Support\Str;
@endphp


@extends('layouts.app')

@section('title', 'Kelola Laporan')
@section('page-title', 'Kelola Laporan')

@section('content')
    <div class="table-container">
        <!-- Filter Section -->
        <div class="mb-4">
            <form action="{{ route('reports.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Cari laporan..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Ditindaklanjuti" {{ request('status') == 'Ditindaklanjuti' ? 'selected' : '' }}>Ditindaklanjuti</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Summary -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Total: {{ $reports->total() }} laporan</strong>
                </div>
            </div>
        </div>

        <!-- Reports Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Verifikasi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>#{{ $reports->firstItem() + $loop->index }}</td>
                            <td>
                                <strong>{{ Str::limit($report->title, 50) }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($report->description, 80) }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $report->category->name }}</span>
                            </td>
                            <td>
                                <i class="fas fa-user me-1"></i>{{ $report->user->name ?? 'N/A' }}
                                <br>
                                <small class="text-muted">{{ $report->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge-status badge-{{ strtolower(str_replace(' ', '-', $report->status)) }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td>
                                @if($report->is_verified)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @elseif($report->rejection_reason)
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i>Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                <i class="far fa-calendar me-1"></i>{{ $report->created_at->format('d M Y') }}
                                <br>
                                <small class="text-muted">{{ $report->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-info" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(session('admin_role') == 'super_admin')
                                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                Tidak ada laporan ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $reports->links() }}
        </div>
    </div>
@endsection
