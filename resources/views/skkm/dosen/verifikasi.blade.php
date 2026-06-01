<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Dosen PA
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Verifikasi Pengajuan SKKM') }}
            </h2>
        </div>
    </x-slot>

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid gap-6" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr));">
                <div class="rounded-3xl bg-gradient-to-br from-amber-500 to-orange-500 p-6 text-white shadow-[0_14px_35px_rgb(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Menunggu Verifikasi</p>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="text-4xl font-extrabold">{{ $pendingSubmissions->count() }}</span>
                        <span class="text-lg font-medium text-amber-100">Pengajuan Baru</span>
                    </div>
                </div>

                <div class="rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-500 p-6 text-white shadow-[0_14px_35px_rgb(16,185,129,0.30)]">
                    <p class="text-sm font-semibold text-emerald-100">Selesai Diverifikasi</p>
                    <div class="mt-2 flex items-center space-x-3">
                        <span class="text-4xl font-extrabold">{{ $verifiedSubmissions->count() }}</span>
                        <span class="text-lg font-medium text-emerald-100">Pengajuan (Terbaru)</span>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 flex items-center rounded-2xl border border-emerald-200 bg-emerald-100/90 p-4 text-emerald-700 shadow-sm">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="overflow-hidden rounded-3xl border border-indigo-100/80 bg-white/90 shadow-[0_8px_30px_rgb(37,99,235,0.12)] backdrop-blur-sm">
                <div class="flex items-center justify-between border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70 px-8 py-6">
                    <div class="flex items-center">
                        <div class="mr-3 rounded-xl bg-indigo-100 p-2">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Antrean Verifikasi (Pending)</h3>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="bg-indigo-50/70 text-xs uppercase text-indigo-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kegiatan & Tanggal</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold tracking-wider">Poin</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold tracking-wider">Bukti Fisik</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold tracking-wider">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse($pendingSubmissions as $sub)
                                @php
                                    $proofUrl = Storage::url($sub->file_bukti);
                                    $proofExtension = strtolower(pathinfo($sub->file_bukti, PATHINFO_EXTENSION));
                                    $isImageProof = in_array($proofExtension, ['jpg', 'jpeg', 'png'], true);
                                    $isPdfProof = $proofExtension === 'pdf';
                                    $unsurLabel = ucwords(str_replace('_', ' ', (string) ($sub->pointRule?->unsur ?? '-')));
                                    $subUnsurLabel = $sub->pointRule?->sub_unsur ?? '-';
                                    $jenisItemLabel = $sub->pointRule?->jenis_item ?? '-';
                                    $tingkatLabel = $sub->pointRule?->tingkat ? ucfirst((string) $sub->pointRule->tingkat) : '-';
                                    $tanggalKegiatanLabel = optional($sub->tanggal_kegiatan)->format('d M Y') ?? '-';
                                    $tanggalAjukanLabel = optional($sub->created_at)->format('d M Y, H:i') ?? '-';
                                    $penyelenggaraLabel = $sub->penyelenggara ?? '-';
                                @endphp
                                <tr class="transition-colors duration-200 hover:bg-indigo-50/40">
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-slate-800">{{ $sub->mahasiswa->name }}</div>
                                        <div class="mt-1 text-xs text-slate-400">{{ $sub->mahasiswa->identifier ?? 'NIM tidak tersedia' }} - Smt {{ $sub->semester_input }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-semibold text-slate-800">{{ $sub->nama_kegiatan }}</div>
                                        <div class="mt-1 text-xs text-slate-500">Tanggal: {{ $tanggalKegiatanLabel }} - Pengajuan: {{ $tanggalAjukanLabel }}</div>
                                        <div class="mt-1 text-xs text-slate-500">Penyelenggara: {{ $penyelenggaraLabel }}</div>
                                        <div class="mt-1 text-xs capitalize text-slate-500">{{ $unsurLabel }} - {{ $jenisItemLabel }} @if($tingkatLabel !== '-') ({{ $tingkatLabel }}) @endif</div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-2.5 py-1 text-sm font-bold text-indigo-600">
                                            {{ $sub->poin_otomatis }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="mx-auto h-24 w-36 overflow-hidden rounded-xl border border-slate-200 bg-slate-100 shadow-sm">
                                            @if ($isImageProof)
                                                <a href="{{ $proofUrl }}" target="_blank" class="block h-full w-full" title="Buka bukti ukuran penuh">
                                                    <img src="{{ $proofUrl }}" alt="Preview bukti {{ $sub->nama_kegiatan }}" class="h-full w-full object-cover">
                                                </a>
                                            @elseif ($isPdfProof)
                                                <a href="{{ $proofUrl }}" target="_blank" class="block h-full w-full bg-white" title="Buka bukti PDF ukuran penuh">
                                                    <iframe
                                                        src="{{ $proofUrl }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                                        class="h-full w-full pointer-events-none"
                                                        loading="lazy"
                                                        title="Preview PDF {{ $sub->nama_kegiatan }}"
                                                    ></iframe>
                                                </a>
                                            @else
                                                <a href="{{ $proofUrl }}" target="_blank" class="inline-flex h-full w-full items-center justify-center px-3 text-xs font-medium text-slate-700 hover:bg-slate-50">
                                                    Lihat Bukti
                                                </a>
                                            @endif
                                        </div>
                                        <p class="mt-2 text-[11px] text-slate-400">Klik preview untuk buka penuh</p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <button
                                            type="button"
                                            onclick="openVerifyModal(this)"
                                            data-submission-id="{{ $sub->id }}"
                                            data-mahasiswa="{{ $sub->mahasiswa->name }}"
                                            data-kegiatan="{{ $sub->nama_kegiatan }}"
                                            data-poin="{{ $sub->poin_otomatis }}"
                                            data-semester="{{ $sub->semester_input }}"
                                            data-penyelenggara="{{ $penyelenggaraLabel }}"
                                            data-tanggal-kegiatan="{{ $tanggalKegiatanLabel }}"
                                            data-tanggal-ajukan="{{ $tanggalAjukanLabel }}"
                                            data-unsur="{{ $unsurLabel }}"
                                            data-sub-unsur="{{ $subUnsurLabel }}"
                                            data-jenis-item="{{ $jenisItemLabel }}"
                                            data-tingkat="{{ $tingkatLabel }}"
                                            class="inline-flex items-center rounded-lg border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            Verifikasi
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                                            <svg class="h-8 w-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-base font-semibold text-slate-800">Semua Pengajuan Telah Diverifikasi</h3>
                                        <p class="mt-1 text-sm text-slate-500">Tidak ada pengajuan SKKM baru yang menunggu tinjauan Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-emerald-100/80 bg-white/90 shadow-[0_8px_30px_rgb(16,185,129,0.14)] backdrop-blur-sm">
                <div class="border-b border-emerald-100 bg-emerald-50/70 px-8 py-6">
                    <h3 class="text-lg font-bold text-slate-800">Riwayat Verifikasi (20 Terakhir)</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-500">
                        <thead class="bg-emerald-50/80 text-xs uppercase text-emerald-700">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Kegiatan</th>
                                <th scope="col" class="px-6 py-4 text-center font-semibold tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-100/60">
                            @forelse($verifiedSubmissions as $sub)
                                <tr class="transition-colors duration-200 hover:bg-emerald-50/30">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $sub->mahasiswa->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-slate-800">{{ $sub->nama_kegiatan }}</div>
                                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($sub->verified_at)->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($sub->status_verifikasi == 'disetujui')
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-600 ring-1 ring-inset ring-emerald-500/20">
                                                Disetujui (+{{ $sub->poin_otomatis }})
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-medium text-rose-600 ring-1 ring-inset ring-rose-500/20">
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs italic text-slate-500">{{ $sub->catatan_dosen ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada riwayat verifikasi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="verifyModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeVerifyModal()"></div>

            <div class="relative w-full overflow-hidden rounded-3xl border border-slate-100 bg-white text-left shadow-2xl transition-all sm:my-8 sm:max-w-2xl">
                <form id="verifyForm" method="POST" action="">
                    @csrf
                    <div class="px-4 pb-4 pt-5 sm:px-7 sm:pb-6 sm:pt-6">
                        <div class="flex items-start gap-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-100">
                                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>

                            <div class="w-full">
                                <h3 class="text-xl font-bold leading-7 text-slate-900 sm:text-2xl" id="modal-title">
                                    Verifikasi Pengajuan
                                </h3>

                                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50/80 px-4 py-3">
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mahasiswa</p>
                                            <p class="mt-1 text-2xl font-bold leading-8 text-slate-800" id="modal-mhs-name">-</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kegiatan</p>
                                            <p class="mt-1 text-2xl font-bold leading-8 text-slate-800" id="modal-kegiatan-name">-</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-2xl border border-indigo-100 bg-indigo-50/40 px-4 py-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Detail Pengajuan</p>
                                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Poin Diperoleh</p>
                                            <p class="mt-1 text-sm font-bold text-indigo-700" id="modal-poin">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Semester Input</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-semester">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Tanggal Kegiatan</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-tanggal-kegiatan">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Tanggal Pengajuan</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-tanggal-ajukan">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 sm:col-span-2">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Penyelenggara</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-penyelenggara">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Kategori SKKM</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-kategori">-</p>
                                        </div>
                                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2.5">
                                            <p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Jenis Item / Tingkat</p>
                                            <p class="mt-1 text-sm font-bold text-slate-800" id="modal-jenis-tingkat">-</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <label class="mb-2.5 block text-sm font-semibold text-slate-700">Keputusan Verifikasi</label>
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <label class="relative block cursor-pointer transition-transform duration-200 hover:-translate-y-0.5">
                                            <input type="radio" name="status_verifikasi" value="disetujui" class="peer sr-only" required onchange="toggleCatatan()">

                                            <div class="relative flex min-h-[98px] items-center rounded-2xl border-2 border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 peer-focus-visible:ring-2 peer-focus-visible:ring-emerald-400/50 peer-focus-visible:ring-offset-1 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-md peer-checked:shadow-emerald-100/70 peer-checked:[&_.icon-box]:border-emerald-500 peer-checked:[&_.icon-box]:bg-emerald-500 peer-checked:[&_.icon-svg]:text-white peer-checked:[&_.title-text]:text-emerald-800 peer-checked:[&_.desc-text]:text-emerald-600 hover:border-emerald-300">
                                                <div class="icon-box flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-slate-200 bg-slate-50 transition-all duration-200">
                                                    <svg class="icon-svg h-6 w-6 text-slate-300 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>

                                                <div class="ml-4">
                                                    <span class="title-text block text-2xl font-bold leading-8 text-slate-700 transition-colors duration-200">Setujui Poin</span>
                                                    <span class="desc-text mt-1 block text-sm font-semibold text-slate-400 transition-colors duration-200">Dokumen valid</span>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative block cursor-pointer transition-transform duration-200 hover:-translate-y-0.5">
                                            <input type="radio" name="status_verifikasi" value="ditolak" class="peer sr-only" required onchange="toggleCatatan()">

                                            <div class="relative flex min-h-[98px] items-center rounded-2xl border-2 border-slate-200 bg-white p-4 shadow-sm transition-all duration-200 peer-focus-visible:ring-2 peer-focus-visible:ring-rose-400/50 peer-focus-visible:ring-offset-1 peer-checked:border-rose-500 peer-checked:bg-rose-50 peer-checked:shadow-md peer-checked:shadow-rose-100/70 peer-checked:[&_.icon-box]:border-rose-500 peer-checked:[&_.icon-box]:bg-rose-500 peer-checked:[&_.icon-svg]:text-white peer-checked:[&_.title-text]:text-rose-800 peer-checked:[&_.desc-text]:text-rose-600 hover:border-rose-300">
                                                <div class="icon-box flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-slate-200 bg-slate-50 transition-all duration-200">
                                                    <svg class="icon-svg h-6 w-6 text-slate-300 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </div>

                                                <div class="ml-4">
                                                    <span class="title-text block text-2xl font-bold leading-8 text-slate-700 transition-colors duration-200">Tolak (Revisi)</span>
                                                    <span class="desc-text mt-1 block text-sm font-semibold text-slate-400 transition-colors duration-200">Perlu perbaikan</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-5 hidden" id="catatanContainer">
                                    <label for="catatan_dosen" class="mb-2 block text-sm font-semibold text-slate-700">Catatan Penolakan (Wajib)</label>
                                    <textarea name="catatan_dosen" id="catatan_dosen" rows="3" class="block w-full rounded-xl border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm placeholder:text-slate-400 transition-colors focus:border-indigo-500 focus:ring-indigo-500" placeholder="Berikan alasan mengapa ditolak agar mahasiswa dapat memperbaiki..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-4 py-4 sm:flex-row sm:justify-end sm:px-7">
                        <button type="button" onclick="closeVerifyModal()" class="inline-flex w-full justify-center rounded-xl border border-slate-300 bg-white px-6 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex w-full justify-center rounded-xl border border-transparent bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                            Simpan Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openVerifyModal(trigger) {
            if (!trigger || !trigger.dataset) {
                return;
            }

            const data = trigger.dataset;
            const kategoriLabel = [data.unsur, data.subUnsur]
                .filter(function (value) {
                    return value && value !== '-';
                })
                .join(' / ') || '-';

            const jenisTingkatLabel = [data.jenisItem, data.tingkat]
                .filter(function (value) {
                    return value && value !== '-';
                })
                .join(' / ') || '-';

            document.getElementById('modal-mhs-name').innerText = data.mahasiswa || '-';
            document.getElementById('modal-kegiatan-name').innerText = data.kegiatan || '-';
            document.getElementById('modal-poin').innerText = data.poin ? ('+' + data.poin + ' poin') : '-';
            document.getElementById('modal-semester').innerText = data.semester ? ('Semester ' + data.semester) : '-';
            document.getElementById('modal-tanggal-kegiatan').innerText = data.tanggalKegiatan || '-';
            document.getElementById('modal-tanggal-ajukan').innerText = data.tanggalAjukan || '-';
            document.getElementById('modal-penyelenggara').innerText = data.penyelenggara || '-';
            document.getElementById('modal-kategori').innerText = kategoriLabel;
            document.getElementById('modal-jenis-tingkat').innerText = jenisTingkatLabel;
            document.getElementById('verifyForm').action = '/skkm/' + data.submissionId + '/verify';

            document.getElementById('verifyForm').reset();
            toggleCatatan();

            document.body.classList.add('overflow-hidden');
            document.getElementById('verifyModal').classList.remove('hidden');
        }

        function closeVerifyModal() {
            document.body.classList.remove('overflow-hidden');
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

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !document.getElementById('verifyModal').classList.contains('hidden')) {
                closeVerifyModal();
            }
        });
    </script>
</x-app-layout>
