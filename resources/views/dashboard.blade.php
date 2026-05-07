<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Student Success Tracker') }} - Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('prototype/css/style.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
@if ($dashboardType === 'student')
    <div id="view-student-dashboard" class="view active view-transition layout-dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i data-lucide="graduation-cap" class="sidebar-logo"></i>
                <span>SST Portal</span>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
                <a href="#" class="nav-item"><i data-lucide="award"></i> SKKM</a>
                <a href="#" class="nav-item"><i data-lucide="book-open"></i> Bimbingan</a>
                <a href="#" class="nav-item"><i data-lucide="user"></i> Profil</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i data-lucide="log-out"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h2 class="greeting">Selamat Datang, {{ $student->name }}!</h2>
                    <span class="badge badge-info">Semester Aktif: {{ $student->semester ?? '-' }}</span>
                </div>
                <div class="header-actions">
                    <button class="btn-icon" type="button"><i data-lucide="bell"></i></button>
                    <div class="avatar">{{ strtoupper(substr($student->name, 0, 1)) }}</div>
                </div>
            </header>

            <div class="dashboard-grid">
                <div class="card widget-skkm">
                    <div class="card-header">
                        <h3>Poin SKKM Semester Ini</h3>
                        <i data-lucide="target" class="text-slate"></i>
                    </div>
                    <div class="card-body flex-center">
                        <div class="circular-progress" style="--progress: {{ $progressDegree }}deg;">
                            <div class="inner-circle">
                                <span class="progress-value">{{ $approvedPoints }}<span class="progress-total">/{{ $skkmTarget }}</span></span>
                                <span class="progress-label">Poin</span>
                            </div>
                        </div>
                        <p class="skkm-status {{ $progressPercent >= 70 ? 'success' : 'warning' }}">{{ $progressPercent }}% Target Tercapai</p>
                    </div>
                </div>

                <div class="card widget-guidance">
                    <div class="card-header">
                        <h3>Bimbingan Terakhir</h3>
                        <i data-lucide="calendar" class="text-slate"></i>
                    </div>
                    <div class="card-body">
                        @if ($latestGuidance)
                            <div class="guidance-detail">
                                <div class="guidance-date">{{ $latestGuidance->guidance_date?->format('d M Y') }}</div>
                                <div class="guidance-topic">{{ $latestGuidance->topic }}</div>
                                <div class="guidance-status">
                                    <span class="badge {{ $latestGuidance->status === 'validated' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $latestGuidance->status === 'validated' ? 'Divalidasi' : 'Menunggu Review' }}
                                    </span>
                                </div>
                            </div>
                            <div class="guidance-lecturer">
                                <i data-lucide="user-check"></i> {{ $latestGuidance->lecturer?->name ?? '-' }}
                            </div>
                        @else
                            <div class="guidance-detail">
                                <div class="guidance-date">Belum ada data bimbingan</div>
                                <div class="guidance-topic">Silakan isi logbook bimbingan terlebih dahulu.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card widget-actions col-span-full">
                    <div class="card-header">
                        <h3>Aksi Cepat</h3>
                    </div>
                    <div class="card-body action-buttons">
                        <button class="btn btn-primary btn-lg" type="button"><i data-lucide="upload-cloud"></i> Upload Sertifikat SKKM</button>
                        <button class="btn btn-secondary btn-lg" type="button"><i data-lucide="pen-tool"></i> Isi Logbook Bimbingan</button>
                    </div>
                </div>

                <div class="card widget-history col-span-full">
                    <div class="card-header">
                        <h3>Riwayat Aktivitas Terbaru</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Aktivitas/Topik</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($activities as $item)
                                    <tr>
                                        <td>{{ $item['date'] }}</td>
                                        <td><span class="badge badge-info">{{ $item['category'] }}</span></td>
                                        <td>{{ $item['activity'] }}</td>
                                        <td>{{ $item['points'] ? '+'.$item['points'] : '-' }}</td>
                                        <td>
                                            @php
                                                $statusClass = match ($item['status']) {
                                                    'approved', 'validated' => 'badge-success',
                                                    'rejected' => 'badge-error',
                                                    default => 'badge-warning',
                                                };

                                                $statusLabel = match ($item['status']) {
                                                    'approved' => 'Disetujui',
                                                    'pending' => 'Pending',
                                                    'rejected' => 'Ditolak',
                                                    'validated' => 'Divalidasi',
                                                    'revised' => 'Revisi',
                                                    default => ucfirst((string) $item['status']),
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-slate">Belum ada aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@else
    <div id="view-lecturer-dashboard" class="view active view-transition layout-dashboard">
        <aside class="sidebar">
            <div class="sidebar-header">
                <i data-lucide="graduation-cap" class="sidebar-logo"></i>
                <span>SST Portal (Dosen)</span>
            </div>
            <nav class="sidebar-nav">
                <a href="#" class="nav-item active"><i data-lucide="layout-dashboard"></i> Dashboard</a>
                <a href="#" class="nav-item"><i data-lucide="check-square"></i> Antrean Persetujuan</a>
                <a href="#" class="nav-item"><i data-lucide="users"></i> Monitoring Mhs</a>
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i data-lucide="log-out"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div>
                    <h2 class="greeting">Dashboard Dosen PA</h2>
                    <span class="badge badge-info">{{ $lecturer->name }}</span>
                </div>
                <div class="header-actions">
                    <div class="search-bar">
                        <i data-lucide="search"></i>
                        <input type="text" placeholder="Cari mahasiswa..." disabled>
                    </div>
                    <button class="btn-icon" type="button"><i data-lucide="bell"></i></button>
                    <div class="avatar avatar-lecturer">{{ strtoupper(substr($lecturer->name, 0, 1)) }}</div>
                </div>
            </header>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon bg-blue-light"><i data-lucide="users" class="text-blue"></i></div>
                    <div class="stat-info">
                        <h4>Total Mahasiswa</h4>
                        <div class="stat-value">{{ $totalStudents }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-yellow-light"><i data-lucide="file-clock" class="text-yellow"></i></div>
                    <div class="stat-info">
                        <h4>Menunggu SKKM</h4>
                        <div class="stat-value">{{ $pendingSkkmCount }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-green-light"><i data-lucide="calendar-clock" class="text-green"></i></div>
                    <div class="stat-info">
                        <h4>Bimbingan Hari Ini</h4>
                        <div class="stat-value">{{ $guidanceTodayCount }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon bg-red-light"><i data-lucide="alert-triangle" class="text-red"></i></div>
                    <div class="stat-info">
                        <h4>Mahasiswa Beresiko</h4>
                        <div class="stat-value text-red">{{ $atRiskCount }}</div>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid mt-6">
                <div class="card col-span-full">
                    <div class="card-header flex-between">
                        <h3>Antrean Verifikasi SKKM</h3>
                        <button class="btn btn-sm btn-outline" type="button">Lihat Semua</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Nama Kegiatan</th>
                                    <th>Poin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvalQueue as $item)
                                    <tr>
                                        <td>{{ $item->student?->name }}</td>
                                        <td class="text-slate">{{ $item->student?->identifier }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td><span class="badge badge-info">+{{ $item->points }} Pts</span></td>
                                        <td><span class="badge badge-warning">Pending</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-slate">Tidak ada antrean verifikasi saat ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endif

<script>
    lucide.createIcons();
</script>
</body>
</html>
