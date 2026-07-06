<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">Kemahasiswaan</span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">Poin SKKM</h2>
        </div>
    </x-slot>

    @php
        $hasActiveFilters = filled($search)
            || filled($selectedFakultas)
            || filled($selectedProgramStudi)
            || filled($selectedSemester)
            || filled($selectedStatusYudisium)
            || (int) $perPage !== 25;

        $selectedFakultasLabel = optional($fakultasOptions->firstWhere('id', (int) $selectedFakultas))->nama;
        $selectedProgramStudiLabel = null;
        if ($selectedProgramStudi) {
            $selectedProdi = $programStudiOptions->firstWhere('id', (int) $selectedProgramStudi);
            if ($selectedProdi) {
                $selectedProgramStudiLabel = trim($selectedProdi->jenjang . ' ' . $selectedProdi->nama);
            }
        }
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="rounded-3xl border border-violet-100/80 bg-white/95 shadow-[0_10px_30px_rgb(124,58,237,0.12)] backdrop-blur-sm overflow-hidden">
                <div class="border-b border-violet-100 bg-gradient-to-r from-violet-50 via-indigo-50 to-sky-50 px-5 py-4">
                    <h3 class="text-sm font-bold text-slate-800">Panel Filter & Navigasi Data</h3>
                    <p class="mt-1 text-xs text-slate-500">Gunakan pencarian dulu, lalu persempit dengan fakultas, prodi, semester, dan status yudisium.</p>
                </div>

                <form id="kemahasiswaanStudentFilterForm" method="GET" action="{{ route('skkm.kemahasiswaan.mahasiswa.index') }}" class="space-y-4 p-5">
                    <div class="grid grid-cols-1 gap-3 xl:grid-cols-4">
                        <div class="xl:col-span-3">
                            <flux:field>
                                <flux:label for="search">Cari Mahasiswa</flux:label>
                                <flux:input
                                    id="search"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Nama, NIM, dosen PA, prodi, atau fakultas"
                                />
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

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="grid grid-cols-1 gap-3 xl:grid-cols-4">
                            <div>
                                <flux:field>
                                    <flux:label for="fakultas_id">Filter Fakultas</flux:label>
                                    <flux:select id="fakultas_id" name="fakultas_id">
                                        <flux:select.option value="">Semua Fakultas</flux:select.option>
                                        @foreach ($fakultasOptions as $fakultas)
                                            <flux:select.option value="{{ $fakultas->id }}" :selected="(int) $selectedFakultas === (int) $fakultas->id">
                                                {{ $fakultas->nama }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label for="program_studi_id">Filter Program Studi</flux:label>
                                    <flux:select id="program_studi_id" name="program_studi_id">
                                        <flux:select.option value="">Semua Program Studi</flux:select.option>
                                        @foreach ($programStudiOptions as $programStudi)
                                            <flux:select.option value="{{ $programStudi->id }}" :selected="(int) $selectedProgramStudi === (int) $programStudi->id">
                                                {{ $programStudi->jenjang }} {{ $programStudi->nama }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label for="semester">Filter Semester</flux:label>
                                    <flux:select id="semester" name="semester">
                                        <flux:select.option value="">Semua Semester</flux:select.option>
                                        @foreach ($semesterOptions as $semesterOption)
                                            <flux:select.option value="{{ $semesterOption }}" :selected="(int) $selectedSemester === (int) $semesterOption">
                                                Semester {{ $semesterOption }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>

                            <div>
                                <flux:field>
                                    <flux:label for="status_yudisium">Filter Status Yudisium</flux:label>
                                    <flux:select id="status_yudisium" name="status_yudisium">
                                        <flux:select.option value="">Semua Status</flux:select.option>
                                        @foreach ($statusYudisiumOptions as $value => $label)
                                            <flux:select.option value="{{ $value }}" :selected="$selectedStatusYudisium === $value">
                                                {{ $label }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a
                            href="{{ route('skkm.kemahasiswaan.mahasiswa.export', request()->query()) }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 shadow-[0_6px_16px_rgba(22,163,74,0.25)] transition-all hover:shadow-lg hover:-translate-y-px active:translate-y-0 px-4 py-2.5"
                        >
                            <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                            Export Excel (CSV)
                        </a>

                        @if ($hasActiveFilters)
                            <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Reset Semua</a>
                        @endif
                    </div>
                </form>

                @if ($hasActiveFilters)
                    <div class="border-t border-violet-100 bg-violet-50/40 px-5 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-violet-700">Filter Aktif:</span>

                            @if ($search)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except('search')) }}" class="inline-flex items-center gap-1 rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 hover:bg-violet-200 transition-colors">
                                    Cari: "{{ $search }}"
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedFakultas && $selectedFakultasLabel)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except(['fakultas_id', 'program_studi_id'])) }}" class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200 transition-colors">
                                    Fakultas: {{ $selectedFakultasLabel }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedProgramStudi && $selectedProgramStudiLabel)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except('program_studi_id')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-200 transition-colors">
                                    Prodi: {{ $selectedProgramStudiLabel }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedSemester)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except('semester')) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition-colors">
                                    Semester: {{ $selectedSemester }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedStatusYudisium)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except('status_yudisium')) }}" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition-colors">
                                    Status: {{ $statusYudisiumOptions[$selectedStatusYudisium] ?? $selectedStatusYudisium }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ((int) $perPage !== 25)
                                <a href="{{ route('skkm.kemahasiswaan.mahasiswa.index', request()->except('per_page')) }}" class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-200 transition-colors">
                                    Per Halaman: {{ $perPage }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-emerald-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(16,185,129,0.14)] overflow-hidden">
                <div class="px-6 py-3 border-b border-emerald-100/70 bg-emerald-50/60">
                    @if ($students->total() > 0)
                        <p class="text-xs font-semibold text-emerald-700">
                            Data yang terlihat sekarang: {{ $students->count() }} data, dari total {{ $students->total() }} data.
                        </p>
                        <p class="mt-1 text-xs text-emerald-600">
                            Di halaman ini menampilkan data nomor {{ $students->firstItem() }} sampai {{ $students->lastItem() }}.
                        </p>
                    @else
                        <p class="text-xs font-semibold text-emerald-700">Belum ada data mahasiswa untuk ditampilkan.</p>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1280px] text-sm text-left text-slate-600">
                        <thead class="sticky top-0 z-10 text-xs text-emerald-700 uppercase bg-emerald-50/90 backdrop-blur">
                            <tr>
                                <th class="w-[72px] px-6 py-4 font-semibold tracking-wider">No</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Dosen PA</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Fakultas & Prodi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Semester</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Smt 1-2</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Smt 3-4</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Smt 5-6</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Smt 7-8</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Total</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-100/60">
                            @forelse ($students as $student)
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
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                    $badgeLabel = match ($status) {
                                        'memenuhi' => 'Memenuhi',
                                        'belum_memenuhi' => 'Belum memenuhi',
                                        'dalam_proses' => 'Dalam proses',
                                        default => 'Belum ada progres',
                                    };
                                @endphp
                                <tr class="hover:bg-emerald-50/40 transition-colors">
                                    <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                        {{ $students->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $student->name }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $student->identifier ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $student->lecturer?->name ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $student->lecturer?->identifier ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $student->programStudi?->fakultas?->nama ?? '-' }}</div>
                                        <div class="text-xs text-slate-500 mt-1">{{ $student->programStudi?->jenjang ?? '' }} {{ $student->programStudi?->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center font-semibold text-slate-700">{{ $semesterAktif ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $p12 }}</span></td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $p34 }}</span></td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $p56 }}</span></td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-700">{{ $p78 }}</span></td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-lg bg-slate-900 px-3 py-1 text-xs font-bold text-white">{{ $total }}</span></td>
                                    <td class="px-6 py-4 text-center"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">{{ $badgeLabel }}</span></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-10 text-center text-slate-500">Tidak ada data mahasiswa untuk filter saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 border-t border-emerald-100/70 bg-white/70">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const studentFilterForm = document.getElementById('kemahasiswaanStudentFilterForm');
            const studentSearchInput = document.getElementById('search');
            const fakultasInput = document.getElementById('fakultas_id');
            const programStudiInput = document.getElementById('program_studi_id');
            const semesterInput = document.getElementById('semester');
            const statusYudisiumInput = document.getElementById('status_yudisium');
            const perPageInput = document.getElementById('per_page');

            const debounce = (callback, delay = 450) => {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => callback(...args), delay);
                };
            };

            if (studentFilterForm) {
                const submitFilters = () => studentFilterForm.requestSubmit();

                if (fakultasInput) {
                    fakultasInput.addEventListener('change', submitFilters);
                }

                if (programStudiInput) {
                    programStudiInput.addEventListener('change', submitFilters);
                }

                if (semesterInput) {
                    semesterInput.addEventListener('change', submitFilters);
                }

                if (statusYudisiumInput) {
                    statusYudisiumInput.addEventListener('change', submitFilters);
                }

                if (perPageInput) {
                    perPageInput.addEventListener('change', submitFilters);
                }

                if (studentSearchInput) {
                    const debouncedSubmit = debounce(submitFilters, 450);
                    studentSearchInput.addEventListener('input', debouncedSubmit);
                    studentSearchInput.addEventListener('keydown', (event) => {
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
