<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Kaprodi
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Monitoring SKKM') }}
            </h2>
        </div>
    </x-slot>

    @php
        $pendingCount = $submissions->where('status_verifikasi', 'pending')->count();
        $approvedCount = $submissions->where('status_verifikasi', 'disetujui')->count();
        $rejectedCount = $submissions->where('status_verifikasi', 'ditolak')->count();
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgb(79,70,229,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Scope Verifikasi</p>
                    <h3 class="mt-2 text-lg font-extrabold">{{ $scopeLabel }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-sky-500 to-cyan-500 text-white shadow-[0_14px_35px_rgb(14,165,233,0.30)]">
                    <p class="text-sm font-semibold text-sky-100">Total Pengajuan</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $submissions->total() }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgb(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Pending (Halaman Ini)</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $pendingCount }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-[0_14px_35px_rgb(16,185,129,0.30)]">
                    <p class="text-sm font-semibold text-emerald-100">Disetujui / Ditolak</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $approvedCount }} / {{ $rejectedCount }}</h3>
                </div>
            </div>

            @if (session('success'))
                <div id="kaprodi-success-alert" class="flex items-start justify-between gap-3 rounded-2xl border border-emerald-200 bg-emerald-100/90 px-4 py-3 text-sm text-emerald-700 shadow-sm">
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="font-semibold">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="closeKaprodiAlert('kaprodi-success-alert')" class="rounded-lg p-1 text-emerald-700 transition hover:bg-emerald-200/70" aria-label="Tutup notifikasi sukses">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div id="kaprodi-error-alert" class="flex items-start justify-between gap-3 rounded-2xl border border-rose-200 bg-rose-100/90 px-4 py-3 text-sm text-rose-700 shadow-sm">
                    <div class="flex items-start gap-2">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5.07 18h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 15c-.77 1.33.19 3 1.73 3z"></path>
                        </svg>
                        <p class="font-semibold">{{ $errors->first() }}</p>
                    </div>
                    <button type="button" onclick="closeKaprodiAlert('kaprodi-error-alert')" class="rounded-lg p-1 text-rose-700 transition hover:bg-rose-200/70" aria-label="Tutup notifikasi error">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            @endif

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan SKKM</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status & Verifikator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse ($submissions as $submission)
                                <tr class="align-top transition-colors hover:bg-indigo-50/40">
                                    <td class="px-6 py-5">
                                        <div class="font-semibold text-slate-800">{{ $submission->mahasiswa?->name }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $submission->mahasiswa?->identifier }} - {{ $submission->mahasiswa?->programStudi?->nama ?? 'Prodi belum diatur' }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-semibold text-slate-800">{{ $submission->nama_kegiatan }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $submission->pointRule?->jenis_item }} - {{ $submission->pointRule?->tingkat ?? '-' }}</div>
                                        <a href="{{ Storage::url($submission->file_bukti) }}" target="_blank" class="mt-2 inline-flex items-center gap-1 rounded-lg border border-indigo-200 bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 transition hover:bg-indigo-100">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Lihat Bukti
                                        </a>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 font-bold text-indigo-700">+{{ $submission->poin_otomatis }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        @if($submission->status_verifikasi === 'disetujui')
                                            <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Disetujui</span>
                                        @elseif($submission->status_verifikasi === 'ditolak')
                                            <span class="inline-flex items-center rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-700">Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-amber-100 px-2.5 py-1 text-xs font-bold text-amber-700">Pending</span>
                                        @endif
                                        <p class="mt-2 text-xs text-slate-500">Oleh: {{ $submission->verifiedBy?->name ?? '-' }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum ada pengajuan SKKM di program studi ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $submissions->links() }}
            </div>
        </div>
    </div>

    <script>
        function closeKaprodiAlert(id) {
            const element = document.getElementById(id);
            if (!element) {
                return;
            }

            element.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => element.remove(), 300);
        }

        window.setTimeout(() => closeKaprodiAlert('kaprodi-success-alert'), 4500);
    </script>
</x-app-layout>
