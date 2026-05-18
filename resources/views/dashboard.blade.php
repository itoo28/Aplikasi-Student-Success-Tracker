<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if ($dashboardType === 'student')
        <div class="space-y-8">
            <!-- Greeting & Stats Row -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800">Selamat Datang, {{ $student->name }}!</h3>
                    <p class="text-slate-500 mt-1">Pantau progress SKKM dan bimbingan akademik Anda di sini.</p>
                </div>
                <div class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl font-semibold">
                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                    Semester Aktif: {{ $student->semester ?? '-' }}
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Card Poin SKKM -->
                <div class="rounded-3xl bg-white border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-center items-center text-center relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-indigo-50 opacity-50 blur-2xl"></div>
                    
                    <h4 class="text-lg font-bold text-slate-800 mb-6 flex items-center justify-center w-full">
                        <i data-lucide="target" class="w-5 h-5 text-indigo-500 mr-2"></i>
                        Target SKKM Kelulusan
                    </h4>
                    
                    <!-- Circular Progress Placeholder -->
                    <div class="relative w-40 h-40 flex items-center justify-center mb-6">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <!-- Background Circle -->
                            <circle class="text-slate-100 stroke-current" stroke-width="8" cx="50" cy="50" r="40" fill="transparent"></circle>
                            <!-- Progress Circle -->
                            <circle class="text-indigo-500 stroke-current" stroke-width="8" cx="50" cy="50" r="40" fill="transparent" stroke-dasharray="251.2" stroke-dashoffset="{{ 251.2 - (251.2 * $progressPercent / 100) }}" stroke-linecap="round"></circle>
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-3xl font-extrabold text-slate-800">{{ $approvedPoints }}</span>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">/ {{ $targetKelulusan }} Poin</span>
                        </div>
                    </div>
                    
                    @if($progressPercent >= 100)
                        <div class="inline-flex items-center px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-sm font-bold">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1.5"></i> Target Tercapai
                        </div>
                    @else
                        <div class="inline-flex items-center px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-sm font-bold">
                            <i data-lucide="alert-circle" class="w-4 h-4 mr-1.5"></i> {{ $progressPercent }}% Tercapai
                        </div>
                    @endif
                </div>

                <!-- Card Bimbingan & Aksi Cepat -->
                <div class="space-y-8">
                    <div class="rounded-3xl bg-white border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <h4 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                            <i data-lucide="clock" class="w-5 h-5 text-emerald-500 mr-2"></i>
                            Bimbingan Terakhir
                        </h4>
                        
                        @if ($latestGuidance)
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-semibold text-slate-500">{{ $latestGuidance->guidance_date?->format('d M Y') }}</span>
                                    <span class="text-xs font-bold px-2 py-1 rounded-lg {{ $latestGuidance->status === 'validated' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $latestGuidance->status === 'validated' ? 'Divalidasi' : 'Menunggu Review' }}
                                    </span>
                                </div>
                                <p class="text-slate-800 font-medium line-clamp-2 mb-3">{{ $latestGuidance->topic }}</p>
                                <div class="flex items-center text-sm text-slate-500">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-1.5"></i>
                                    {{ $latestGuidance->lecturer?->name ?? '-' }}
                                </div>
                            </div>
                        @else
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100 text-center flex flex-col items-center justify-center">
                                <i data-lucide="file-x" class="w-8 h-8 text-slate-300 mb-2"></i>
                                <p class="text-slate-500 text-sm font-medium">Belum ada data bimbingan.<br>Silakan isi logbook terlebih dahulu.</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-8 shadow-lg shadow-indigo-600/20 text-white relative overflow-hidden">
                        <div class="absolute -right-4 -bottom-4 opacity-10">
                            <i data-lucide="zap" class="w-32 h-32"></i>
                        </div>
                        <h4 class="text-lg font-bold mb-4 relative z-10">Aksi Cepat</h4>
                        <div class="flex flex-col sm:flex-row gap-3 relative z-10">
                            <a href="{{ route('skkm.create') }}" class="flex-1 bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/30 text-white px-4 py-3 rounded-xl font-semibold transition-all flex items-center justify-center">
                                <i data-lucide="upload-cloud" class="w-4 h-4 mr-2"></i> Upload SKKM
                            </a>
                            <button class="flex-1 bg-white text-indigo-600 hover:bg-slate-50 px-4 py-3 rounded-xl font-semibold transition-all flex items-center justify-center shadow-sm">
                                <i data-lucide="pen-tool" class="w-4 h-4 mr-2"></i> Isi Logbook
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Poin per Semester -->
                <div class="col-span-1 lg:col-span-2 rounded-3xl bg-white border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-indigo-500 mr-2"></i>
                        Grafik Poin SKKM per Semester
                    </h3>
                    <div class="w-full h-80 relative">
                        @if(empty($pointsPerSemester))
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                                <i data-lucide="bar-chart" class="w-12 h-12 mb-3 opacity-20"></i>
                                <p class="text-sm font-medium">Belum ada data poin SKKM yang disetujui.</p>
                            </div>
                        @endif
                        <canvas id="skkmChart"></canvas>
                    </div>
                </div>

                <!-- Riwayat Aktivitas -->
                <div class="col-span-1 lg:col-span-2 rounded-3xl bg-white border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <h3 class="text-lg font-bold text-slate-800">Riwayat Aktivitas Terbaru</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                                <tr>
                                    <th class="px-8 py-4 font-semibold tracking-wider">Tanggal</th>
                                    <th class="px-8 py-4 font-semibold tracking-wider">Kategori</th>
                                    <th class="px-8 py-4 font-semibold tracking-wider">Aktivitas / Topik</th>
                                    <th class="px-8 py-4 font-semibold tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($activities as $item)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-8 py-5 text-slate-800 font-medium">{{ $item['date'] }}</td>
                                        <td class="px-8 py-5">
                                            @if($item['category'] === 'SKKM')
                                                <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-md text-xs font-bold border border-indigo-100">SKKM</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 bg-cyan-50 text-cyan-600 rounded-md text-xs font-bold border border-cyan-100">BIMBINGAN</span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5">
                                            <div class="text-slate-800 font-semibold">{{ $item['activity'] }}</div>
                                            @if($item['points'])
                                                <div class="text-xs text-indigo-500 font-bold mt-1">+{{ $item['points'] }} Poin</div>
                                            @endif
                                        </td>
                                        <td class="px-8 py-5">
                                            @if(in_array($item['status'], ['approved', 'validated', 'disetujui']))
                                                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold">Disetujui</span>
                                            @elseif($item['status'] === 'menunggu_dosen')
                                                <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">Menunggu Dosen PA</span>
                                            @elseif($item['status'] === 'menunggu_kaprodi')
                                                <span class="inline-flex items-center px-2.5 py-1 bg-sky-50 text-sky-600 rounded-lg text-xs font-bold">Menunggu Kaprodi</span>
                                            @elseif($item['status'] === 'menunggu_kemahasiswaan')
                                                <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold">Menunggu Kemahasiswaan</span>
                                            @elseif(in_array($item['status'], ['pending']))
                                                <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-bold">Menunggu</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold">Ditolak/Revisi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-8 text-center text-slate-500">Belum ada riwayat aktivitas yang tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
