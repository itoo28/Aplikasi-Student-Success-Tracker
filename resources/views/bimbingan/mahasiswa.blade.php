<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Bimbingan Akademik Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        {{-- Status Kelayakan Card --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-700 p-8 text-white shadow-2xl shadow-indigo-300/30">
            <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 rounded-full bg-purple-400/10 blur-2xl"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="text-xs font-semibold text-indigo-200 uppercase tracking-wider">Status Kelayakan Bimbingan Akademik</span>
                    <h3 class="text-2xl font-extrabold mt-1">Informasi Bimbingan Semester {{ $semesterAktif }}</h3>
                    <p class="text-indigo-200 text-sm mt-2">
                        Anda telah menyelesaikan bimbingan sebanyak <strong class="text-white">{{ $bimbinganSemesterIni }} kali</strong> dari batas maksimal 3 kali semester ini.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    @if($bimbinganSemesterIni >= 1)
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-400/20 text-emerald-200 rounded-2xl text-sm font-bold border border-emerald-400/30 backdrop-blur-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Syarat Administrasi Terpenuhi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-400/20 text-rose-200 rounded-2xl text-sm font-bold border border-rose-400/30 backdrop-blur-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Belum Memenuhi Syarat
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Form Pengajuan -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden p-8">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Pengajuan Bimbingan Baru</h3>
            
            @if($bimbinganSemesterIni >= 3)
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl p-5 text-sm">
                    Anda sudah menyelesaikan batas maksimal bimbingan untuk semester ini (3 kali). Form pengajuan baru telah dinonaktifkan.
                </div>
            @else
                <form action="{{ route('bimbingan.mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <flux:input type="date" name="tanggal" label="Tanggal Bimbingan" required />
                        <flux:input type="text" name="topik" label="Topik/Kendala Bimbingan" placeholder="Contoh: Konsultasi KRS, Kesulitan Belajar" required />
                    </div>
                    <div class="mt-6">
                        <flux:input type="file" name="document" label="Dokumen Pendukung (Opsional, PDF/JPG)" />
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- Riwayat Pengajuan -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Riwayat Bimbingan</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($riwayat as $log)
                <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        
                        {{-- Icon Status --}}
                        <div class="flex-shrink-0 mt-0.5">
                            @if($log->status === 'validated' || $log->status === 'completed')
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            @elseif($log->status === 'revised')
                                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $log->topik }}</h4>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                <span class="inline-flex items-center text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $log->tanggal->format('d M Y') }}
                                </span>
                                <span class="inline-flex items-center text-xs text-slate-500">
                                    <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    Semester {{ $log->semester }}
                                </span>
                            </div>

                            @if($log->catatan && $log->catatan !== '-')
                                <div class="mt-2 inline-flex items-start gap-1.5 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100">
                                    <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    <span class="text-[11px] text-slate-600 leading-relaxed">{{ $log->catatan }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Actions / Status --}}
                        <div class="flex items-center gap-3 sm:flex-shrink-0">
                            @if($log->status === 'pending')
                                @if($log->dosen_whatsapp_link)
                                    <a href="{{ $log->dosen_whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition-colors border border-emerald-100/50" title="Hubungi Dosen PA via WA">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.79 11.79 0 0012.03.007 11.875 11.875 0 002.5 11.89 11.6 11.6 0 001.77 16.4l.03.41 2.69-.87a.62.62 0 01.45.02l2.82 1.02a.61.61 0 01.34.3l.3.72a.6.6 0 01-.1.66l-1.42 1.76a.63.63 0 01-.58.24 12.32 12.32 0 01-4.18-.97 12.15 12.15 0 01-2.77-2.09C.47 16.99 1.73 9.77 6.75 4.76A11.688 11.688 0 0112.03 1.5c3.13 0 6.05 1.22 8.24 3.43a11.64 11.64 0 013.44 8.25c0 3.09-1.2 5.99-3.38 8.18l-.42.41-2.8-1.01a.62.62 0 01-.33-.28l-.21-.35a.59.59 0 01.1-.63l1.4-1.74a.6.6 0 01.29-.2l.63-.2a.6.6 0 00.36-.27l.95-1.39c1.57-1.91 2.44-4.37 2.44-6.99a11.68 11.68 0 00-3.38-8.23zm-6.44 12.65c-.24.65-1.4 1.25-1.92 1.31-.52.05-1.14.08-2.31-.54-1.17-.61-2.21-1.77-2.56-1.89-.36-.12-.79-.19-1.34.19-.55.38-2.08 1.56-2.08 1.56s-1.17-.33-2.25-1.11c-1.06-.77-1.62-1.93-1.82-2.37-.2-.44-.02-.69.39-.95.4-.25.85-.63 1.2-.95.38-.34.47-.56.7-.94.23-.39.12-.72-.06-.99-.17-.27-1.4-3.4-1.92-4.63-.5-1.23-.99-1.07-1.37-1.09-.35-.02-.76-.02-1.17-.02-.39 0-.99.14-1.5.7-.51.57-1.91 1.86-1.91 4.54 0 2.69 1.96 5.28 2.24 5.64.27.35 3.87 5.96 9.4 8.1 5.83 2.24 5.83 1.48 6.88 1.39 1.05-.1 3.94-1.6 4.5-3.15.56-1.55.56-2.88.39-3.16-.17-.28-1.69-.53-3.31-1.18a5.31 5.31 0 01-1.67-.86c-.47-.4-.79-.87-.99-1.36z"/></svg>
                                        <span>Hubungi Dosen</span>
                                    </a>
                                @endif
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                </span>
                            @elseif($log->status === 'revised')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                </span>
                            @elseif($log->status === 'completed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                </span>
                            @endif

                            @if($log->document_path)
                                <a href="{{ Storage::url($log->document_path) }}" target="_blank"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors" title="Lihat Bukti">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($log->status === 'completed')
                        <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                            <h4 class="text-sm font-semibold text-emerald-800 mb-2">Laporan Bimbingan Selesai</h4>
                            <p class="text-sm text-slate-700 mb-3">{{ $log->resolution }}</p>
                            @if($log->activity_photo_path)
                                <a href="{{ Storage::url($log->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100">Lihat Foto Kegiatan</a>
                            @endif
                        </div>
                    @endif
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
