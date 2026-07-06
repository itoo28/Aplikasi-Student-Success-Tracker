<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Dosen PA
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                Data Mahasiswa Bimbingan
            </h2>
        </div>
    </x-slot>

    @php
        $hasActiveFilters = filled($search ?? '')
            || filled($selectedSemester ?? null)
            || filled($selectedStatusYudisium ?? '')
            || (int) ($perPage ?? 25) !== 25;
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Total Mahasiswa</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalStudents }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Sudah Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsFulfilled }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgba(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Belum Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsInProgress }}</h3>
                </div>
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                    <div class="flex flex-col gap-5">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Ringkasan Poin SKKM</h3>
                                <p class="text-sm text-slate-500 mt-1">Poin ditampilkan per blok semester (1-2, 3-4, 5-6, 7-8) dan total.</p>
                            </div>
                            <div class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-2 font-semibold text-indigo-700 w-fit">
                                <i data-lucide="users" class="mr-2 h-4 w-4"></i>
                                {{ $students->total() }} mahasiswa bimbingan
                            </div>
                        </div>

                        <form id="dosenMahasiswaFilterForm" method="GET" action="{{ route('skkm.monitoring.index') }}" class="space-y-3">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-5">
                                <div class="relative xl:col-span-2">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                        <i data-lucide="search" class="h-4 w-4"></i>
                                    </div>
                                    <input id="dosen-filter-search" type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama atau NIM..." class="block w-full rounded-xl border-slate-200 bg-white py-2.5 pl-9 pr-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <select id="dosen-filter-semester" name="semester" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Semester</option>
                                    @foreach($semesterOptions as $semesterOption)
                                        <option value="{{ $semesterOption }}" @selected((int) ($selectedSemester ?? 0) === (int) $semesterOption)>
                                            Semester {{ $semesterOption }}
                                        </option>
                                    @endforeach
                                </select>

                                <select id="dosen-filter-status" name="status_yudisium" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Semua Status</option>
                                    @foreach($statusYudisiumOptions as $statusValue => $statusLabel)
                                        <option value="{{ $statusValue }}" @selected(($selectedStatusYudisium ?? '') === $statusValue)>
                                            {{ $statusLabel }}
                                        </option>
                                    @endforeach
                                </select>

                                <select id="dosen-filter-per-page" name="per_page" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach ($perPageOptions as $option)
                                        <option value="{{ $option }}" @selected((int) $perPage === (int) $option)>
                                            {{ $option }} data
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-wrap items-center gap-2">
                                <a
                                    href="{{ route('skkm.monitoring.export', request()->query()) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl text-sm font-bold text-white bg-green-600 hover:bg-green-700 shadow-[0_6px_16px_rgba(22,163,74,0.25)] transition-all hover:shadow-lg hover:-translate-y-px active:translate-y-0 px-4 py-2.5"
                                >
                                    <i data-lucide="file-spreadsheet" class="h-4 w-4"></i>
                                    Export Excel (CSV)
                                </a>

                                @if($hasActiveFilters)
                                    <a href="{{ route('skkm.monitoring.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">
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
                        <p class="text-xs font-semibold text-indigo-700">Belum ada data mahasiswa bimbingan untuk ditampilkan.</p>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th scope="col" class="px-4 py-4 font-semibold tracking-wider text-center w-12">No.</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Semester Aktif</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 1-2</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 3-4</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 5-6</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Smt 7-8</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Total</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">WhatsApp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse($students as $loopIndex => $student)
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
                                        'memenuhi' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                        'belum_memenuhi' => 'bg-rose-50 text-rose-700 border border-rose-200',
                                        'dalam_proses' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                        default => 'bg-slate-50 text-slate-600 border border-slate-200',
                                    };
                                    $badgeLabel = match ($status) {
                                        'memenuhi' => 'Memenuhi',
                                        'belum_memenuhi' => 'Belum Memenuhi',
                                        'dalam_proses' => 'Dalam Proses',
                                        default => 'Belum Ada Data',
                                    };
                                    $waPhone = $student->whatsappPhoneNumber();
                                    $waMessage = 'Tolong lengkapi poin SKKM untuk persyaratan yudisium. Mohon segera melengkapi data dan bukti kegiatan yang masih kurang. Terima kasih.';
                                    $waLink = $waPhone ? 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMessage) : null;
                                @endphp
                                <tr class="hover:bg-indigo-50/40 transition-colors duration-200">
                                    <td class="px-4 py-5 text-center">
                                        <span class="text-sm font-semibold text-slate-500">{{ $students->firstItem() + $loopIndex }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-semibold text-slate-800">{{ $student->name }}</div>
                                        <div class="text-xs text-slate-400 mt-1">{{ $student->identifier ?? 'Identifier tidak tersedia' }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-semibold text-slate-800">{{ $student->programStudi?->nama ?? '-' }}</div>
                                        <div class="text-xs text-slate-400 mt-1">{{ $student->programStudi?->fakultas?->nama ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="text-sm font-semibold text-slate-700">{{ $semesterAktif ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">{{ $p12 }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">{{ $p34 }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">{{ $p56 }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">{{ $p78 }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center px-3 py-1 text-sm font-bold bg-slate-900 text-white rounded-lg">{{ $total }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-bold {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        @if($waLink)
                                            <a
                                                href="{{ $waLink }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                title="Kirim pesan WhatsApp ke {{ $student->name }}"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 hover:text-emerald-700 transition-all duration-150 shadow-sm hover:shadow-md"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                            </a>
                                        @else
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-50 text-slate-300" title="Nomor HP tidak tersedia">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-6 py-12 text-center text-slate-500 text-sm">
                                        Belum ada mahasiswa bimbingan.
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const filterForm = document.getElementById('dosenMahasiswaFilterForm');
                const searchInput = document.getElementById('dosen-filter-search');
                const semesterSelect = document.getElementById('dosen-filter-semester');
                const statusSelect = document.getElementById('dosen-filter-status');
                const perPageSelect = document.getElementById('dosen-filter-per-page');

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

                [semesterSelect, statusSelect, perPageSelect].forEach((selectElement) => {
                    if (!selectElement) {
                        return;
                    }

                    selectElement.addEventListener('change', submitFilter);
                });
            });
        </script>
    @endpush
</x-app-layout>
