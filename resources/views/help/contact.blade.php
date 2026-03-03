@extends('layouts.app')

@section('title', 'Hubungi Support')
@section('page-title', 'Hubungi Support')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-ticket-alt me-2"></i>Kirim Tiket Bantuan
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('help.submit-ticket') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subjek <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" required placeholder="Ringkasan masalah Anda">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="">Pilih prioritas</option>
                                <option value="low">Rendah - Pertanyaan umum</option>
                                <option value="medium">Sedang - Masalah fungsionalitas</option>
                                <option value="high">Tinggi - Sistem error/bug critical</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required placeholder="Jelaskan masalah atau pertanyaan Anda secara detail..."></textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('help.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Contact Info -->
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Kontak
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>Email:</strong><br>
                        <a href="mailto:support@pelaporan.com">support@pelaporan.com</a>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-phone text-success me-2"></i>
                        <strong>Telepon:</strong><br>
                        <a href="tel:+62123456789">+62 123 456 789</a>
                    </div>
                    <div>
                        <i class="fas fa-clock text-warning me-2"></i>
                        <strong>Jam Operasional:</strong><br>
                        Senin - Jumat: 08:00 - 17:00<br>
                        Sabtu: 09:00 - 14:00
                    </div>
                </div>
            </div>

            <!-- Response Time -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="fas fa-clock me-2"></i>Waktu Respons
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <span class="badge bg-danger">Tinggi</span> 
                            <small class="text-muted">≤ 2 jam</small>
                        </li>
                        <li class="mb-2">
                            <span class="badge bg-warning text-dark">Sedang</span> 
                            <small class="text-muted">≤ 1 hari</small>
                        </li>
                        <li>
                            <span class="badge bg-success">Rendah</span> 
                            <small class="text-muted">≤ 3 hari</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
