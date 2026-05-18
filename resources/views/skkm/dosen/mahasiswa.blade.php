<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            Data Mahasiswa Bimbingan
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Ringkasan Poin SKKM</h3>
                    <p class="text-sm text-slate-500 mt-1">Poin ditampilkan per blok semester (1-2, 3-4, 5-6, 7-8) dan total.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
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
                        <tbody class="divide-y divide-slate-100">
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
                                <tr class="hover:bg-slate-50/80 transition-colors duration-200">
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
