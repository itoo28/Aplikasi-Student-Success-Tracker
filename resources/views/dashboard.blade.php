<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if ($dashboardType === 'student')
        <div class="space-y-8">

            {{-- Hero Greeting --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-8 text-white shadow-2xl shadow-indigo-300/30">
                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-purple-400/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    {{-- Left: Greeting + Info --}}
                    <div>
                        <p class="text-indigo-200 text-sm font-medium">Selamat datang kembali 👋</p>
                        <h2 class="text-2xl lg:text-3xl font-extrabold mt-1">{{ $student->name }}</h2>
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10">
                                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> {{ $student->jenjang_studi ?? 'S1' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Semester {{ $student->semester ?? '-' }}
                            </span>
                            @if($student->lecturer)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10">
                                <i data-lucide="user-check" class="w-3.5 h-3.5"></i> PA: {{ $student->lecturer->name }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Circular Progress --}}
                    <div class="flex items-center gap-6">
                        <div class="relative w-28 h-28 flex-shrink-0">
                            <svg class="w-28 h-28 transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="10"/>
                                <circle cx="60" cy="60" r="52" fill="none" stroke="url(#dashGrad)" stroke-width="10" stroke-linecap="round"
                                    stroke-dasharray="{{ 2 * 3.14159 * 52 }}" stroke-dashoffset="{{ 2 * 3.14159 * 52 * (1 - $progressPercent / 100) }}"/>
                                <defs><linearGradient id="dashGrad"><stop offset="0%" stop-color="#a5b4fc"/><stop offset="100%" stop-color="#e9d5ff"/></linearGradient></defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-2xl font-extrabold">{{ $progressPercent }}%</span>
                                <span class="text-[9px] text-indigo-200 font-semibold uppercase tracking-wider">Tercapai</span>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold text-lg">{{ $approvedPoints }}<span class="text-indigo-300 font-medium text-sm"> / {{ $targetKelulusan }}</span></p>
                            <p class="text-indigo-200 text-xs mt-0.5">Poin SKKM Disetujui</p>
                            @if($progressPercent >= 100)
                                <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 bg-emerald-400/20 text-emerald-200 rounded-full text-[10px] font-bold border border-emerald-400/30">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Memenuhi Syarat
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 mt-2 px-2.5 py-1 bg-amber-400/20 text-amber-200 rounded-full text-[10px] font-bold border border-amber-400/30">
                                    <i data-lucide="target" class="w-3 h-3"></i> Kurang {{ $remainingPoints }} poin
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Mini Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center"><i data-lucide="check-circle" class="w-4.5 h-4.5 text-emerald-600"></i></div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Disetujui</span>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-800">{{ $approvedCount }}</p>
                    <p class="text-xs text-emerald-600 font-semibold mt-0.5">+{{ $approvedPoints }} poin</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center"><i data-lucide="clock" class="w-4.5 h-4.5 text-amber-600"></i></div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Menunggu</span>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-800">{{ $pendingCount }}</p>
                    <p class="text-xs text-amber-600 font-semibold mt-0.5">+{{ $pendingPoints }} poin</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center"><i data-lucide="x-circle" class="w-4.5 h-4.5 text-rose-600"></i></div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Ditolak</span>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-800">{{ $rejectedCount }}</p>
                    <p class="text-xs text-rose-600 font-semibold mt-0.5">perlu revisi</p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-shadow">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center"><i data-lucide="trending-up" class="w-4.5 h-4.5 text-violet-600"></i></div>
                        <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Sisa Target</span>
                    </div>
                    <p class="text-2xl font-extrabold text-slate-800">{{ $remainingPoints }}</p>
                    <p class="text-xs text-violet-600 font-semibold mt-0.5">poin lagi</p>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Bimbingan Terakhir --}}
                <div class="rounded-3xl bg-white border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <i data-lucide="book-open" class="w-5 h-5 text-cyan-500 mr-2"></i> Bimbingan Terakhir
                    </h4>
                    @if ($latestGuidance)
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-semibold text-slate-500">{{ $latestGuidance->guidance_date?->format('d M Y') }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $latestGuidance->status === 'validated' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $latestGuidance->status === 'validated' ? 'Divalidasi' : 'Menunggu' }}
                                </span>
                            </div>
                            <p class="text-slate-800 font-medium text-sm line-clamp-2 mb-3">{{ $latestGuidance->topic }}</p>
                            <div class="flex items-center text-xs text-slate-500">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 mr-1.5"></i>
                                {{ $latestGuidance->lecturer?->name ?? '-' }}
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center">
                            <i data-lucide="file-x" class="w-8 h-8 text-slate-300 mx-auto mb-2"></i>
                            <p class="text-slate-500 text-xs font-medium">Belum ada data bimbingan.</p>
                        </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="mt-5 flex flex-col gap-2">
                        <a href="{{ route('skkm.create') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                            <i data-lucide="upload-cloud" class="w-4 h-4"></i> Upload SKKM
                        </a>
                        <a href="{{ route('skkm.index') }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                            <i data-lucide="list" class="w-4 h-4"></i> Lihat Semua Poin
                        </a>
                    </div>
                </div>

                {{-- Chart --}}
                <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-100 p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-indigo-500 mr-2"></i> Poin SKKM per Semester
                    </h4>
                    <div class="w-full h-72 relative">
                        <canvas id="skkmChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity Feed --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800 flex items-center">
                        <i data-lucide="activity" class="w-5 h-5 text-indigo-500 mr-2"></i> Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('skkm.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($activities as $item)
                        <div class="px-6 py-4 flex items-center gap-4 hover:bg-slate-50/60 transition-colors">
                            @if($item['category'] === 'SKKM')
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="file-badge" class="w-4 h-4 text-indigo-600"></i>
                                </div>
                            @else
                                <div class="w-9 h-9 rounded-xl bg-cyan-100 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="book-open" class="w-4 h-4 text-cyan-600"></i>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $item['activity'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] text-slate-400">{{ $item['date'] }}</span>
                                    @if($item['points'])
                                        <span class="text-[11px] font-bold text-indigo-500">+{{ $item['points'] }} poin</span>
                                    @endif
                                </div>
                            </div>
                            @if(in_array($item['status'], ['approved', 'validated', 'disetujui']))
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-bold flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Disetujui</span>
                            @elseif(in_array($item['status'], ['pending', 'menunggu_dosen']))
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-bold flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Menunggu</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 text-rose-600 rounded-lg text-[10px] font-bold flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Ditolak</span>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <i data-lucide="inbox" class="w-10 h-10 text-slate-200 mx-auto mb-3"></i>
                            <p class="text-sm text-slate-500">Belum ada riwayat aktivitas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <!-- LECTURER DASHBOARD -->
        <div class="space-y-8">
            <!-- Greeting Row -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Halo, {{ $lecturer->name }}</h3>
                    <p class="text-slate-500 mt-1">Ringkasan aktivitas mahasiswa bimbingan akademik Anda.</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Mhs -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center">
                    <div class="bg-blue-50 text-blue-600 p-4 rounded-2xl mr-4">
                        <i data-lucide="users" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-semibold mb-1">Total Mahasiswa</p>
                        <h4 class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</h4>
                    </div>
                </div>
                
                <!-- Menunggu SKKM -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center">
                    <div class="bg-amber-50 text-amber-600 p-4 rounded-2xl mr-4">
                        <i data-lucide="file-clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-semibold mb-1">Antrean SKKM</p>
                        <h4 class="text-2xl font-bold text-slate-800">{{ $pendingSkkmCount }}</h4>
                    </div>
                </div>

                <!-- Bimbingan Hari Ini -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center">
                    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-2xl mr-4">
                        <i data-lucide="calendar-clock" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-semibold mb-1">Bimbingan Hari Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800">{{ $guidanceTodayCount }}</h4>
                    </div>
                </div>

                <!-- Mhs Beresiko -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex items-center">
                    <div class="bg-rose-50 text-rose-600 p-4 rounded-2xl mr-4">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 font-semibold mb-1">Mhs Beresiko</p>
                        <h4 class="text-2xl font-bold text-slate-800">{{ $atRiskCount }}</h4>
                    </div>
                </div>
            </div>

            <!-- Antrean Table -->
            <div class="rounded-3xl bg-white border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i data-lucide="clock" class="w-5 h-5 text-amber-500 mr-2"></i>
                        Antrean Verifikasi SKKM
                    </h3>
                    <a href="{{ route('skkm.verifikasi.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors">
                        Lihat Semua
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th class="px-8 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-8 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th class="px-8 py-4 font-semibold tracking-wider">Poin</th>
                                <th class="px-8 py-4 font-semibold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($approvalQueue as $item)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-8 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->mahasiswa?->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $item->mahasiswa?->identifier }}</div>
                                    </td>
                                    <td class="px-8 py-4 font-medium text-slate-800">
                                        {{ $item->nama_kegiatan }}
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                                            +{{ $item->poin_otomatis }} Pts
                                        </span>
                                    </td>
                                    <td class="px-8 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">
                                            Pending
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-8 text-center text-slate-500">Tidak ada antrean verifikasi saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if ($dashboardType === 'student')
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartCanvas = document.getElementById('skkmChart');
                if (chartCanvas) {
                    const ctx = chartCanvas.getContext('2d');
                    const pointsData = @json($pointsPerSemester ?? []);
                    
                    if (Object.keys(pointsData).length > 0) {
                        const labels = Object.keys(pointsData).map(sem => 'Semester ' + sem);
                        const data = Object.values(pointsData);

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Poin SKKM',
                                    data: data,
                                    backgroundColor: 'rgba(99, 102, 241, 0.85)',
                                    borderColor: 'rgba(79, 70, 229, 1)',
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    hoverBackgroundColor: 'rgba(79, 70, 229, 1)',
                                    barPercentage: 0.6,
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                        titleFont: { size: 13, family: 'Inter' },
                                        bodyFont: { size: 14, family: 'Inter', weight: 'bold' },
                                        padding: 12,
                                        cornerRadius: 8,
                                        displayColors: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.parsed.y + ' Poin';
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            color: 'rgba(241, 245, 249, 1)',
                                            drawBorder: false,
                                        },
                                        border: { display: false },
                                        ticks: {
                                            font: { family: 'Inter' },
                                            color: '#64748b',
                                            stepSize: 10
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false,
                                        },
                                        border: { display: false },
                                        ticks: {
                                            font: { family: 'Inter', weight: '500' },
                                            color: '#475569'
                                        }
                                    }
                                }
                            }
                        });
                    }
                }
            });
        </script>
        @endpush
    @endif
</x-app-layout>
