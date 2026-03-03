@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- System Information -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Nama Aplikasi</td>
                            <td><strong>{{ $settings['app_name'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">URL</td>
                            <td><strong>{{ $settings['app_url'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Timezone</td>
                            <td><strong>{{ $settings['timezone'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Database</td>
                            <td><strong>{{ $settings['db_connection'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cache Driver</td>
                            <td><strong>{{ $settings['cache_driver'] }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Laravel Version</td>
                            <td><strong>{{ app()->version() }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">PHP Version</td>
                            <td><strong>{{ phpversion() }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <form action="{{ route('settings.clear-cache') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100 text-start">
                                <i class="fas fa-broom me-2"></i>Bersihkan Cache
                                <small class="d-block text-muted">Hapus cache aplikasi, config, view, dan route</small>
                            </button>
                        </form>

                        <form action="{{ route('settings.optimize') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100 text-start">
                                <i class="fas fa-tachometer-alt me-2"></i>Optimasi Sistem
                                <small class="d-block text-muted">Optimasi performa aplikasi</small>
                            </button>
                        </form>

                        <a href="{{ route('settings.database') }}" class="btn btn-outline-info w-100 text-start">
                            <i class="fas fa-database me-2"></i>Database Info
                            <small class="d-block text-muted">Lihat informasi database</small>
                        </a>

                        <form action="{{ route('settings.maintenance') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100 text-start">
                                <i class="fas fa-wrench me-2"></i>Mode Maintenance
                                <small class="d-block text-muted">
                                    @if(file_exists(storage_path('framework/down')))
                                        Status: <span class="text-danger">AKTIF</span> - Klik untuk nonaktifkan
                                    @else
                                        Status: <span class="text-success">NONAKTIF</span> - Klik untuk aktifkan
                                    @endif
                                </small>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Storage Information -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hdd me-2"></i>Informasi Penyimpanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <i class="fas fa-folder fa-3x text-primary mb-2"></i>
                                <h6 class="text-muted">Total Reports</h6>
                                <h4>{{ \App\Models\Report::count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <i class="fas fa-users fa-3x text-success mb-2"></i>
                                <h6 class="text-muted">Total Users</h6>
                                <h4>{{ \App\Models\User::count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <i class="fas fa-tags fa-3x text-warning mb-2"></i>
                                <h6 class="text-muted">Total Categories</h6>
                                <h4>{{ \App\Models\Category::count() }}</h4>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center p-3">
                                <i class="fas fa-images fa-3x text-info mb-2"></i>
                                <h6 class="text-muted">Reports with Media</h6>
                                <h4>{{ \App\Models\Report::whereNotNull('media')->count() }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
