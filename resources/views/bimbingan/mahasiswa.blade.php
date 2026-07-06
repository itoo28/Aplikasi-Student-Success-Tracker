<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Bimbingan Akademik Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        @php
            $pendingBimbinganWhatsapp = $pendingRiwayat->first(fn ($item) => filled($item->dosen_whatsapp_link));
        @endphp
        
        {{-- Status Kelayakan Card --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-6 sm:p-8 text-white shadow-2xl shadow-indigo-300/30">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-purple-400/10 blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="text-xs font-semibold text-indigo-200 uppercase tracking-wider">Status Kelayakan Bimbingan Akademik</span>
                    <h3 class="text-xl sm:text-2xl font-extrabold mt-1">Informasi Bimbingan Semester {{ $semesterAktif }}</h3>
                    <p class="text-indigo-200 text-xs sm:text-sm mt-2">
                        Anda telah menyelesaikan bimbingan sebanyak <strong class="text-white">{{ $bimbinganSemesterIni }} kali</strong> dari batas maksimal 3 kali semester ini.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if($bimbinganSemesterIni >= 1)
                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 bg-emerald-400/20 text-emerald-200 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold border border-emerald-400/30 backdrop-blur-md">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Administrasi Terpenuhi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 sm:gap-1.5 px-3 py-1.5 sm:px-4 sm:py-2 bg-rose-400/20 text-rose-200 rounded-xl sm:rounded-2xl text-xs sm:text-sm font-bold border border-rose-400/30 backdrop-blur-md">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Belum Memenuhi Syarat
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if(session('submitted_bimbingan_topik'))
            <div class="relative overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-5 sm:p-6 shadow-[0_10px_32px_rgba(16,185,129,0.12)]">
                <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-emerald-200/30 blur-2xl"></div>
                <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">
                            Menunggu Validasi Dosen PA
                        </span>
                        <h3 class="mt-3 text-base sm:text-lg font-bold text-slate-800">Pengajuan bimbingan berhasil dikirim</h3>
                        <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-600">
                            Pengajuan topik <strong class="text-slate-800">{{ session('submitted_bimbingan_topik') }}</strong> untuk tanggal
                            <strong class="text-slate-800">{{ session('submitted_bimbingan_tanggal') }}</strong> sedang menunggu persetujuan dari
                            dosen PA{{ session('submitted_bimbingan_dosen_name') ? ' ' . session('submitted_bimbingan_dosen_name') : '' }}.
                            Silakan hubungi dosen pembimbing melalui WhatsApp agar pengajuan Anda segera diperiksa.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if(session('submitted_bimbingan_whatsapp_link'))
                            <a href="{{ session('submitted_bimbingan_whatsapp_link') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-emerald-300 w-full sm:w-auto justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 shrink-0">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Hubungi Dosen
                            </a>
                        @else
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs sm:text-sm text-amber-700">
                                Nomor WhatsApp dosen PA belum tersedia. Silakan hubungi admin program studi.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @elseif($pendingRiwayat->isNotEmpty())
            <div class="rounded-3xl border border-amber-200 bg-amber-50/80 p-5 shadow-[0_8px_24px_rgba(245,158,11,0.12)]">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h3 class="text-base font-bold text-amber-900">Masih ada {{ $pendingRiwayat->count() }} pengajuan menunggu validasi dosen PA</h3>
                        <p class="mt-1 text-xs sm:text-sm text-amber-800/90">
                            Pengajuan bimbingan mahasiswa baru akan diproses setelah dosen PA melakukan validasi. Silakan hubungi dosen pembimbing Anda agar pengajuan segera diperiksa.
                        </p>
                    </div>
                    @if($pendingBimbinganWhatsapp)
                        <a href="{{ $pendingBimbinganWhatsapp->dosen_whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-4 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-emerald-300 w-full sm:w-auto justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 shrink-0">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Hubungi Dosen
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Form Pengajuan -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden p-5 sm:p-8">
            <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-6">Pengajuan Bimbingan Baru</h3>
            
            @if($bimbinganSemesterIni >= 3)
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-5 text-xs sm:text-sm">
                    Anda sudah menyelesaikan batas maksimal bimbingan untuk semester ini (3 kali). Form pengajuan baru telah dinonaktifkan.
                </div>
            @else
                <form action="{{ route('bimbingan.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 sm:gap-6 sm:grid-cols-2">
                        <flux:input type="date" name="tanggal" label="Tanggal Bimbingan" required />
                        <flux:input type="text" name="topik" label="Topik/Kendala Bimbingan" placeholder="Contoh: Konsultasi KRS, Kesulitan Belajar" required />
                    </div>
                    <div class="mt-6">
                        <flux:input type="file" name="document" label="Dokumen Pendukung (Opsional, PDF/JPG)" />
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105 w-full sm:w-auto justify-center">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-4 sm:px-8 py-4 sm:py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-slate-800">Riwayat Bimbingan</h3>
                </div>
                <div class="flex-shrink-0 w-full sm:w-auto">
                    <form method="GET" action="{{ route('bimbingan.mahasiswa.index') }}" class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto">
                        <label for="status" class="text-xs font-semibold text-slate-550 whitespace-nowrap">Filter Status:</label>
                        <select name="status" id="status-filter" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white pl-3 pr-8 py-1.5 text-xs text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all cursor-pointer w-full sm:w-auto">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="validated" {{ $status === 'validated' ? 'selected' : '' }}>Disetujui</option>
                            <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="revised" {{ $status === 'revised' ? 'selected' : '' }}>Ditolak</option>
                            <option value="canceled" {{ $status === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($riwayat as $log)
                <div class="px-4 sm:px-8 py-4 sm:py-5 hover:bg-slate-50/60 transition-colors" x-data="{ showDetails: false }">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                {{-- Icon Status --}}
                                <div class="flex-shrink-0">
                                    @if($log->status === 'validated' || $log->status === 'completed')
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 shrink-0">
                                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($log->status === 'revised')
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 shrink-0">
                                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    @elseif($log->status === 'canceled')
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center border border-slate-200 shrink-0">
                                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 shrink-0">
                                            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-800 text-sm truncate">{{ $log->topik }}</h4>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 text-[11px] text-slate-500">
                                        <span class="inline-flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $log->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="inline-flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                            Semester {{ $log->semester }}
                                        </span>
                                        @switch($log->status)
                                            @case('pending')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 font-bold border border-amber-200">Pending</span>
                                                @break
                                            @case('validated')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold border border-emerald-200">Disetujui</span>
                                                @break
                                            @case('revised')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 font-bold border border-rose-200">Ditolak</span>
                                                @break
                                            @case('completed')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 font-bold border border-indigo-200">Selesai</span>
                                                @break
                                            @case('canceled')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-50 text-slate-700 font-bold border border-slate-200">Dibatalkan</span>
                                                @break
                                            @default
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-50 text-slate-700 font-bold border border-slate-200">{{ ucfirst($log->status) }}</span>
                                        @endswitch
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3 sm:flex-shrink-0 self-end sm:self-center">
                                <button @click="showDetails = !showDetails" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all focus:outline-none shrink-0">
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
                                    <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Catatan / Instruksi</p>
                                    <p class="leading-relaxed bg-white p-2.5 rounded-xl border border-slate-100 text-slate-800">
                                        {{ $log->catatan && $log->catatan !== '-' ? $log->catatan : 'Tidak ada catatan atau instruksi khusus.' }}
                                    </p>
                                </div>
                                
                                <div class="flex flex-col gap-3 justify-center">
                                    @if($log->status === 'pending')
                                        <p class="text-[11px] font-medium text-amber-700 leading-relaxed">
                                            Pengajuan ini masih menunggu validasi dosen PA. Setelah mengirim pengajuan, silakan hubungi dosen pembimbing melalui tombol WhatsApp.
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($log->status === 'pending' && $log->dosen_whatsapp_link)
                                            <a href="{{ $log->dosen_whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm shrink-0" title="Hubungi Dosen PA via WA">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 shrink-0">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                                <span>Hubungi Dosen</span>
                                            </a>
                                        @endif

                                        @if($log->document_path)
                                            <a href="{{ Storage::url($log->document_path) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 text-slate-700 text-xs font-bold hover:bg-indigo-100 hover:text-indigo-650 transition-colors shadow-sm shrink-0" title="Lihat Bukti">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                <span>Lihat Dokumen Pendukung</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($log->status === 'completed')
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4">
                                    <p class="font-bold text-emerald-800 mb-1 uppercase tracking-wider text-[10px]">Laporan Bimbingan Selesai</p>
                                    <p class="text-xs text-slate-800 leading-relaxed">{{ $log->resolution }}</p>
                                    @if($log->activity_photo_path)
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($log->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100 transition-colors shadow-sm">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
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
                    <h3 class="text-lg font-bold text-slate-800">Belum Ada Riwayat Bimbingan</h3>
                    <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto">Riwayat pengajuan bimbingan akademik Anda akan tampil di sini.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
