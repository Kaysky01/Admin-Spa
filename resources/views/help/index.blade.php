@extends('layouts.app')

@section('title', 'Bantuan & Dukungan')
@section('page-title', 'Bantuan & Dukungan')

@section('content')
    <div class="row">
        <!-- Quick Links -->
        <div class="col-lg-12 mb-4">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('help.documentation') }}" class="card shadow-sm text-decoration-none hover-card">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x text-primary mb-3"></i>
                            <h5>Dokumentasi</h5>
                            <p class="text-muted mb-0">Panduan lengkap penggunaan sistem</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('help.contact') }}" class="card shadow-sm text-decoration-none hover-card">
                        <div class="card-body text-center">
                            <i class="fas fa-headset fa-3x text-success mb-3"></i>
                            <h5>Hubungi Support</h5>
                            <p class="text-muted mb-0">Kirim tiket bantuan</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="#faq-section" class="card shadow-sm text-decoration-none hover-card">
                        <div class="card-body text-center">
                            <i class="fas fa-question-circle fa-3x text-info mb-3"></i>
                            <h5>FAQ</h5>
                            <p class="text-muted mb-0">Pertanyaan yang sering diajukan</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="col-lg-12" id="faq-section">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>Frequently Asked Questions (FAQ)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}">
                                        <i class="fas fa-question-circle me-2 text-primary"></i>
                                        {{ $faq['question'] }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        {{ $faq['answer'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
    </style>
@endsection
