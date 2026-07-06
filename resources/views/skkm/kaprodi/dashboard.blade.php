<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Kaprodi
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">Dashboard Monitoring SKKM</h2>
        </div>
    </x-slot>

    @php
        $totalSubmissions = (int) ($statusSummary->total_submissions ?? 0);
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-8 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                <p class="text-sm font-semibold uppercase tracking-wide text-indigo-100">Scope Monitoring</p>
                <h3 class="mt-2 text-2xl font-extrabold">{{ $scopeLabel }}</h3>
                <p class="mt-2 text-sm text-indigo-100">Pantau progres mahasiswa, status verifikasi, dan kondisi akhir poin SKKM pada program studi Anda.</p>
            </div>

            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Total Mahasiswa</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalStudents }}</h3>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Sudah Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsFulfilled }}</h3>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-white shadow-[0_14px_35px_rgba(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Dalam Proses</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsInProgress }}</h3>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Total Pengajuan</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalSubmissions }}</h3>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-indigo-100/80 bg-white/90 shadow-[0_8px_30px_rgb(37,99,235,0.12)] backdrop-blur-sm">
                <div class="border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70 px-8 py-6">
                    <h3 class="text-lg font-bold text-slate-800">Pengajuan Terbaru</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-indigo-50/70 text-xs uppercase text-indigo-700">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th class="px-6 py-4 text-center font-semibold tracking-wider">Poin</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse ($recentSubmissions as $submission)
                                <tr class="transition-colors hover:bg-indigo-50/40">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $submission->mahasiswa?->name }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $submission->mahasiswa?->identifier ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $submission->nama_kegiatan }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $submission->pointRule?->jenis_item ?? '-' }} - {{ $submission->pointRule?->tingkat ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">+{{ $submission->poin_otomatis }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($submission->status_verifikasi === 'disetujui')
                                            <span class="inline-flex rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Disetujui</span>
                                        @elseif ($submission->status_verifikasi === 'ditolak')
                                            <span class="inline-flex rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-700">Ditolak</span>
                                        @else
                                            <span class="inline-flex rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14-4H5m14 8H5m14 4H5"></path>
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada pengajuan SKKM terbaru untuk ditampilkan.</p>
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
