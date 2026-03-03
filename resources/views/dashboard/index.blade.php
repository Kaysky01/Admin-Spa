

@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Header Statistics - Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info mb-0">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h5 class="mb-2"><i class="fas fa-chart-line me-2"></i>Ringkasan Hari Ini</h5>
                        <p class="mb-0">
                            <strong>{{ $reportsToday }}</strong> laporan baru | 
                            <strong>{{ $activeUsersToday }}</strong> pengguna aktif | 
                            <strong>{{ $pendingVerification }}</strong> menunggu verifikasi | 
                            <strong>{{ $responseRate }}%</strong> tingkat respons | 
                            <strong>{{ $avgResponseTime }}</strong> jam rata-rata respons
                        </p>
                    </div>
                    <div class="col-md-3 text-end">
                        <i class="fas fa-calendar-day" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="icon" style="background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="number">{{ $totalReports }}</div>
                <div class="label">Total Laporan</div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-arrow-up text-success"></i> {{ $reportsThisMonth }} bulan ini
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="icon" style="background: linear-gradient(135deg, #F093FB 0%, #F5576C 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="number">{{ $totalUsers }}</div>
                <div class="label">Total Pengguna</div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-user-plus text-success"></i> {{ $newUsersThisMonth }} pengguna baru
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="icon" style="background: linear-gradient(135deg, #4FACFE 0%, #00F2FE 100%);">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="number">{{ $responseRate }}%</div>
                <div class="label">Tingkat Respons</div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-check-circle text-success"></i> {{ $respondedReports ?? 0 }} sudah ditanggapi
                </small>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="stat-card">
                <div class="icon" style="background: linear-gradient(135deg, #FA709A 0%, #FEE140 100%);">
                    <i class="fas fa-photo-video"></i>
                </div>
                <div class="number">{{ $mediaPercentage }}%</div>
                <div class="label">Laporan Dengan Media</div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-image text-info"></i> {{ $reportsWithMedia }} laporan
                </small>
            </div>
        </div>
    </div>

    <!-- Verification Statistics -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h5><i class="fas fa-shield-check me-2"></i>Statistik Verifikasi Laporan</h5>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon" style="background: linear-gradient(135deg, #66BB6A 0%, #43A047 100%);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="badge bg-success" style="font-size: 14px;">
                        {{ $verificationRate }}%
                    </span>
                </div>
                <div class="number text-success">{{ $verifiedReports }}</div>
                <div class="label">Terverifikasi</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon" style="background: linear-gradient(135deg, #FFA726 0%, #FB8C00 100%);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="badge bg-warning text-dark" style="font-size: 14px;">
                        {{ $totalReports > 0 ? round(($pendingVerification / $totalReports) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="number text-warning">{{ $pendingVerification }}</div>
                <div class="label">Menunggu Verifikasi</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="icon" style="background: linear-gradient(135deg, #EF5350 0%, #E53935 100%);">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <span class="badge bg-danger" style="font-size: 14px;">
                        {{ $totalReports > 0 ? round(($rejectedReports / $totalReports) * 100, 1) : 0 }}%
                    </span>
                </div>
                <div class="number text-danger">{{ $rejectedReports }}</div>
                <div class="label">Ditolak</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="icon" style="background: linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%);">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="number">{{ $totalReports }}</div>
                <div class="label">Total Laporan</div>
                <small class="text-muted mt-2 d-block">
                    Semua laporan masuk
                </small>
            </div>
        </div>
    </div>

    <!-- Time-based Statistics -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="number text-warning">{{ $reportsToday }}</div>
                        <div class="label">Laporan Hari Ini</div>
                    </div>
                    <div class="icon" style="background: linear-gradient(135deg, #FFA726 0%, #FB8C00 100%); width: 50px; height: 50px; font-size: 20px;">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="number text-info">{{ $reportsThisWeek }}</div>
                        <div class="label">Laporan Minggu Ini</div>
                    </div>
                    <div class="icon" style="background: linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%); width: 50px; height: 50px; font-size: 20px;">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="number text-success">{{ $reportsThisMonth }}</div>
                        <div class="label">Laporan Bulan Ini</div>
                    </div>
                    <div class="icon" style="background: linear-gradient(135deg, #66BB6A 0%, #43A047 100%); width: 50px; height: 50px; font-size: 20px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Statistics with Percentage -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h5 class="mb-3"><i class="fas fa-tasks me-2"></i>Status Laporan</h5>
        </div>
        @php
            $statusColors = [
                'Diproses' => ['bg' => 'linear-gradient(135deg, #FFA726 0%, #FB8C00 100%)', 'icon' => 'fas fa-clock', 'color' => '#FFA726'],
                'Ditindaklanjuti' => ['bg' => 'linear-gradient(135deg, #42A5F5 0%, #1E88E5 100%)', 'icon' => 'fas fa-tasks', 'color' => '#42A5F5'],
                'Selesai' => ['bg' => 'linear-gradient(135deg, #66BB6A 0%, #43A047 100%)', 'icon' => 'fas fa-check-circle', 'color' => '#66BB6A']
            ];
        @endphp

        @foreach($reportsByStatus as $status)
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon" style="background: {{ $statusColors[$status->status]['bg'] ?? '#6C757D' }};">
                            <i class="{{ $statusColors[$status->status]['icon'] ?? 'fas fa-circle' }}"></i>
                        </div>
                        <span class="badge" style="background-color: {{ $statusColors[$status->status]['color'] ?? '#6C757D' }}; font-size: 14px;">
                            {{ $statusPercentages[$status->status] ?? 0 }}%
                        </span>
                    </div>
                    <div class="number">{{ $status->total }}</div>
                    <div class="label">{{ $status->status }}</div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $statusPercentages[$status->status] ?? 0 }}%; background-color: {{ $statusColors[$status->status]['color'] ?? '#6C757D' }};"
                             aria-valuenow="{{ $statusPercentages[$status->status] ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Top Categories -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h5><i class="fas fa-star me-2"></i>Top 3 Kategori Laporan</h5>
        </div>
        @php
            $topCategoryColors = ['#667EEA', '#F093FB', '#4FACFE'];
        @endphp
        @foreach($topCategories as $index => $item)
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div style="width: 40px; height: 40px; background: {{ $topCategoryColors[$index] ?? '#6C757D' }}; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                <i class="{{ $item->category->icon ?? 'fas fa-tag' }} text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <div class="label mb-1">{{ $item->category->name }}</div>
                                <div class="number" style="font-size: 24px;">{{ $item->total }}</div>
                            </div>
                        </div>
                        <span class="badge bg-primary" style="font-size: 24px; padding: 10px 15px;">
                            #{{ $index + 1 }}
                        </span>
                    </div>
                    <small class="text-muted">
                        {{ round(($item->total / $totalReports) * 100, 1) }}% dari total laporan
                    </small>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Daily Trend (7 days) -->
        <div class="col-md-6 mb-3">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-area me-2"></i>Tren Laporan 7 Hari Terakhir
                </div>
                <canvas id="dailyReportsChart" height="100"></canvas>
            </div>
        </div>

        <!-- Reports by Status Pie Chart -->
        <div class="col-md-6 mb-3">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-pie me-2"></i>Distribusi Status Laporan
                </div>
                <canvas id="statusPieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Monthly and Category Charts -->
    <div class="row mb-4">
        <!-- Monthly Reports Chart -->
        <div class="col-md-12 mb-3">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-line me-2"></i>Laporan Per Bulan (6 Bulan Terakhir)
                </div>
                <canvas id="monthlyReportsChart" height="60"></canvas>
            </div>
        </div>
    </div>

    <!-- Category Distribution Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-chart-bar me-2"></i>Distribusi Laporan Per Kategori
                </div>
                <canvas id="categoryChart" height="60"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Reports Table -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Laporan Terbaru
            </h5>
            <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-eye me-1"></i>Lihat Semua
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                        <tr>
                            <td>#{{ $report->id }}</td>
                            <td>{{ Str::limit($report->title, 40) }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $report->category->name }}</span>
                            </td>
                            <td>{{ $report->user->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge-status badge-{{ strtolower(str_replace(' ', '-', $report->status)) }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td>{{ $report->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <a href="{{ route('reports.show', $report->id) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada laporan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Daily Reports Area Chart (7 days)
    const dailyCtx = document.getElementById('dailyReportsChart').getContext('2d');
    const dailyData = @json($dailyReports);
    
    // Fill missing dates
    const last7Days = [];
    for (let i = 6; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        last7Days.push(date.toISOString().split('T')[0]);
    }
    
    const dailyLabels = last7Days.map(date => {
        const d = new Date(date);
        const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        return days[d.getDay()] + ' ' + d.getDate();
    });
    
    const dailyCounts = last7Days.map(date => {
        const found = dailyData.find(item => item.date === date);
        return found ? found.total : 0;
    });
    
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Laporan per Hari',
                data: dailyCounts,
                borderColor: '#667EEA',
                backgroundColor: 'rgba(102, 126, 234, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#667EEA',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' laporan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Monthly Reports Line Chart
    const monthlyCtx = document.getElementById('monthlyReportsChart').getContext('2d');
    const monthlyData = @json($monthlyReports);
    
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => {
                const [year, month] = item.month.split('-');
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                return monthNames[parseInt(month) - 1] + ' ' + year;
            }),
            datasets: [{
                label: 'Jumlah Laporan',
                data: monthlyData.map(item => item.total),
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(240, 147, 251, 0.8)',
                    'rgba(245, 87, 108, 0.8)',
                    'rgba(79, 172, 254, 0.8)',
                    'rgba(0, 242, 254, 0.8)'
                ],
                borderColor: [
                    'rgba(102, 126, 234, 1)',
                    'rgba(118, 75, 162, 1)',
                    'rgba(240, 147, 251, 1)',
                    'rgba(245, 87, 108, 1)',
                    'rgba(79, 172, 254, 1)',
                    'rgba(0, 242, 254, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Total: ' + context.parsed.y + ' laporan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    const statusData = @json($reportsByStatus);
    const statusPercentages = @json($statusPercentages);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => item.status),
            datasets: [{
                data: statusData.map(item => item.total),
                backgroundColor: [
                    '#FFA726',
                    '#42A5F5',
                    '#66BB6A'
                ],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 13,
                            weight: '500'
                        },
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const percentage = statusPercentages[label] || 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });

    // Category Bar Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($reportsByCategory);
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryData.map(item => item.category.name),
            datasets: [{
                label: 'Jumlah Laporan',
                data: categoryData.map(item => item.total),
                backgroundColor: [
                    'rgba(102, 126, 234, 0.8)',
                    'rgba(118, 75, 162, 0.8)',
                    'rgba(237, 100, 166, 0.8)',
                    'rgba(255, 154, 158, 0.8)',
                    'rgba(250, 208, 196, 0.8)',
                    'rgba(74, 144, 226, 0.8)'
                ],
                borderColor: [
                    'rgba(102, 126, 234, 1)',
                    'rgba(118, 75, 162, 1)',
                    'rgba(237, 100, 166, 1)',
                    'rgba(255, 154, 158, 1)',
                    'rgba(250, 208, 196, 1)',
                    'rgba(74, 144, 226, 1)'
                ],
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = {{ $totalReports > 0 ? $totalReports : 1 }};
                            const percentage = ((context.parsed.y / total) * 100).toFixed(1);
                            return 'Jumlah: ' + context.parsed.y + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
