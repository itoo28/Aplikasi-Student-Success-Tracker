<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Kaprodi
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Monitoring Poin SKKM') }}
            </h2>
        </div>
    </x-slot>

    @php
        $pendingCount = $submissions->where('status_verifikasi', 'pending')->count();
        $approvedCount = $submissions->where('status_verifikasi', 'disetujui')->count();
        $rejectedCount = $submissions->where('status_verifikasi', 'ditolak')->count();

        $hasActiveFilters = filled($search ?? '')
            || filled($selectedSemester ?? null)
            || filled($selectedStatusYudisium ?? '')
            || filled($selectedLecturer ?? null)
            || (int) ($perPage ?? 25) !== 25;
    @endphp

    <div x-data="{ activeTab: '{{ request()->query('tab', 'submissions') }}' }" class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <!-- Segmented Control Tab Switcher -->
            <div class="flex items-center gap-2 p-1.5 bg-slate-200/40 backdrop-blur-sm rounded-2xl w-fit border border-indigo-100/50">
                <button
                    type="button"
                    @click="activeTab = 'submissions'"
                    :class="activeTab === 'submissions' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all duration-200"
                >
                    <i data-lucide="clipboard-list" class="size-4"></i>
                    Daftar Pengajuan
                </button>
                <button
                    type="button"
                    @click="activeTab = 'students'"
                    :class="activeTab === 'students' ? 'bg-white text-indigo-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-bold rounded-xl transition-all duration-200"
                >
                    <i data-lucide="users" class="size-4"></i>
                    Progres Poin Mahasiswa
                </button>
            </div>

            <!-- TAB 1: DAFTAR PENGAJUAN -->
            <div x-show="activeTab === 'submissions'" class="space-y-8" x-cloak>
                <!-- Cards Submissions -->
                <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                        <p class="text-sm font-semibold text-indigo-100">Scope Verifikasi</p>
                        <h3 class="mt-2 text-lg font-extrabold">{{ $scopeLabel }}</h3>
                    </div>
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                        <p class="text-sm font-semibold text-indigo-100">Total Pengajuan</p>
                        <h3 class="mt-2 text-3xl font-extrabold">{{ $submissions->total() }}</h3>
                    </div>
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgba(245,158,11,0.30)]">
                        <p class="text-sm font-semibold text-amber-100">Pending (Halaman Ini)</p>
                        <h3 class="mt-2 text-3xl font-extrabold">{{ $pendingCount }}</h3>
                    </div>
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                        <p class="text-sm font-semibold text-indigo-100">Disetujui / Ditolak</p>
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
                                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Disetujui
                                                </span>
                                            @elseif($submission->status_verifikasi === 'ditolak')
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-0.5 text-[10px] font-bold text-rose-700 border border-rose-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Ditolak
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[10px] font-bold text-amber-700 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>Pending
                                                </span>
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

            <!-- TAB 2: PROGRES POIN MAHASISWA -->
            <div x-show="activeTab === 'students'" class="space-y-8" x-cloak>
                <!-- Cards Students -->
                <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                        <p class="text-sm font-semibold text-indigo-100">Scope Prodi</p>
                        <h3 class="mt-2 text-lg font-extrabold">{{ $scopeLabel }}</h3>
                    </div>
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                        <p class="text-sm font-semibold text-indigo-100">Sudah Memenuhi</p>
                        <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsFulfilled }}</h3>
                    </div>
                    <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgba(245,158,11,0.30)]">
                        <p class="text-sm font-semibold text-amber-100">Dalam Proses</p>
                        <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsInProgress }}</h3>
                    </div>
                </div>

                <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                    <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                        <div class="flex flex-col gap-5">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Poin per Blok Semester</h3>
                                    <p class="text-sm text-slate-500 mt-1">Poin ditampilkan per blok semester (1-2, 3-4, 5-6, 7-8) dan total.</p>
                                </div>
                                <div class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-2 font-semibold text-indigo-700 w-fit">
                                    <i data-lucide="users" class="mr-2 h-4 w-4"></i>
                                    {{ $students->total() }} mahasiswa
                                </div>
                            </div>

                            <form id="kaprodiMahasiswaFilterForm" method="GET" action="{{ route('skkm.kaprodi.index') }}" class="space-y-3">
                                <!-- Menyimpan status tab aktif saat filter disubmit -->
                                <input type="hidden" name="tab" value="students">
                                
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-3 xl:grid-cols-6">
                                    <div class="relative xl:col-span-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                            <i data-lucide="search" class="h-4 w-4"></i>
                                        </div>
                                        <input id="kaprodi-filter-search" type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, NIM, dosen PA" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>

                                    <select id="kaprodi-filter-semester" name="semester" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Semester</option>
                                        @foreach($semesterOptions as $semesterOption)
                                            <option value="{{ $semesterOption }}" @selected((int) ($selectedSemester ?? 0) === (int) $semesterOption)>
                                                Semester {{ $semesterOption }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="kaprodi-filter-status" name="status_yudisium" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Status</option>
                                        @foreach($statusYudisiumOptions as $statusValue => $statusLabel)
                                            <option value="{{ $statusValue }}" @selected(($selectedStatusYudisium ?? '') === $statusValue)>
                                                {{ $statusLabel }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="kaprodi-filter-lecturer" name="lecturer_id" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Semua Dosen PA</option>
                                        @foreach($lecturerOptions as $lecturerOption)
                                            <option value="{{ $lecturerOption->id }}" @selected((int) ($selectedLecturer ?? 0) === (int) $lecturerOption->id)>
                                                {{ $lecturerOption->name }}{{ $lecturerOption->identifier ? ' - ' . $lecturerOption->identifier : '' }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select id="kaprodi-filter-per-page" name="per_page" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach ($perPageOptions as $option)
                                            <option value="{{ $option }}" @selected((int) $perPage === (int) $option)>
                                                {{ $option }} data
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <a
                                        href="{{ route('skkm.kaprodi.mahasiswa.export', request()->query()) }}"
                                        class="inline-flex items-center justify-center gap-2 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 shadow-[0_6px_16px_rgba(22,163,74,0.25)] transition-all hover:shadow-lg hover:-translate-y-px active:translate-y-0 px-4 py-2.5"
                                    >
                                        <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                                        Export Excel (CSV)
                                    </a>

                                    @if($hasActiveFilters)
                                        <a href="{{ route('skkm.kaprodi.index') }}?tab=students" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="px-6 py-3 border-b border-indigo-100/70 bg-indigo-50/60">
                        @if ($students->total() > 0)
                            <p class="text-xs font-semibold text-indigo-700">
                                Data yang terlihat sekarang: {{ $students->count() }} data, dari total {{ $students->total() }} data.
                            </p>
                            <p class="mt-1 text-xs text-indigo-600">
                                Di halaman ini menampilkan data nomor {{ $students->firstItem() }} sampai {{ $students->lastItem() }}.
                            </p>
                        @else
                            <p class="text-xs font-semibold text-indigo-700">Belum ada data mahasiswa untuk ditampilkan.</p>
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                                <tr>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Dosen PA</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Semester Aktif</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 1-2</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 3-4</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 5-6</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 7-8</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Total</th>
                                    <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-indigo-100/60">
                                @forelse($students as $student)
                                    @php
                                        $progress = $student->skkmProgress;
                                        $p12 = (int) ($progress->poin_smt_1_2 ?? 0);
                                        $p34 = (int) ($progress->poin_smt_3_4 ?? 0);
                                        $p56 = (int) ($progress->poin_smt_5_6 ?? 0);
                                        $p78 = (int) ($progress->poin_smt_7_8 ?? 0);
                                        $total = (int) ($progress->total_poin ?? ($p12 + $p34 + $p56 + $p78));
                                        $semesterAktif = $progress->semester_aktif ?? $student->semester;
                                        $status = $progress->status_yudisium ?? null;
                                        $badgeClass = match ($status) {
                                            'memenuhi' => 'bg-emerald-100 text-emerald-700',
                                            'belum_memenuhi' => 'bg-rose-100 text-rose-700',
                                            'dalam_proses' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-slate-100 text-slate-500',
                                        };
                                        $badgeLabel = match ($status) {
                                            'memenuhi' => 'Memenuhi',
                                            'belum_memenuhi' => 'Belum memenuhi',
                                            'dalam_proses' => 'Dalam proses',
                                            default => 'Belum ada data',
                                        };
                                    @endphp
                                    <tr class="hover:bg-indigo-50/40 transition-colors duration-200">
                                        <td class="px-6 py-5">
                                            <div class="font-semibold text-slate-800">{{ $student->name }}</div>
                                            <div class="text-xs text-slate-500 mt-1">{{ $student->identifier ?? 'Identifier tidak tersedia' }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="font-semibold text-slate-800">{{ $student->lecturer?->name ?? '-' }}</div>
                                            <div class="text-xs text-slate-500 mt-1">{{ $student->lecturer?->identifier ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="font-semibold text-slate-800">{{ $student->programStudi?->nama ?? '-' }}</div>
                                            <div class="text-xs text-slate-500 mt-1">{{ $student->programStudi?->fakultas?->nama ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="text-sm font-semibold text-slate-700">{{ $semesterAktif ?? '-' }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-100 text-indigo-700 rounded-lg">{{ $p12 }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-100 text-indigo-700 rounded-lg">{{ $p34 }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-100 text-indigo-700 rounded-lg">{{ $p56 }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-100 text-indigo-700 rounded-lg">{{ $p78 }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold bg-slate-900 text-white rounded-lg">{{ $total }}</span>
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-12 text-center">
                                            <div class="inline-flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                                <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7"></path>
                                                </svg>
                                            </div>
                                            <p class="mt-3 text-sm font-semibold text-slate-700">Tidak ada mahasiswa untuk prodi ini.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-indigo-100/70 bg-white/70">
                        {{ $students->links() }}
                    </div>
                </div>
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('kaprodiMahasiswaFilterForm');
                const searchInput = document.getElementById('kaprodi-filter-search');
                const semesterSelect = document.getElementById('kaprodi-filter-semester');
                const statusSelect = document.getElementById('kaprodi-filter-status');
                const lecturerSelect = document.getElementById('kaprodi-filter-lecturer');
                const perPageSelect = document.getElementById('kaprodi-filter-per-page');

                if (!filterForm) {
                    return;
                }

                const debounce = (callback, delay = 450) => {
                    let timeoutId;
                    return (...args) => {
                        window.clearTimeout(timeoutId);
                        timeoutId = window.setTimeout(() => callback(...args), delay);
                    };
                };

                const submitFilter = () => filterForm.requestSubmit();
                const debouncedSubmit = debounce(submitFilter, 450);

                if (searchInput) {
                    searchInput.addEventListener('input', debouncedSubmit);
                    searchInput.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            submitFilter();
                        }
                    });
                }

                [semesterSelect, statusSelect, lecturerSelect, perPageSelect].forEach((selectElement) => {
                    if (!selectElement) {
                        return;
                    }

                    selectElement.addEventListener('change', submitFilter);
                });
            });
        </script>
    @endpush
</x-app-layout>
