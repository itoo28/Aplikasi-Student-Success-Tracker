<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Poin SKKM Saya') }}
        </h2>
    </x-slot>

    @php
        $percentage = $targetKelulusan > 0 ? min(($approvedPoints / $targetKelulusan) * 100, 100) : 0;
        $isWarning = $approvedPoints < $targetKelulusan && ($user->semester ?? 1) >= (($user->jenjang_studi ?? 'S1') === 'D3' ? 5 : 7);
    @endphp

    <div class="space-y-8">

        {{-- Peringatan Dini --}}
        @if($isWarning)
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 p-5 text-white shadow-lg shadow-orange-200/50">
            <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 blur-xl"></div>
            <div class="relative flex items-center gap-4">
                <div class="flex-shrink-0 p-2.5 bg-white/20 rounded-xl backdrop-blur-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <div>
                    <p class="font-bold text-sm">Peringatan Dini Kelulusan</p>
                    <p class="text-amber-100 text-xs mt-0.5">Anda masih membutuhkan <strong class="text-white">{{ $remainingPoints }} poin</strong> lagi. Segera lengkapi SKKM sebelum masa studi berakhir.</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Hero Progress Card --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-6 sm:p-8 text-white shadow-2xl shadow-indigo-300/30">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-purple-400/10 blur-2xl"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 items-center">
                {{-- Circular Progress --}}
                <div class="flex flex-col items-center">
                    <div class="relative w-28 h-28 sm:w-36 sm:h-36">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 120 120">
                            <circle cx="60" cy="60" r="52" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="10"/>
                            <circle cx="60" cy="60" r="52" fill="none" stroke="url(#progressGrad)" stroke-width="10" stroke-linecap="round"
                                stroke-dasharray="{{ 2 * 3.14159 * 52 }}" stroke-dashoffset="{{ 2 * 3.14159 * 52 * (1 - $percentage / 100) }}"
                                class="transition-all duration-1000 ease-out"/>
                            <defs>
                                <linearGradient id="progressGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" stop-color="#a5b4fc"/>
                                    <stop offset="100%" stop-color="#e9d5ff"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl sm:text-3xl font-extrabold">{{ round($percentage) }}%</span>
                            <span class="text-indigo-200 text-[8px] sm:text-[10px] font-semibold uppercase tracking-wider">Tercapai</span>
                        </div>
                    </div>
                    <p class="mt-3 text-xs sm:text-sm font-semibold text-indigo-200">{{ $approvedPoints }} / {{ $targetKelulusan }} Poin</p>
                </div>

                {{-- Stats Grid --}}
                <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-emerald-400/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-[8px] sm:text-[10px] font-semibold text-indigo-200 uppercase tracking-wider">Disetujui</span>
                        </div>
                        <p class="text-xl sm:text-2xl font-extrabold">{{ $approvedCount }}</p>
                        <p class="text-[10px] sm:text-[11px] text-indigo-300 mt-0.5">+{{ $approvedPoints }} poin</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-amber-400/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-[8px] sm:text-[10px] font-semibold text-indigo-200 uppercase tracking-wider">Menunggu</span>
                        </div>
                        <p class="text-xl sm:text-2xl font-extrabold">{{ $pendingCount }}</p>
                        <p class="text-[10px] sm:text-[11px] text-indigo-300 mt-0.5">+{{ $pendingPoints }} poin</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-rose-400/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <span class="text-[8px] sm:text-[10px] font-semibold text-indigo-200 uppercase tracking-wider">Ditolak</span>
                        </div>
                        <p class="text-xl sm:text-2xl font-extrabold">{{ $rejectedCount }}</p>
                        <p class="text-[10px] sm:text-[11px] text-indigo-300 mt-0.5">revisi</p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-3 sm:p-4 border border-white/10 hover:bg-white/15 transition-colors">
                        <div class="flex items-center gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                            <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-violet-400/20 flex items-center justify-center">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                            <span class="text-[8px] sm:text-[10px] font-semibold text-indigo-200 uppercase tracking-wider">Sisa Target</span>
                        </div>
                        <p class="text-xl sm:text-2xl font-extrabold">{{ $remainingPoints }}</p>
                        <p class="text-[10px] sm:text-[11px] text-indigo-300 mt-0.5">poin lagi</p>
                    </div>
                </div>
            </div>

            {{-- Status Yudisium Badge --}}
            <div class="relative z-10 mt-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                    <span class="text-xs font-medium text-indigo-350">Status Kelulusan:</span>
                    @if($statusYudisium == 'memenuhi')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-400/20 text-emerald-200 rounded-full text-xs font-bold border border-emerald-400/30">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Memenuhi Syarat Yudisium
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-400/20 text-amber-200 rounded-full text-xs font-bold border border-amber-400/30">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Dalam Proses
                        </span>
                    @endif
                </div>
                <span class="text-xs text-indigo-300 sm:self-center">
                    {{ $user->jenjang_studi ?? 'S1' }} · Semester {{ $user->semester ?? '-' }}
                </span>
            </div>
        </div>

        {{-- Action Button --}}
        <div class="flex justify-end">
            <a href="{{ route('skkm.create') }}" class="group inline-flex items-center px-5 py-3 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl overflow-hidden transition-all hover:from-indigo-700 hover:to-purple-700 shadow-lg shadow-indigo-500/20 hover:scale-105 w-full sm:w-auto justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajukan Baru
            </a>
        </div>

        {{-- Filter Tabs + Table --}}
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            {{-- Header --}}
            <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Riwayat Pengajuan</h3>
                    <div class="flex items-center gap-2 overflow-x-auto mobile-scroll-x pb-1 sm:pb-0">
                        <button data-filter="all" class="filter-btn active mobile-scroll-item mobile-nowrap px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all bg-indigo-600 text-white shadow-sm">
                            Semua ({{ $submissions->count() }})
                        </button>
                        <button data-filter="disetujui" class="filter-btn mobile-scroll-item mobile-nowrap px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700">
                            Disetujui ({{ $approvedCount }})
                        </button>
                        <button data-filter="pending" class="filter-btn mobile-scroll-item mobile-nowrap px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-amber-50 hover:text-amber-700">
                            Menunggu ({{ $pendingCount }})
                        </button>
                        <button data-filter="ditolak" class="filter-btn mobile-scroll-item mobile-nowrap px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all bg-slate-100 text-slate-600 hover:bg-rose-50 hover:text-rose-700">
                            Ditolak ({{ $rejectedCount }})
                        </button>
                    </div>
                </div>
            </div>

            {{-- Card List (Mobile-friendly) --}}
            <div class="divide-y divide-slate-100">
                @forelse($submissions as $sub)
                <div class="submission-row px-4 sm:px-8 py-4 sm:py-5 hover:bg-slate-50/60 transition-colors" data-status="{{ $sub->status_verifikasi }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        {{-- Left: Icon + Info --}}
                        <div class="flex items-start gap-3 sm:gap-4 flex-1 min-w-0">
                            {{-- Status Icon --}}
                            <div class="flex-shrink-0 mt-0.5">
                                @if($sub->status_verifikasi === 'disetujui')
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @elseif($sub->status_verifikasi === 'ditolak')
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @else
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="min-w-0 flex-1">
                                <h4 class="font-semibold text-slate-800 text-sm truncate" title="{{ $sub->nama_kegiatan }}">{{ $sub->nama_kegiatan }}</h4>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500">
                                    <span class="inline-flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($sub->tanggal_kegiatan)->format('d M Y') }}
                                    </span>
                                    <span class="inline-flex items-center max-w-[150px] sm:max-w-none truncate" title="{{ str_replace('_', ' ', ucwords($sub->pointRule->unsur ?? '-', '_')) }}">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"></path></svg>
                                        {{ str_replace('_', ' ', ucwords($sub->pointRule->unsur ?? '-', '_')) }}
                                    </span>
                                    @if($sub->pointRule->tingkat)
                                    <span class="inline-flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                                        {{ ucfirst($sub->pointRule->tingkat) }}
                                    </span>
                                    @endif
                                </div>

                                @if($sub->catatan_dosen)
                                    <div class="mt-2 inline-flex items-start gap-1.5 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                        <span class="text-[11px] text-slate-650 leading-relaxed">{{ $sub->catatan_dosen }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right: Poin + Status + Action --}}
                        <div class="flex items-center justify-between sm:justify-end gap-3 sm:flex-shrink-0 w-full sm:w-auto border-t border-slate-50 pt-3 sm:border-t-0 sm:pt-0">
                            {{-- Poin --}}
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-sm font-bold
                                {{ $sub->status_verifikasi === 'disetujui' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                                +{{ $sub->poin_otomatis }} Poin
                            </span>

                            {{-- Status Badge --}}
                            @if($sub->status_verifikasi === 'pending')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-amber-55 text-amber-700 rounded-full text-[10px] font-bold border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu
                                </span>
                            @elseif($sub->status_verifikasi === 'ditolak')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-rose-50 text-rose-700 rounded-full text-[10px] font-bold border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                </span>
                            @endif

                            {{-- View Bukti --}}
                            <a href="{{ Storage::url($sub->file_bukti) }}" target="_blank"
                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors shrink-0" title="Lihat Bukti">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-8 py-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 mb-5">
                        <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Pengajuan</h3>
                    <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto">Mulai kumpulkan poin SKKM dengan mengajukan kegiatan pertama Anda.</p>
                    <a href="{{ route('skkm.create') }}" class="inline-flex items-center mt-6 px-6 py-3 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-lg shadow-indigo-500/30 hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajukan Poin SKKM
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Empty state for filtered --}}
            <div id="filter-empty" class="hidden px-8 py-12 text-center">
                <p class="text-sm text-slate-500">Tidak ada pengajuan dengan filter ini.</p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.submission-row');
        const emptyEl = document.getElementById('filter-empty');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const filter = this.dataset.filter;

                // Update active button
                buttons.forEach(b => {
                    b.classList.remove('bg-indigo-600', 'text-white', 'shadow-sm', 'active');
                    b.classList.add('bg-slate-100', 'text-slate-600');
                });
                this.classList.remove('bg-slate-100', 'text-slate-600');
                this.classList.add('bg-indigo-600', 'text-white', 'shadow-sm', 'active');

                // Filter rows
                let visibleCount = 0;
                rows.forEach(row => {
                    const status = row.dataset.status;
                    if (filter === 'all' || status === filter) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide empty state
                if (emptyEl) {
                    emptyEl.classList.toggle('hidden', visibleCount > 0);
                }
            });
        });
    });
    </script>
    @endpush
</x-app-layout>
