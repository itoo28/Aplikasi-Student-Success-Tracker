<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Monitoring SKKM Kemahasiswaan') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <h3 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $submissions->total() }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Mahasiswa Peringatan</p>
                <h3 class="mt-2 text-3xl font-extrabold text-amber-600">{{ $warningStudents->count() }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Memenuhi Syarat Yudisium</p>
                <h3 class="mt-2 text-3xl font-extrabold text-emerald-600">{{ $studentRecap->where('status_yudisium', 'memenuhi')->count() }}</h3>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan SKKM</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-center">Poin</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status & Verifikator</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($submissions as $submission)
                            <tr class="hover:bg-slate-50/70 transition-colors align-top">
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $submission->mahasiswa?->name }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $submission->mahasiswa?->identifier }} · {{ $submission->mahasiswa?->programStudi?->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $submission->nama_kegiatan }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $submission->pointRule?->jenis_item }} · {{ $submission->pointRule?->tingkat ?? '-' }}</div>
                                    <a href="{{ Storage::url($submission->file_bukti) }}" target="_blank" class="inline-flex mt-2 text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Bukti</a>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 font-bold">+{{ $submission->poin_otomatis }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    @if($submission->status_verifikasi === 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700">Disetujui</span>
                                    @elseif($submission->status_verifikasi === 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700">Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-700">Pending</span>
                                    @endif
                                    <p class="text-xs text-slate-500 mt-2">Oleh: {{ $submission->verifiedBy?->name ?? '-' }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-slate-500">Tidak ada pengajuan SKKM saat ini.</td>
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
</x-app-layout>
