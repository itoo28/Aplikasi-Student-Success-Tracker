<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Dashboard Rekapitulasi Bimbingan') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Mahasiswa -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-slate-100/50 blur-xl"></div>
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Jumlah Mahasiswa</span>
                <h3 class="mt-3 text-3xl font-extrabold text-slate-800 z-10">{{ $totalMahasiswa }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Terdaftar bimbingan</p>
            </div>
            
            <!-- Belum Bimbingan / Peringatan -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-amber-50/50 blur-xl"></div>
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Mahasiswa Peringatan</span>
                <h3 class="mt-3 text-3xl font-extrabold text-amber-600 z-10">{{ $belumBimbingan }}</h3>
                <p class="text-[11px] text-amber-500 mt-1">Belum melakukan bimbingan</p>
            </div>
            
            <!-- Memenuhi Syarat -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-emerald-50/50 blur-xl"></div>
                <span class="text-slate-500 text-xs font-semibold uppercase tracking-wider">Memenuhi Syarat Administrasi</span>
                <h3 class="mt-3 text-3xl font-extrabold text-emerald-600 z-10">{{ $memenuhiSyarat }}</h3>
                <p class="text-[11px] text-emerald-500 mt-1">Telah melakukan bimbingan</p>
            </div>
        </div>

        <!-- Tabel Kesamping Seperti Monitoring SKKM -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Daftar Status Bimbingan Mahasiswa</h3>
                    <p class="text-sm text-slate-500 mt-1">Memantau frekuensi bimbingan akademik di semester aktif mahasiswa.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Semester Aktif</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Frekuensi Bimbingan</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status Kelayakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mahasiswaList as $mhs)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $mhs->name }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $mhs->identifier ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $mhs->programStudi?->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $mhs->programStudi?->fakultas?->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-sm font-semibold text-slate-700">{{ $mhs->semester ?? 1 }}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">
                                        {{ $mhs->bimbingan_semester_count }} kali
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($mhs->bimbingan_semester_count >= 1)
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-50 text-emerald-600">
                                            Memenuhi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-50 text-rose-600">
                                            Belum Memenuhi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 text-sm">
                                    Belum ada data mahasiswa yang terdaftar untuk direkapitulasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
