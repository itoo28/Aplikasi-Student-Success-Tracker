<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if ($dashboardType === 'student')
        <div x-data="{ showGuidanceDetail: false }" class="space-y-8">
            @php
                $latestBimbinganStatus = null;

                if ($latestBimbingan) {
                    $latestBimbinganStatus = match ($latestBimbingan->status) {
                        'validated' => ['label' => 'Disetujui', 'class' => 'bg-emerald-100 text-emerald-700 ring-emerald-200'],
                        'completed' => ['label' => 'Selesai', 'class' => 'bg-cyan-100 text-cyan-700 ring-cyan-200'],
                        'revised' => ['label' => 'Ditolak', 'class' => 'bg-rose-100 text-rose-700 ring-rose-200'],
                        default => ['label' => 'Menunggu', 'class' => 'bg-amber-100 text-amber-700 ring-amber-200'],
                    };
                }
            @endphp

            {{-- Hero Greeting --}}
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-6 sm:p-8 text-white shadow-2xl shadow-indigo-300/30">
                <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-purple-400/10 blur-2xl"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    {{-- Left: Greeting + Info --}}
                    <div>
                        <p class="text-indigo-200 text-sm font-medium">Selamat datang kembali 👋</p>
                        <h2 class="text-2xl lg:text-3xl font-extrabold mt-1 truncate max-w-xs sm:max-w-md md:max-w-none">{{ $student->name }}</h2>
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10">
                                <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> {{ $student->jenjang_studi ?? 'S1' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Semester {{ $student->semester ?? '-' }}
                            </span>
                            @if($student->lecturer)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-lg text-xs font-semibold border border-white/10 max-w-[200px] truncate" title="PA: {{ $student->lecturer->name }}">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 shrink-0"></i> PA: {{ $student->lecturer->name }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Circular Progress --}}
                    <div class="flex items-center gap-5 sm:gap-6 border-t border-white/10 pt-5 lg:border-t-0 lg:pt-0">
                        <div class="relative w-20 h-20 sm:w-28 sm:h-28 flex-shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="10"/>
                                <circle cx="60" cy="60" r="52" fill="none" stroke="url(#dashGrad)" stroke-width="10" stroke-linecap="round"
                                    stroke-dasharray="{{ 2 * 3.14159 * 52 }}" stroke-dashoffset="{{ 2 * 3.14159 * 52 * (1 - $progressPercent / 100) }}"/>
                                <defs><linearGradient id="dashGrad"><stop offset="0%" stop-color="#a5b4fc"/><stop offset="100%" stop-color="#e9d5ff"/></linearGradient></defs>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xl sm:text-2xl font-extrabold">{{ $progressPercent }}%</span>
                                <span class="text-[8px] sm:text-[9px] text-indigo-200 font-semibold uppercase tracking-wider">Tercapai</span>
                            </div>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold text-base sm:text-lg">{{ $approvedPoints }}<span class="text-indigo-300 font-medium text-xs sm:text-sm"> / {{ $targetKelulusan }}</span></p>
                            <p class="text-indigo-200 text-xs mt-0.5">Poin SKKM Disetujui</p>
                            @if($progressPercent >= 100)
                                <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 sm:px-2.5 sm:py-1 bg-emerald-400/20 text-emerald-200 rounded-full text-[9px] sm:text-[10px] font-bold border border-emerald-400/30">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Memenuhi Syarat
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 sm:px-2.5 sm:py-1 bg-amber-400/20 text-amber-200 rounded-full text-[9px] sm:text-[10px] font-bold border border-amber-400/30">
                                    <i data-lucide="target" class="w-3 h-3"></i> Kurang {{ $remainingPoints }} poin
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Mini Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-2xl sm:rounded-3xl p-4 sm:p-5 text-white shadow-[0_10px_25px_rgba(79,70,229,0.15)] hover:shadow-[0_14px_35px_rgba(79,70,229,0.25)] hover:-translate-y-0.5 transition-all duration-300 border border-white/10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-2.5 sm:mb-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/10"><i data-lucide="check-circle" class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-white"></i></div>
                        <span class="text-[9px] sm:text-[11px] font-bold text-indigo-200 uppercase tracking-wider">Disetujui</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-white">{{ $approvedCount }}</p>
                    <p class="text-[10px] sm:text-xs text-indigo-100 font-bold mt-0.5">+{{ $approvedPoints }} poin</p>
                </div>
                <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl sm:rounded-3xl p-4 sm:p-5 text-white shadow-[0_10px_25px_rgba(245,158,11,0.2)] hover:shadow-[0_14px_35px_rgba(245,158,11,0.3)] hover:-translate-y-0.5 transition-all duration-300 border border-white/10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-2.5 sm:mb-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/10"><i data-lucide="clock" class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-white"></i></div>
                        <span class="text-[9px] sm:text-[11px] font-bold text-amber-100 uppercase tracking-wider">Menunggu</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-white">{{ $pendingCount }}</p>
                    <p class="text-[10px] sm:text-xs text-amber-100 font-bold mt-0.5">+{{ $pendingPoints }} poin</p>
                </div>
                <div class="bg-gradient-to-br from-rose-500 to-red-600 rounded-2xl sm:rounded-3xl p-4 sm:p-5 text-white shadow-[0_10px_25px_rgba(244,63,94,0.2)] hover:shadow-[0_14px_35px_rgba(244,63,94,0.3)] hover:-translate-y-0.5 transition-all duration-300 border border-white/10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-2.5 sm:mb-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/10"><i data-lucide="x-circle" class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-white"></i></div>
                        <span class="text-[9px] sm:text-[11px] font-bold text-rose-100 uppercase tracking-wider">Ditolak</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-white">{{ $rejectedCount }}</p>
                    <p class="text-[10px] sm:text-xs text-rose-100 font-bold mt-0.5">revisi</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 rounded-2xl sm:rounded-3xl p-4 sm:p-5 text-white shadow-[0_10px_25px_rgba(79,70,229,0.15)] hover:shadow-[0_14px_35px_rgba(79,70,229,0.25)] hover:-translate-y-0.5 transition-all duration-300 border border-white/10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-2.5 sm:mb-3">
                        <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-white/15 backdrop-blur-sm flex items-center justify-center border border-white/10"><i data-lucide="trending-up" class="w-4 h-4 sm:w-4.5 sm:h-4.5 text-white"></i></div>
                        <span class="text-[9px] sm:text-[11px] font-bold text-indigo-200 uppercase tracking-wider">Sisa Target</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-extrabold text-white">{{ $remainingPoints }}</p>
                    <p class="text-[10px] sm:text-xs text-indigo-100 font-bold mt-0.5">poin lagi</p>
                </div>
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">

                {{-- Bimbingan Terakhir --}}
                <div class="rounded-3xl bg-white border border-slate-100 p-5 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <i data-lucide="book-open" class="w-5 h-5 text-cyan-500 mr-2"></i> Bimbingan Terakhir
                    </h4>
                    @if ($latestBimbingan)
                        <button type="button" @click="showGuidanceDetail = true" class="group w-full bg-slate-50 p-4 rounded-2xl border border-slate-100 text-left transition-all hover:border-cyan-200 hover:bg-cyan-50/50 hover:shadow-sm">
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <span class="text-xs font-semibold text-slate-500 shrink-0">{{ $latestBimbingan->tanggal?->format('d M Y') }}</span>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[9px] font-bold ring-1 ring-inset {{ $latestBimbinganStatus['class'] }} truncate shrink-0">
                                    {{ $latestBimbinganStatus['label'] }}
                                </span>
                            </div>
                            <p class="text-slate-800 font-medium text-sm line-clamp-2 mb-3">{{ $latestBimbingan->topik }}</p>
                            <div class="flex items-center text-xs text-slate-500">
                                <i data-lucide="user-check" class="w-3.5 h-3.5 mr-1.5 shrink-0"></i>
                                <span class="truncate">{{ $latestBimbingan->dosen?->name ?? '-' }}</span>
                            </div>
                            <div class="mt-4 flex items-center text-xs font-semibold text-cyan-700">
                                Lihat detail dan ringkasan
                                <i data-lucide="arrow-right" class="ml-1.5 h-3.5 w-3.5 transition-transform group-hover:translate-x-1"></i>
                            </div>
                        </button>
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
                <div class="lg:col-span-2 rounded-3xl bg-white border border-slate-100 p-5 sm:p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <h4 class="text-base font-bold text-slate-800 mb-4 flex items-center">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-indigo-500 mr-2"></i> Poin SKKM per Semester
                    </h4>
                    <div class="w-full h-56 sm:h-72 relative">
                        <canvas id="skkmChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity Feed --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                    <h3 class="text-base font-bold text-slate-800 flex items-center">
                        <i data-lucide="activity" class="w-5 h-5 text-indigo-500 mr-2"></i> Aktivitas Terbaru
                    </h3>
                    <a href="{{ route('skkm.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">Lihat Semua</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($activities as $item)
                        <div class="px-4 sm:px-6 py-3.5 sm:py-4 flex items-center gap-3 sm:gap-4 hover:bg-slate-50/60 transition-colors">
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
                            @if(in_array($item['status'], ['approved', 'validated', 'completed', 'disetujui']))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-200 flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Disetujui</span>
                            @elseif(in_array($item['status'], ['pending', 'menunggu_dosen']))
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200 flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Menunggu</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 rounded-full text-[10px] font-bold border border-rose-200 flex-shrink-0"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Ditolak</span>
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

            @if ($latestBimbingan && $latestBimbinganStatus)
                <div
                    x-show="showGuidanceDetail"
                    x-cloak
                    @keydown.escape.window="showGuidanceDetail = false"
                    class="fixed inset-0 z-[150] overflow-y-auto"
                    aria-modal="true"
                    role="dialog"
                >
                    <div class="min-h-full px-4 py-6 sm:px-6 sm:py-10">
                        <button type="button" @click="showGuidanceDetail = false" class="fixed inset-0 bg-slate-950/55 backdrop-blur-md" aria-label="Tutup detail bimbingan"></button>

                        <div
                            x-show="showGuidanceDetail"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
                            @click.stop
                            class="relative z-[151] mx-auto w-full max-w-3xl overflow-hidden rounded-[2rem] border border-white/60 bg-white shadow-[0_30px_90px_rgba(15,23,42,0.30)] ring-1 ring-slate-900/5"
                        >
                            <div class="relative overflow-hidden bg-gradient-to-r from-sky-600 via-indigo-600 to-violet-600 px-6 py-6 text-white sm:px-8">
                                <div class="absolute -left-10 top-0 h-24 w-24 rounded-full bg-white/10 blur-2xl"></div>
                                <div class="absolute -right-8 bottom-0 h-28 w-28 rounded-full bg-cyan-300/20 blur-2xl"></div>
                                <div class="relative flex items-start justify-between gap-4">
                                    <div class="max-w-2xl">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-sky-100">Detail Bimbingan Akademik</p>
                                        <h3 class="mt-2 text-xl font-extrabold leading-tight sm:text-2xl">{{ $latestBimbingan->topik }}</h3>
                                        <p class="mt-2 text-sm text-sky-100/90">
                                            Detail sesi bimbingan terakhir Anda bersama dosen PA.
                                        </p>
                                    </div>
                                    <button type="button" @click="showGuidanceDetail = false" class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl bg-white/15 text-white transition hover:bg-white/25" aria-label="Tutup detail bimbingan">
                                        <i data-lucide="x" class="h-4 w-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="max-h-[calc(100vh-7rem)] overflow-y-auto bg-gradient-to-b from-slate-50/70 to-white px-6 py-6 sm:px-8 sm:py-7">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Tanggal</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-800">{{ $latestBimbingan->tanggal?->format('d M Y') }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Semester</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-800">Semester {{ $latestBimbingan->semester ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Status</p>
                                        <span class="mt-2 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ $latestBimbinganStatus['class'] }}">{{ $latestBimbinganStatus['label'] }}</span>
                                    </div>
                                </div>

                                <div class="mt-5 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">
                                            <i data-lucide="user-check" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Dosen Pembimbing</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-800">{{ $latestBimbingan->dosen?->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-1 gap-4">
                                    @if($latestBimbingan->catatan && $latestBimbingan->catatan !== '-')
                                        <section class="rounded-3xl border border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50 p-5 shadow-sm">
                                            <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-700">Catatan Dosen</p>
                                            <p class="mt-3 text-sm leading-7 text-slate-700">{{ $latestBimbingan->catatan }}</p>
                                        </section>
                                    @endif

                                    <section class="rounded-3xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 p-5 shadow-sm">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-700">Ringkasan Pembahasan</p>
                                        <p class="mt-3 text-sm leading-7 text-slate-700">
                                            {{ $latestBimbingan->resolution ?: 'Ringkasan pembahasan belum tersedia. Dosen PA akan mengisinya setelah sesi bimbingan selesai.' }}
                                        </p>
                                    </section>
                                </div>

                                <div class="mt-6 flex flex-wrap items-center gap-3 border-t border-slate-200 pt-5">
                                    @if($latestBimbingan->document_path)
                                        <a href="{{ Storage::url($latestBimbingan->document_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl border border-indigo-200 bg-indigo-50 px-4 py-2.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100">
                                            <i data-lucide="file-text" class="h-4 w-4"></i> Dokumen Pendukung
                                        </a>
                                    @endif
                                    @if($latestBimbingan->activity_photo_path)
                                        <a href="{{ Storage::url($latestBimbingan->activity_photo_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100">
                                            <i data-lucide="image" class="h-4 w-4"></i> Foto Kegiatan
                                        </a>
                                    @endif
                                    <a href="{{ route('bimbingan.mahasiswa.index') }}" class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-50">
                                        <i data-lucide="history" class="h-4 w-4"></i> Lihat Riwayat Lengkap
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <!-- LECTURER DASHBOARD -->
        <div class="relative isolate overflow-hidden rounded-[1.5rem] sm:rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-4 sm:p-8 space-y-6 sm:space-y-8">
            <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
            <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

            <div class="relative space-y-6 sm:space-y-8">
            <!-- Greeting Row -->
            <div class="rounded-3xl p-5 sm:p-8 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgb(79,70,229,0.35)]">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-indigo-100 text-xs sm:text-sm font-semibold uppercase tracking-wide">Dosen PA Dashboard</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold mt-1">Halo, {{ $lecturer->name }}</h3>
                        <p class="text-indigo-100 text-xs sm:text-sm mt-1.5 sm:mt-2">Ringkasan aktivitas mahasiswa bimbingan akademik Anda.</p>
                    </div>
                    <span class="inline-flex items-center rounded-xl bg-white/15 px-3 py-1.5 text-xs font-semibold border border-white/20 shrink-0">
                        {{ now()->format('d M Y') }}
                    </span>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Total Mhs -->
                <div class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35_rgba(37,99,235,0.35)] flex flex-col justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-indigo-100 mb-1.5 sm:mb-2">Total Mahasiswa</p>
                        <h4 class="text-2xl sm:text-3xl font-extrabold">{{ $totalStudents }}</h4>
                        <p class="text-[10px] sm:text-xs text-indigo-100/80 mt-1.5 sm:mt-2 line-clamp-2 md:line-clamp-none">Total mahasiswa di bawah bimbingan akademik Anda.</p>
                    </div>
                    <div>
                        <i data-lucide="users" class="w-4 h-4 sm:w-5 sm:h-5 mt-2 sm:mt-3 text-indigo-100"></i>
                    </div>
                </div>
                
                <!-- Menunggu SKKM -->
                <div class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35_rgba(245,158,11,0.30)] flex flex-col justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-amber-100 mb-1.5 sm:mb-2">Antrean SKKM</p>
                        <h4 class="text-2xl sm:text-3xl font-extrabold">{{ $pendingSkkmCount }}</h4>
                        <p class="text-[10px] sm:text-xs text-amber-100/80 mt-1.5 sm:mt-2 line-clamp-2 md:line-clamp-none">Jumlah pengajuan SKKM mahasiswa yang perlu divalidasi.</p>
                    </div>
                    <div>
                        <i data-lucide="file-clock" class="w-4 h-4 sm:w-5 sm:h-5 mt-2 sm:mt-3 text-amber-100"></i>
                    </div>
                </div>

                <!-- Bimbingan Hari Ini -->
                <div class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35_rgba(37,99,235,0.35)] flex flex-col justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-indigo-100 mb-1.5 sm:mb-2">Bimbingan Hari Ini</p>
                        <h4 class="text-2xl sm:text-3xl font-extrabold">{{ $guidanceTodayCount }}</h4>
                        <p class="text-[10px] sm:text-xs text-indigo-100/80 mt-1.5 sm:mt-2 line-clamp-2 md:line-clamp-none">Jumlah sesi bimbingan yang dijadwalkan pada hari ini.</p>
                    </div>
                    <div>
                        <i data-lucide="calendar-clock" class="w-4 h-4 sm:w-5 sm:h-5 mt-2 sm:mt-3 text-indigo-100"></i>
                    </div>
                </div>

                <!-- Mhs Beresiko -->
                <div class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-[0_14px_35_rgba(244,63,94,0.30)] flex flex-col justify-between">
                    <div>
                        <p class="text-xs sm:text-sm font-semibold text-rose-100 mb-1.5 sm:mb-2">Mhs Beresiko</p>
                        <h4 class="text-2xl sm:text-3xl font-extrabold">{{ $atRiskCount }}</h4>
                        <p class="text-[10px] sm:text-xs text-rose-100/80 mt-1.5 sm:mt-2 line-clamp-2 md:line-clamp-none">Mahasiswa dengan perolehan poin SKKM di bawah target.</p>
                    </div>
                    <div>
                        <i data-lucide="alert-triangle" class="w-4 h-4 sm:w-5 sm:h-5 mt-2 sm:mt-3 text-rose-100"></i>
                    </div>
                </div>
            </div>

            <!-- Antrean Table -->
            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-5 sm:px-8 py-4 sm:py-6 border-b border-indigo-100 flex justify-between items-center bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800 flex items-center">
                        <i data-lucide="clock" class="w-5 h-5 text-indigo-500 mr-2 shrink-0"></i>
                        <span class="truncate">Antrean Verifikasi SKKM</span>
                    </h3>
                    <a href="{{ route('skkm.verifikasi.index') }}" class="text-xs sm:text-sm font-semibold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-xl transition-colors shrink-0">
                        Lihat Semua
                    </a>
                </div>
                
                {{-- Desktop View Table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Poin</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse ($approvalQueue as $item)
                                <tr class="hover:bg-indigo-50/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->mahasiswa?->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $item->mahasiswa?->identifier }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-800">
                                        {{ $item->nama_kegiatan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                                            +{{ $item->poin_otomatis }} Pts
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">Tidak ada antrean verifikasi saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile View Cards --}}
                <div class="block md:hidden divide-y divide-indigo-100/60">
                    @forelse ($approvalQueue as $item)
                        <div class="p-4 hover:bg-indigo-50/40 transition-colors space-y-2.5">
                            <div class="flex justify-between items-start">
                                <div class="min-w-0">
                                    <div class="font-bold text-slate-800 text-sm truncate">{{ $item->mahasiswa?->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $item->mahasiswa?->identifier }}</div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200 shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                </span>
                            </div>
                            <div class="text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                <span class="text-[9px] font-bold text-slate-400 block mb-0.5 uppercase tracking-wide">Kegiatan</span>
                                <div class="font-medium text-slate-800 leading-relaxed">{{ $item->nama_kegiatan }}</div>
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <span class="inline-flex items-center px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold border border-indigo-100">
                                    +{{ $item->poin_otomatis }} Pts
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-xs text-slate-500">Tidak ada antrean verifikasi saat ini.</div>
                    @endforelse
                </div>
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
                        const backgroundColors = data.map((value) => {
                            const numericValue = Number(value) || 0;
                            if (numericValue === 0) {
                                return 'rgba(239, 68, 68, 0.85)';
                            }
                            if (numericValue < 20) {
                                return 'rgba(245, 158, 11, 0.85)';
                            }
                            return 'rgba(99, 102, 241, 0.85)';
                        });
                        const borderColors = data.map((value) => {
                            const numericValue = Number(value) || 0;
                            if (numericValue === 0) {
                                return 'rgba(220, 38, 38, 1)';
                            }
                            if (numericValue < 20) {
                                return 'rgba(217, 119, 6, 1)';
                            }
                            return 'rgba(79, 70, 229, 1)';
                        });
                        const hoverBackgroundColors = data.map((value) => {
                            const numericValue = Number(value) || 0;
                            if (numericValue === 0) {
                                return 'rgba(220, 38, 38, 1)';
                            }
                            if (numericValue < 20) {
                                return 'rgba(217, 119, 6, 1)';
                            }
                            return 'rgba(79, 70, 229, 1)';
                        });

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Poin SKKM',
                                    data: data,
                                    backgroundColor: backgroundColors,
                                    borderColor: borderColors,
                                    borderWidth: 1,
                                    borderRadius: 6,
                                    hoverBackgroundColor: hoverBackgroundColors,
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
