<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Dashboard Poin SKKM') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Alert Peringatan Dini (Sesuai PRD) -->
            @if($progress && $progress->total_poin < 80 && $progress->semester_aktif >= 7)
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded-md shadow-sm mb-6 flex items-center" role="alert">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <p><strong>Peringatan Dini:</strong> Segera lengkapi SKKM â€” tersisa sedikit waktu untuk memenuhi syarat Yudisium.</p>
            </div>
            @endif

            <!-- Progress Cards (Glassmorphism) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Card Total Poin -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 text-white shadow-xl shadow-indigo-200 transition-transform duration-300 hover:scale-105">
                    <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white opacity-10 blur-2xl"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 font-medium text-sm tracking-wider uppercase">Total Poin Disetujui</p>
                            <p class="text-5xl font-extrabold mt-2">{{ $progress ? $progress->total_poin : 0 }} <span class="text-xl font-medium text-indigo-200">/ 80</span></p>
                        </div>
                        <div class="p-3 bg-white bg-opacity-20 rounded-2xl backdrop-blur-md">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-6 w-full bg-indigo-900 bg-opacity-30 rounded-full h-2.5">
                        @php $percentage = $progress ? min(($progress->total_poin / 80) * 100, 100) : 0; @endphp
                        <div class="bg-white h-2.5 rounded-full shadow-[0_0_10px_rgba(255,255,255,0.8)]" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <!-- Card Status Yudisium -->
                <div class="rounded-3xl bg-white border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl relative overflow-hidden flex flex-col justify-center transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                    <p class="text-slate-500 font-medium text-sm tracking-wider uppercase mb-1">Status Kelulusan SKKM</p>
                    @if($progress && $progress->status_yudisium == 'memenuhi')
                        <div class="flex items-center space-x-3 text-emerald-600 mt-2">
                            <div class="bg-emerald-100 p-2 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-2xl font-bold">Memenuhi Syarat</span>
                        </div>
                    @else
                        <div class="flex items-center space-x-3 text-amber-500 mt-2">
                            <div class="bg-amber-100 p-2 rounded-xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-2xl font-bold">Dalam Proses</span>
                        </div>
                    @endif
                </div>

                <!-- Card Tambah Pengajuan -->
                <div class="rounded-3xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-indigo-100 p-8 shadow-sm hover:shadow-md transition-all flex flex-col items-center justify-center text-center">
                    <a href="{{ route('skkm.create') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-indigo-600 rounded-full overflow-hidden transition-all hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/50">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span class="relative">Ajukan Poin SKKM</span>
                    </a>
                    <p class="text-slate-500 text-sm mt-4">Upload sertifikat atau surat tugas baru untuk menambah poin.</p>
                </div>
            </div>

            <!-- Table Daftar Pengajuan -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Pengajuan SKKM</h3>
                    <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">{{ $submissions->count() }} Pengajuan</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-8 py-4 font-semibold tracking-wider">Tanggal & Kegiatan</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Unsur / Tingkat</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Poin</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($submissions as $sub)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                                <td class="px-8 py-5">
                                    <div class="font-semibold text-slate-800">{{ $sub->nama_kegiatan }}</div>
                                    <div class="text-xs text-slate-400 mt-1 flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($sub->tanggal_kegiatan)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-slate-700 capitalize">{{ str_replace('_', ' ', $sub->pointRule->unsur) }}</div>
                                    <div class="text-xs text-slate-400 capitalize">{{ $sub->pointRule->tingkat ?? '-' }} â€¢ {{ str_replace('_', ' ', $sub->pointRule->peranan) }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-slate-100 text-slate-600 rounded-lg">
                                        +{{ $sub->poin_otomatis }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    @if($sub->status_verifikasi === 'pending')
                                        <span class="inline-flex items-center bg-amber-50 text-amber-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-amber-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Menunggu Dosen PA
                                        </span>
                                    @elseif($sub->status_verifikasi === 'ditolak')
                                        <span class="inline-flex items-center bg-rose-50 text-rose-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-rose-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span> Ditolak Dosen PA
                                        </span>
                                    @elseif($sub->status_kaprodi === null || $sub->status_kaprodi === 'pending')
                                        <span class="inline-flex items-center bg-sky-50 text-sky-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-sky-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-sky-500"></span> Menunggu Kaprodi
                                        </span>
                                    @elseif($sub->status_kaprodi === 'ditolak')
                                        <span class="inline-flex items-center bg-rose-50 text-rose-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-rose-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span> Ditolak Kaprodi
                                        </span>
                                    @elseif($sub->status_kemahasiswaan === null || $sub->status_kemahasiswaan === 'pending')
                                        <span class="inline-flex items-center bg-indigo-50 text-indigo-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-indigo-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-indigo-500"></span> Menunggu Kemahasiswaan
                                        </span>
                                    @elseif($sub->status_kemahasiswaan === 'ditolak')
                                        <span class="inline-flex items-center bg-rose-50 text-rose-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-rose-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-rose-500"></span> Ditolak Kemahasiswaan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-emerald-500/20">
                                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Final Disetujui
                                        </span>
                                    @endif

                                    @if($sub->catatan_dosen || $sub->catatan_kaprodi || $sub->catatan_kemahasiswaan)
                                        <p class="text-[11px] text-slate-500 mt-2">
                                            {{ $sub->catatan_kemahasiswaan ?? $sub->catatan_kaprodi ?? $sub->catatan_dosen }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ Storage::url($sub->file_bukti) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition-colors">Lihat Bukti</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <h3 class="text-base font-semibold text-slate-800">Belum Ada Pengajuan</h3>
                                    <p class="text-slate-500 mt-1 text-sm">Mulai ajukan poin SKKM Anda dengan klik tombol "Ajukan Poin SKKM".</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
