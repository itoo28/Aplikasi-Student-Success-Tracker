<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                    Kemahasiswaan
                </span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                    Dashboard Monitoring SKKM
                </h2>
            </div>
            <a href="{{ route('skkm.point-rules.index') }}" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_8px_20px_rgb(124,58,237,0.28)] transition-all hover:from-violet-700 hover:to-indigo-700">
                <i data-lucide="list-plus" class="mr-2 h-4 w-4"></i>
                Kelola Poin SKKM
            </a>
        </div>
    </x-slot>

    @php
        $totalActiveStudents = (int) ($studentStats['total_active_students'] ?? 0);
        $studentsWithoutPoints = (int) ($studentStats['students_without_points'] ?? 0);
        $studentsInProgress = (int) ($studentStats['students_in_progress'] ?? 0);
        $studentsCompleted = (int) ($studentStats['students_completed'] ?? 0);
        $hasActiveFilters = filled($search)
            || filled($selectedStatus)
            || (int) $perPage !== 20;
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Mahasiswa Aktif</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalActiveStudents }}</h3>
                    <p class="text-xs text-indigo-100 mt-2">Total mahasiswa aktif di sistem</p>
                </div>
                <!-- Card krusial menggunakan gradient merah -->
                <div class="rounded-3xl p-6 bg-gradient-to-br from-rose-500 to-red-600 text-white shadow-[0_14px_35px_rgba(244,63,94,0.35)]">
                    <p class="text-sm font-semibold text-rose-100">Belum Ada Poin</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsWithoutPoints }}</h3>
                    <p class="text-xs text-rose-100 mt-2">Mahasiswa yang belum punya poin SKKM</p>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Mahasiswa Dalam Proses</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsInProgress }}</h3>
                    <p class="text-xs text-indigo-100 mt-2">Jumlah mahasiswa yang progresnya belum memenuhi target</p>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Sudah Penuh Poin</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsCompleted }}</h3>
                    <p class="text-xs text-indigo-100 mt-2">Mahasiswa yang sudah memenuhi target poin</p>
                </div>
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70 px-5 py-4">
                    <h3 class="text-sm font-bold text-slate-800">Panel Filter Pengajuan</h3>
                    <p class="mt-1 text-xs text-slate-500">Filter berjalan otomatis tanpa tombol terapkan.</p>
                </div>

                <form id="kemahasiswaanMonitoringForm" method="GET" action="{{ route('dashboard') }}" class="space-y-4 p-5">
                    <div class="grid grid-cols-1 gap-3 xl:grid-cols-4">
                        <div class="xl:col-span-2">
                            <flux:field>
                                <flux:label for="search">Cari Pengajuan</flux:label>
                                <flux:input
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Kegiatan, nama mahasiswa, NIM, jenis item"
                                />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label for="status">Status Verifikasi</flux:label>
                                <flux:select id="status" name="status">
                                    <flux:select.option value="">Semua Status</flux:select.option>
                                    @foreach ($statusOptions as $value => $label)
                                        <flux:select.option value="{{ $value }}" :selected="$selectedStatus === $value">
                                            {{ $label }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label for="per_page">Per Halaman</flux:label>
                                <flux:select id="per_page" name="per_page">
                                    @foreach ($perPageOptions as $option)
                                        <flux:select.option value="{{ $option }}" :selected="(int) $perPage === (int) $option">
                                            {{ $option }} data
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>
                    </div>

                    @if ($hasActiveFilters)
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Reset Semua</a>
                        </div>
                    @endif
                </form>

                @if ($hasActiveFilters)
                    <div class="border-t border-indigo-100 bg-indigo-50/40 px-5 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Filter Aktif:</span>
                            @if ($search)
                                <a href="{{ route('dashboard', request()->except('search')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-200 transition-colors">
                                    Cari: "{{ $search }}"
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif
                            @if ($selectedStatus)
                                <a href="{{ route('dashboard', request()->except('status')) }}" class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200 transition-colors">
                                    Status: {{ $statusOptions[$selectedStatus] ?? $selectedStatus }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif
                            @if ((int) $perPage !== 20)
                                <a href="{{ route('dashboard', request()->except('per_page')) }}" class="inline-flex items-center gap-1 rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700 hover:bg-cyan-200 transition-colors">
                                    Per Halaman: {{ $perPage }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                    <h3 class="text-lg font-bold text-slate-800">Pengajuan SKKM Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600 min-w-[1080px]">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse ($submissions as $submission)
                                @php
                                    $statusClass = match ($submission->status_verifikasi) {
                                        'disetujui' => 'bg-emerald-100 text-emerald-700',
                                        'ditolak' => 'bg-rose-100 text-rose-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                    $statusLabel = match ($submission->status_verifikasi) {
                                        'disetujui' => 'Disetujui',
                                        'ditolak' => 'Ditolak',
                                        default => 'Pending',
                                    };
                                @endphp
                                <tr class="hover:bg-indigo-50/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $submission->mahasiswa?->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $submission->mahasiswa?->identifier ?? '-' }} &middot; {{ $submission->mahasiswa?->programStudi?->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $submission->nama_kegiatan }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $submission->pointRule?->jenis_item ?? '-' }} &middot; {{ $submission->pointRule?->tingkat ?? '-' }}</div>
                                        @if ($submission->file_bukti)
                                            <a href="{{ Storage::url($submission->file_bukti) }}" target="_blank" class="inline-flex mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Bukti</a>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold">+{{ (int) $submission->poin_otomatis }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                                        <p class="text-xs text-slate-500 mt-2">Verifikator: {{ $submission->verifiedBy?->name ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-700">{{ $submission->created_at?->format('d M Y') ?? '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ $submission->created_at?->format('H:i') ?? '-' }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">Tidak ada pengajuan SKKM pada filter saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-indigo-100/70 bg-white/70">
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const monitoringForm = document.getElementById('kemahasiswaanMonitoringForm');
            const monitoringSearchInput = document.getElementById('search');
            const monitoringStatusInput = document.getElementById('status');
            const monitoringPerPageInput = document.getElementById('per_page');

            const debounce = (callback, delay = 450) => {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => callback(...args), delay);
                };
            };

            if (monitoringForm) {
                const submitFilters = () => monitoringForm.requestSubmit();

                if (monitoringStatusInput) {
                    monitoringStatusInput.addEventListener('change', submitFilters);
                }

                if (monitoringPerPageInput) {
                    monitoringPerPageInput.addEventListener('change', submitFilters);
                }

                if (monitoringSearchInput) {
                    const debouncedSubmit = debounce(submitFilters, 450);
                    monitoringSearchInput.addEventListener('input', debouncedSubmit);
                    monitoringSearchInput.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            submitFilters();
                        }
                    });
                }
            }
        </script>
    @endpush
</x-app-layout>
