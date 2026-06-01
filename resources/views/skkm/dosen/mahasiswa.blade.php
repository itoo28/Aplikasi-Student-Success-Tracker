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

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgb(79,70,229,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Total Mahasiswa</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $students->count() }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-[0_14px_35px_rgb(16,185,129,0.30)]">
                    <p class="text-sm font-semibold text-emerald-100">Sudah Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $students->filter(fn($student) => ($student->skkmProgress?->status_yudisium ?? null) === 'memenuhi')->count() }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgb(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Belum Memenuhi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $students->filter(fn($student) => in_array(($student->skkmProgress?->status_yudisium ?? null), ['dalam_proses', 'belum_memenuhi'], true))->count() }}</h3>
                </div>
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Ringkasan Poin SKKM</h3>
                        <p class="text-sm text-slate-500 mt-1">Poin ditampilkan per blok semester (1-2, 3-4, 5-6, 7-8) dan total.</p>
                    </div>
                    <form method="GET" action="{{ route('skkm.monitoring.index') }}" class="flex flex-col sm:flex-row items-center w-full md:w-auto gap-2 mt-4 md:mt-0">
                        <div class="relative w-full sm:w-64 md:w-80">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..." class="block w-full pl-10 pr-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm">
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white border border-transparent rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                Cari
                            </button>
                            @if(request('search'))
                                <a href="{{ route('skkm.monitoring.index') }}" class="flex-1 sm:flex-none inline-flex justify-center items-center px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
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
                                        'memenuhi' => 'bg-emerald-50 text-emerald-600',
                                        'belum_memenuhi' => 'bg-rose-50 text-rose-600',
                                        'dalam_proses' => 'bg-amber-50 text-amber-600',
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
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-slate-500 text-sm">
                                        Belum ada mahasiswa bimbingan.
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
