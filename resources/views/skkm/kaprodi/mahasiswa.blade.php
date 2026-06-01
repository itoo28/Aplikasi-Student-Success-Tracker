<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Kaprodi
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                Data Mahasiswa Prodi
            </h2>
        </div>
    </x-slot>

    @php
        $studentsFulfilled = $students->filter(fn($student) => ($student->skkmProgress?->status_yudisium ?? null) === 'memenuhi')->count();
        $studentsInProgress = $students->filter(fn($student) => in_array(($student->skkmProgress?->status_yudisium ?? null), ['dalam_proses', 'belum_memenuhi'], true))->count();
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgb(79,70,229,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Scope Prodi</p>
                    <h3 class="mt-2 text-lg font-extrabold">{{ $scopeLabel }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-[0_14px_35px_rgb(16,185,129,0.30)]">
                    <p class="text-sm font-semibold text-emerald-100">Sudah Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsFulfilled }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgb(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Dalam Proses</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $studentsInProgress }}</h3>
                </div>
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70 flex flex-col lg:flex-row gap-4 lg:items-center lg:justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Poin per Blok Semester</h3>
                        <p class="text-sm text-slate-500 mt-1">Poin ditampilkan per blok semester (1-2, 3-4, 5-6, 7-8) dan total.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <form method="GET" action="{{ route('skkm.kaprodi.mahasiswa.index') }}" class="flex flex-col sm:flex-row items-center gap-2">
                            <div class="relative w-full sm:w-72">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                                    <i data-lucide="search" class="h-4 w-4"></i>
                                </div>
                                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama, NIM, dosen PA" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div class="flex w-full sm:w-auto items-center gap-2">
                                <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-indigo-700 sm:w-auto">Cari</button>
                                @if($search)
                                    <a href="{{ route('skkm.kaprodi.mahasiswa.index') }}" class="inline-flex w-full justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50 sm:w-auto">Reset</a>
                                @endif
                            </div>
                        </form>
                        <div class="inline-flex items-center rounded-xl bg-indigo-50 px-4 py-2 font-semibold text-indigo-700">
                            <i data-lucide="users" class="mr-2 h-4 w-4"></i>
                            {{ $students->count() }} mahasiswa
                        </div>
                    </div>
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
            </div>
        </div>
    </div>
</x-app-layout>
