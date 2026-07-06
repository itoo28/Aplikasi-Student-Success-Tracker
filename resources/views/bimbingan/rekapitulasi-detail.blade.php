<x-app-layout>
    @php
        $skkmRole = auth()->user()->resolvedSkkmRole();
        $backRoute = match ($skkmRole) {
            'super_admin' => route('admin.bimbingan'),
            'kaprodi' => route('bimbingan.rekapitulasi.kaprodi'),
            'kemahasiswaan' => route('bimbingan.rekapitulasi.kemahasiswaan'),
            default => route('dashboard'),
        };
        
        $avatarInitials = \Illuminate\Support\Str::of($mahasiswa->name)
            ->explode(' ')
            ->filter()
            ->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))
            ->take(2)
            ->implode('');

        $programStudi = $mahasiswa->programStudi
            ? trim(($mahasiswa->programStudi->jenjang ? $mahasiswa->programStudi->jenjang . ' ' : '') . $mahasiswa->programStudi->nama)
            : '-';
    @endphp

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                    {{ __('Detail Bimbingan Akademik') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">Memantau riwayat dan dokumen bimbingan akademik mahasiswa.</p>
            </div>
            <div>
                <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors rounded-xl text-sm font-semibold shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        {{-- Student Profile Summary Card --}}
        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex-shrink-0 flex items-center justify-center">
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-extrabold text-xl border border-indigo-100">
                            {{ $avatarInitials }}
                        </div>
                        <div class="absolute -bottom-1.5 -right-1.5 w-6 h-6 rounded-full bg-slate-900 border-2 border-white flex items-center justify-center text-[10px] text-white font-bold" title="Role Mahasiswa">
                            M
                        </div>
                    </div>
                </div>
                
                <div class="flex-grow min-w-0 grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Mahasiswa</span>
                        <h3 class="text-lg font-bold text-slate-800 truncate mt-1">{{ $mahasiswa->name }}</h3>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $mahasiswa->identifier ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Program Studi</span>
                        <h4 class="text-sm font-bold text-slate-800 mt-1 truncate">{{ $programStudi }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5 truncate">{{ $mahasiswa->programStudi?->fakultas?->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dosen PA</span>
                        <h4 class="text-sm font-bold text-slate-800 mt-1 truncate">{{ $mahasiswa->lecturer?->name ?? 'Belum Ditentukan' }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $mahasiswa->lecturer?->identifier ? 'NIDN: ' . $mahasiswa->lecturer->identifier : '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bimbingan Logs Timeline --}}
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Riwayat Sesi Bimbingan</h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse ($riwayat as $log)
                    <div class="px-8 py-5 hover:bg-slate-50/50 transition-colors" x-data="{ showDetails: false }">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    {{-- Status Indicator Icon --}}
                                    <div class="flex-shrink-0">
                                        @if($log->status === 'validated' || $log->status === 'completed')
                                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            </div>
                                        @elseif($log->status === 'revised')
                                            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @elseif($log->status === 'canceled')
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center border border-slate-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-slate-800 text-sm truncate">{{ $log->topik }}</h4>
                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-xs text-slate-500">
                                            <span class="inline-flex items-center text-xs text-slate-500">
                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $log->tanggal->format('d M Y') }}
                                            </span>
                                            <span class="inline-flex items-center text-xs text-slate-500">
                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                                Semester {{ $log->semester }}
                                            </span>
                                            <span class="inline-flex items-center text-xs text-slate-500">
                                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                PA: {{ $log->dosen?->name ?? 'Belum Ditentukan' }}
                                            </span>
                                            <span class="inline-flex items-center text-xs text-slate-500">
                                                {{ $log->tipe_pengajuan_label }}
                                            </span>
                                            @switch($log->status)
                                                @case('pending')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">Pending</span>
                                                    @break
                                                @case('revised')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">Ditolak / Revisi</span>
                                                    @break
                                                @case('completed')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200">Selesai</span>
                                                    @break
                                                @case('canceled')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[10px] font-bold border border-slate-200">Dibatalkan</span>
                                                    @break
                                                @default
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">Disetujui</span>
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 sm:flex-shrink-0 self-end sm:self-center">
                                    <button @click="showDetails = !showDetails" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all focus:outline-none">
                                        <span x-text="showDetails ? 'Sembunyikan' : 'Detail Sesi'"></span>
                                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div x-show="showDetails" x-collapse class="mt-2 border-t border-slate-100 pt-4 space-y-4" x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-650 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50">
                                    <div>
                                        <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Catatan Pembimbing</p>
                                        <p class="leading-relaxed bg-white p-2.5 rounded-xl border border-slate-100 text-slate-800">
                                            {{ $log->catatan && $log->catatan !== '-' ? $log->catatan : 'Tidak ada catatan pembimbing.' }}
                                        </p>
                                    </div>
                                    
                                    <div class="flex flex-col gap-2 justify-center">
                                        @if($log->document_path)
                                            <div>
                                                <a href="{{ Storage::url($log->document_path) }}" target="_blank"
                                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-indigo-100 hover:text-indigo-650 transition-colors shadow-sm" title="Download / Lihat Dokumen Pendukung">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span>Lihat Dokumen Pendukung</span>
                                                </a>
                                            </div>
                                        @else
                                            <p class="text-slate-400 italic">Tidak ada dokumen pendukung.</p>
                                        @endif
                                    </div>
                                </div>

                                @if($log->status === 'completed')
                                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4">
                                        <p class="font-bold text-emerald-800 mb-1 uppercase tracking-wider text-[10px]">Laporan Hasil Bimbingan</p>
                                        <p class="text-xs text-slate-700 leading-relaxed">{{ $log->resolution }}</p>
                                        @if($log->activity_photo_path)
                                            <div class="mt-3">
                                                <a href="{{ Storage::url($log->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-colors shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span>Lihat Foto Kegiatan</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-8 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 mb-5">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Tidak Ada Riwayat Bimbingan</h3>
                        <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto">Mahasiswa ini belum memiliki catatan atau riwayat bimbingan akademik di sistem.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
