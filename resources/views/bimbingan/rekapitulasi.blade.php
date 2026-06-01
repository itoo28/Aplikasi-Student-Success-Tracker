<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">Dashboard Rekapitulasi Bimbingan</h2>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Mahasiswa -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <p class="text-sm font-semibold text-slate-500">Jumlah Mahasiswa</p>
                    <h3 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalMahasiswa }}</h3>
                </div>
                
                <!-- Belum Bimbingan / Peringatan -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <p class="text-sm font-semibold text-slate-500">Mahasiswa Peringatan</p>
                    <h3 class="mt-2 text-3xl font-extrabold text-amber-600">{{ $belumBimbingan }}</h3>
                </div>
                
                <!-- Memenuhi Syarat -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <p class="text-sm font-semibold text-slate-500">Memenuhi Syarat Administrasi</p>
                    <h3 class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $memenuhiSyarat }}</h3>
                </div>
            </div>

            <!-- Tabel Kesamping Seperti Monitoring SKKM -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden mt-6">
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
    </div>
</x-app-layout>
