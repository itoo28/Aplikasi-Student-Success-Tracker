<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Verifikasi Pengajuan SKKM') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Stats/Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Menunggu Verifikasi -->
                <div class="rounded-3xl bg-white border border-amber-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl relative overflow-hidden flex flex-col justify-center transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-amber-50 opacity-50 blur-xl"></div>
                    <p class="text-amber-600 font-medium text-sm tracking-wider uppercase mb-1 relative z-10">Menunggu Verifikasi</p>
                    <div class="flex items-center space-x-3 text-amber-700 mt-2 relative z-10">
                        <span class="text-4xl font-extrabold">{{ $pendingSubmissions->count() }}</span>
                        <span class="text-lg font-medium text-amber-500">Pengajuan Baru</span>
                    </div>
                </div>

                <!-- Card Riwayat Terverifikasi -->
                <div class="rounded-3xl bg-white border border-emerald-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] backdrop-blur-xl relative overflow-hidden flex flex-col justify-center transition-all hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)]">
                    <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-emerald-50 opacity-50 blur-xl"></div>
                    <p class="text-emerald-600 font-medium text-sm tracking-wider uppercase mb-1 relative z-10">Selesai Diverifikasi</p>
                    <div class="flex items-center space-x-3 text-emerald-700 mt-2 relative z-10">
                        <span class="text-4xl font-extrabold">{{ $verifiedSubmissions->count() }}</span>
                        <span class="text-lg font-medium text-emerald-500">Pengajuan (Terbaru)</span>
                    </div>
                </div>
            </div>

            @if(session('success'))
            <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-md shadow-sm mb-6 flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <p>{{ session('success') }}</p>
            </div>
            @endif

            <!-- Tabel Pending Verifikasi -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <div class="flex items-center">
                        <div class="bg-amber-100 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Antrean Verifikasi (Pending)</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kegiatan & Tanggal</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Poin</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Bukti Fisik</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pendingSubmissions as $sub)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-800">{{ $sub->mahasiswa->name }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $sub->mahasiswa->identifier ?? 'NIM tidak tersedia' }} • Smt {{ $sub->semester_input }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $sub->nama_kegiatan }}</div>
                                    <div class="text-xs text-slate-500 mt-1 capitalize">{{ str_replace('_', ' ', $sub->pointRule->unsur) }} • {{ $sub->pointRule->jenis_item }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">
                                        {{ $sub->poin_otomatis }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ Storage::url($sub->file_bukti) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-slate-200 shadow-sm text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        <svg class="mr-1.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Lihat Bukti
                                    </a>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <button type="button" onclick="openVerifyModal('{{ $sub->id }}', '{{ $sub->mahasiswa->name }}', '{{ $sub->nama_kegiatan }}')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Verifikasi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <h3 class="text-base font-semibold text-slate-800">Semua Pengajuan Telah Diverifikasi</h3>
                                    <p class="text-slate-500 mt-1 text-sm">Tidak ada pengajuan SKKM baru yang menunggu tinjauan Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Riwayat Verifikasi -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Verifikasi (20 Terakhir)</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($verifiedSubmissions as $sub)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $sub->mahasiswa->name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-slate-800">{{ $sub->nama_kegiatan }}</div>
                                    <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($sub->verified_at)->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($sub->status_verifikasi == 'disetujui')
                                        <span class="inline-flex items-center bg-emerald-50 text-emerald-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-emerald-500/20">
                                            Disetujui (+{{ $sub->poin_otomatis }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-rose-50 text-rose-600 text-xs font-medium px-2.5 py-1 rounded-full ring-1 ring-inset ring-rose-500/20">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs text-slate-500 italic">{{ $sub->catatan_dosen ?? '-' }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500 text-sm">Belum ada riwayat verifikasi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div id="verifyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeVerifyModal()"></div>

            <!-- Modal panel -->
            <div class="relative bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full border border-slate-100">
                <form id="verifyForm" method="POST" action="">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900" id="modal-title">
                                    Verifikasi Pengajuan
                                </h3>
                                <div class="mt-2 mb-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                    <p class="text-xs text-slate-500 mb-1">Mahasiswa</p>
                                    <p class="text-sm font-semibold text-slate-800" id="modal-mhs-name">-</p>
                                    <p class="text-xs text-slate-500 mt-2 mb-1">Kegiatan</p>
                                    <p class="text-sm font-semibold text-slate-800" id="modal-kegiatan-name">-</p>
                                </div>
                                
                                <div class="mt-4">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Keputusan Verifikasi</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="relative block cursor-pointer hover:-translate-y-1 transition-transform duration-300">
                                            <input type="radio" name="status_verifikasi" value="disetujui" class="sr-only peer" required onchange="toggleCatatan()">
                                            
                                            <div class="relative flex items-center p-4 rounded-2xl border-2 border-slate-200 bg-white shadow-sm transition-all duration-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-lg peer-checked:shadow-emerald-100/50 peer-checked:[&_.icon-box]:border-emerald-500 peer-checked:[&_.icon-box]:bg-emerald-500 peer-checked:[&_.icon-svg]:text-white peer-checked:[&_.title-text]:text-emerald-800 peer-checked:[&_.desc-text]:text-emerald-600 hover:border-emerald-300">
                                                
                                                <div class="icon-box flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-slate-200 bg-slate-50 transition-all duration-300">
                                                    <svg class="icon-svg h-6 w-6 text-slate-300 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                                
                                                <div class="ml-4">
                                                    <span class="title-text block text-base font-bold text-slate-700 transition-colors duration-300">Setujui Poin</span>
                                                    <span class="desc-text block text-xs font-medium text-slate-400 mt-0.5 transition-colors duration-300">Dokumen valid</span>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative block cursor-pointer hover:-translate-y-1 transition-transform duration-300">
                                            <input type="radio" name="status_verifikasi" value="ditolak" class="sr-only peer" required onchange="toggleCatatan()">
                                            
                                            <div class="relative flex items-center p-4 rounded-2xl border-2 border-slate-200 bg-white shadow-sm transition-all duration-300 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:shadow-lg peer-checked:shadow-rose-100/50 peer-checked:[&_.icon-box]:border-rose-500 peer-checked:[&_.icon-box]:bg-rose-500 peer-checked:[&_.icon-svg]:text-white peer-checked:[&_.title-text]:text-rose-800 peer-checked:[&_.desc-text]:text-rose-600 hover:border-rose-300">
                                                
                                                <div class="icon-box flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-slate-200 bg-slate-50 transition-all duration-300">
                                                    <svg class="icon-svg h-6 w-6 text-slate-300 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>
                                                
                                                <div class="ml-4">
                                                    <span class="title-text block text-base font-bold text-slate-700 transition-colors duration-300">Tolak (Revisi)</span>
                                                    <span class="desc-text block text-xs font-medium text-slate-400 mt-0.5 transition-colors duration-300">Perlu perbaikan</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4 hidden" id="catatanContainer">
                                    <label for="catatan_dosen" class="block text-sm font-semibold text-slate-700 mb-2">Catatan Penolakan (Wajib)</label>
                                    <textarea name="catatan_dosen" id="catatan_dosen" rows="3" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Berikan alasan mengapa ditolak agar mahasiswa dapat memperbaiki..."></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-3xl border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-6 py-2.5 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Simpan Verifikasi
                        </button>
                        <button type="button" onclick="closeVerifyModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-6 py-2.5 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openVerifyModal(id, mhsName, kegiatanName) {
            document.getElementById('modal-mhs-name').innerText = mhsName;
            document.getElementById('modal-kegiatan-name').innerText = kegiatanName;
            document.getElementById('verifyForm').action = '/skkm/' + id + '/verify';
            
            // Reset form
            document.getElementById('verifyForm').reset();
            toggleCatatan();

            document.getElementById('verifyModal').classList.remove('hidden');
        }

        function closeVerifyModal() {
            document.getElementById('verifyModal').classList.add('hidden');
        }

        function toggleCatatan() {
            const radios = document.getElementsByName('status_verifikasi');
            const catatanContainer = document.getElementById('catatanContainer');
            const catatanInput = document.getElementById('catatan_dosen');
            
            let isRejected = false;
            for (let i = 0; i < radios.length; i++) {
                if (radios[i].checked && radios[i].value === 'ditolak') {
                    isRejected = true;
                    break;
                }
            }

            if (isRejected) {
                catatanContainer.classList.remove('hidden');
                catatanInput.required = true;
            } else {
                catatanContainer.classList.add('hidden');
                catatanInput.required = false;
            }
        }
    </script>
</x-app-layout>
