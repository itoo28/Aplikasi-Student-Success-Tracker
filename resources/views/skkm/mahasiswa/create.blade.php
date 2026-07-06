<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('skkm.index') }}" class="mr-4 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Pengajuan Poin SKKM Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 sm:py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden backdrop-blur-xl">
                
                <div class="bg-indigo-600 px-6 sm:px-8 py-4 sm:py-6">
                    <h3 class="text-lg sm:text-xl font-bold text-white">Form Pengajuan Kegiatan</h3>
                    <p class="text-indigo-200 mt-1 text-xs sm:text-sm">Pilih kategori kegiatan secara bertahap dan unggah bukti fisik yang sesuai.</p>
                </div>

                <div class="p-5 sm:p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-rose-800 font-medium">Terdapat kesalahan pengisian form:</h3>
                            </div>
                            <ul class="mt-2 list-disc list-inside text-sm text-rose-600 ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('skkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        {{-- Hidden input for the actual point_rule_id --}}
                        <input type="hidden" name="point_rule_id" id="point_rule_id" value="{{ old('point_rule_id') }}">

                        {{-- Step Indicator --}}
                        <div class="flex items-center justify-between mb-2 px-1">
                            <div class="flex items-center space-x-2 text-xs font-semibold">
                                <span id="step-indicator-1" class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-600 text-white transition-all">1</span>
                                <span class="text-slate-400">›</span>
                                <span id="step-indicator-2" class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-500 transition-all">2</span>
                                <span class="text-slate-400">›</span>
                                <span id="step-indicator-3" class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-500 transition-all">3</span>
                                <span class="text-slate-400">›</span>
                                <span id="step-indicator-4" class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-200 text-slate-500 transition-all">4</span>
                            </div>
                            <span id="step-label" class="text-xs font-medium text-slate-500">Langkah 1: Pilih Unsur</span>
                        </div>

                        {{-- Cascading Dropdowns --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- 1. Unsur --}}
                            <div>
                                <label for="select_unsur" class="block text-sm font-semibold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-100 text-indigo-700 text-[10px] font-bold mr-1.5">1</span>
                                    Unsur SKKM
                                </label>
                                <select id="select_unsur" required class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                                    <option value="" disabled selected>-- Pilih Unsur --</option>
                                </select>
                            </div>

                            {{-- 2. Jenis Kegiatan (sub_unsur + jenis_item) --}}
                            <div>
                                <label for="select_jenis" class="block text-sm font-semibold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold mr-1.5" id="jenis-badge">2</span>
                                    Jenis Kegiatan
                                </label>
                                <select id="select_jenis" disabled class="block w-full rounded-xl border-slate-200 bg-slate-100 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                                    <option value="" disabled selected>-- Pilih Unsur terlebih dahulu --</option>
                                </select>
                            </div>

                            {{-- 3. Tingkat --}}
                            <div>
                                <label for="select_tingkat" class="block text-sm font-semibold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold mr-1.5" id="tingkat-badge">3</span>
                                    Tingkat
                                </label>
                                <select id="select_tingkat" disabled class="block w-full rounded-xl border-slate-200 bg-slate-100 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                                    <option value="" disabled selected>-- Pilih Jenis Kegiatan terlebih dahulu --</option>
                                </select>
                            </div>

                            {{-- 4. Peranan --}}
                            <div>
                                <label for="select_peranan" class="block text-sm font-semibold text-slate-700 mb-2">
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold mr-1.5" id="peranan-badge">4</span>
                                    Peranan / Kedudukan
                                </label>
                                <select id="select_peranan" disabled class="block w-full rounded-xl border-slate-200 bg-slate-100 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                                    <option value="" disabled selected>-- Pilih Tingkat terlebih dahulu --</option>
                                </select>
                            </div>
                        </div>

                        {{-- Poin Preview --}}
                        <div id="poin-preview" class="hidden rounded-2xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 p-5 transition-all">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">Poin Otomatis</p>
                                    <p class="text-sm text-slate-600 mt-1" id="poin-description">-</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span id="poin-value" class="text-4xl font-extrabold text-indigo-600">0</span>
                                    <span class="text-sm font-semibold text-indigo-400">Poin</span>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center text-xs text-slate-500">
                                <svg class="w-4 h-4 mr-1 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Bukti yang diperlukan: <span id="poin-bukti" class="font-medium ml-1">-</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Kegiatan --}}
                            <div>
                                <label for="nama_kegiatan" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" id="nama_kegiatan" required value="{{ old('nama_kegiatan') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: Lomba Karya Tulis Ilmiah Nasional">
                            </div>

                            {{-- Penyelenggara --}}
                            <div>
                                <label for="penyelenggara" class="block text-sm font-semibold text-slate-700 mb-2">Penyelenggara</label>
                                <input type="text" name="penyelenggara" id="penyelenggara" required value="{{ old('penyelenggara') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: Kementerian Pendidikan">
                            </div>

                            {{-- Tanggal Kegiatan --}}
                            <div>
                                <label for="tanggal_kegiatan" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" required value="{{ old('tanggal_kegiatan') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                            </div>

                            {{-- Semester Input --}}
                            <div>
                                <label for="semester_input" class="block text-sm font-semibold text-slate-700 mb-2">Semester Saat Kegiatan</label>
                                <input type="number" name="semester_input" id="semester_input" min="1" max="8" required value="{{ old('semester_input', auth()->user()->semester) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: 3">
                            </div>
                        </div>

                        {{-- File Upload --}}
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Fisik (Sertifikat / Surat Tugas)</label>
                            <input type="hidden" name="uploaded_file_path" id="uploaded_file_path">
                            
                            {{-- Dropzone Area --}}
                            <div id="upload-dropzone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-300 bg-slate-50 group relative cursor-pointer">
                                <div class="space-y-2 text-center pointer-events-none">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <span class="font-medium text-indigo-600 group-hover:text-indigo-500">Pilih file atau seret ke sini</span>
                                    </div>
                                    <p class="text-xs text-slate-500">PDF, JPG, PNG hingga 5MB</p>
                                </div>
                                <input id="file_bukti" name="file_bukti" type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required accept=".pdf,.jpg,.jpeg,.png">
                            </div>

                            {{-- Progress Bar Container --}}
                            <div id="upload-progress-container" class="hidden mt-3 p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                <div class="flex justify-between items-center mb-1 text-xs font-semibold">
                                    <span class="text-slate-600 flex items-center gap-1.5">
                                        <svg class="animate-spin h-3.5 w-3.5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Mengunggah file...
                                    </span>
                                    <span id="upload-progress-percent" class="text-indigo-600">0%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div id="upload-progress-bar" class="bg-indigo-600 h-2 rounded-full transition-all duration-150" style="width: 0%"></div>
                                </div>
                            </div>

                            {{-- Preview Area --}}
                            <div id="upload-preview-container" class="hidden mt-4 bg-white border border-slate-200/80 rounded-2xl p-4 shadow-sm relative transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    {{-- Icon/Image Thumbnail --}}
                                    <div class="size-16 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100 overflow-hidden relative" id="preview-media-wrapper">
                                        {{-- Will render img or file icon --}}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-800 truncate" id="preview-filename">-</p>
                                        <p class="text-xs text-slate-500 mt-0.5" id="preview-filesize">-</p>
                                        <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                            Berhasil diunggah
                                        </span>
                                    </div>
                                    <button type="button" id="btn-remove-file" class="size-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center transition-colors shadow-sm self-start">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 pt-6 border-t border-slate-100">
                            <a href="{{ route('skkm.index') }}" class="px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors text-center justify-center inline-flex w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit" id="btn-submit" disabled class="px-8 py-3 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100 text-center justify-center inline-flex w-full sm:w-auto">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const allRules = @json($rules);

        const elUnsur    = document.getElementById('select_unsur');
        const elJenis    = document.getElementById('select_jenis');
        const elTingkat  = document.getElementById('select_tingkat');
        const elPeranan  = document.getElementById('select_peranan');
        const elHidden   = document.getElementById('point_rule_id');
        const elPreview  = document.getElementById('poin-preview');
        const elPoinVal  = document.getElementById('poin-value');
        const elPoinDesc = document.getElementById('poin-description');
        const elPoinBukti= document.getElementById('poin-bukti');
        const elSubmit   = document.getElementById('btn-submit');
        const elStepLabel= document.getElementById('step-label');
        const elFile     = document.getElementById('file_bukti');

        const unsurLabels = {
            'penalaran': 'Penalaran & Keilmuan',
            'bakat_minat': 'Bakat & Minat',
            'sosial': 'Sosial & Kemasyarakatan',
            'kegiatan_khusus': 'Kegiatan Khusus',
        };

        // Populate unique Unsur values
        const unsurSet = [...new Set(allRules.map(r => r.unsur))];
        unsurSet.forEach(u => {
            const opt = document.createElement('option');
            opt.value = u;
            opt.textContent = unsurLabels[u] || u.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            elUnsur.appendChild(opt);
        });

        function resetSelect(el, placeholder) {
            el.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.disabled = true;
            opt.selected = true;
            opt.textContent = placeholder;
            el.appendChild(opt);
            el.disabled = true;
            el.classList.add('bg-slate-100');
            el.classList.remove('bg-slate-50');
        }

        function enableSelect(el) {
            el.disabled = false;
            el.classList.remove('bg-slate-100');
            el.classList.add('bg-slate-50');
        }

        function updateStepIndicators(activeStep) {
            const labels = ['Pilih Unsur', 'Pilih Jenis Kegiatan', 'Pilih Tingkat', 'Pilih Peranan'];
            for (let i = 1; i <= 4; i++) {
                const ind = document.getElementById('step-indicator-' + i);
                if (i <= activeStep) {
                    ind.classList.remove('bg-slate-200', 'text-slate-500');
                    ind.classList.add('bg-indigo-600', 'text-white');
                } else {
                    ind.classList.remove('bg-indigo-600', 'text-white');
                    ind.classList.add('bg-slate-200', 'text-slate-500');
                }
            }
            const badgeIds = [null, 'jenis-badge', 'tingkat-badge', 'peranan-badge'];
            for (let i = 1; i <= 3; i++) {
                const badge = document.getElementById(badgeIds[i]);
                if (badge) {
                    if (i < activeStep) {
                        badge.classList.remove('bg-slate-100', 'text-slate-500');
                        badge.classList.add('bg-indigo-100', 'text-indigo-700');
                    } else {
                        badge.classList.remove('bg-indigo-100', 'text-indigo-700');
                        badge.classList.add('bg-slate-100', 'text-slate-500');
                    }
                }
            }
            elStepLabel.textContent = 'Langkah ' + activeStep + ': ' + labels[activeStep - 1];
        }

        function hidePreview() {
            elPreview.classList.add('hidden');
            elHidden.value = '';
            elSubmit.disabled = true;
        }

        function showPreview(rule) {
            elHidden.value = rule.id;
            elPoinVal.textContent = rule.poin;
            const tingkatLabel = rule.tingkat ? (' • ' + rule.tingkat.replace(/\b\w/g, c => c.toUpperCase())) : '';
            const perananLabel = rule.peranan.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
            elPoinDesc.textContent = rule.jenis_item + tingkatLabel + ' • ' + perananLabel;
            elPoinBukti.textContent = rule.bukti_fisik_required || '-';
            elPreview.classList.remove('hidden');
            
            // Enable submit only if file is uploaded and upload is not in progress
            const elUploadedPath = document.getElementById('uploaded_file_path');
            const elProgressContainer = document.getElementById('upload-progress-container');
            const uploadInProgress = elProgressContainer && !elProgressContainer.classList.contains('hidden');
            if (elUploadedPath && elUploadedPath.value && !uploadInProgress) {
                elSubmit.disabled = false;
            } else {
                elSubmit.disabled = true;
            }
        }

        // When Unsur changes
        elUnsur.addEventListener('change', function () {
            const selectedUnsur = this.value;

            resetSelect(elJenis, '-- Pilih Jenis Kegiatan --');
            resetSelect(elTingkat, '-- Pilih Jenis Kegiatan terlebih dahulu --');
            resetSelect(elPeranan, '-- Pilih Tingkat terlebih dahulu --');
            hidePreview();
            updateStepIndicators(2);

            // Get unique jenis_item values for the selected unsur
            const filtered = allRules.filter(r => r.unsur === selectedUnsur);
            const jenisSet = [...new Set(filtered.map(r => JSON.stringify([r.sub_unsur, r.jenis_item])))];

            jenisSet.forEach(key => {
                const [subUnsur, jenisItem] = JSON.parse(key);
                const opt = document.createElement('option');
                opt.value = key;
                opt.textContent = jenisItem + ' (' + subUnsur.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) + ')';
                elJenis.appendChild(opt);
            });

            enableSelect(elJenis);
        });

        // When Jenis changes
        elJenis.addEventListener('change', function () {
            const selectedUnsur = elUnsur.value;
            const [selectedSubUnsur, selectedJenis] = JSON.parse(this.value);

            resetSelect(elTingkat, '-- Pilih Tingkat --');
            resetSelect(elPeranan, '-- Pilih Tingkat terlebih dahulu --');
            hidePreview();
            updateStepIndicators(3);

            const filtered = allRules.filter(r =>
                r.unsur === selectedUnsur &&
                r.sub_unsur === selectedSubUnsur &&
                r.jenis_item === selectedJenis
            );

            const tingkatSet = [...new Set(filtered.map(r => r.tingkat || '__none__'))];

            // If only one tingkat and it's null, auto-select and skip
            if (tingkatSet.length === 1 && tingkatSet[0] === '__none__') {
                const opt = document.createElement('option');
                opt.value = '__none__';
                opt.textContent = 'Tidak ada tingkat';
                opt.selected = true;
                elTingkat.appendChild(opt);
                enableSelect(elTingkat);
                elTingkat.dispatchEvent(new Event('change'));
                return;
            }

            tingkatSet.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t;
                opt.textContent = t === '__none__' ? 'Tidak ada tingkat' : t.replace(/\b\w/g, c => c.toUpperCase());
                elTingkat.appendChild(opt);
            });

            enableSelect(elTingkat);
        });

        // When Tingkat changes
        elTingkat.addEventListener('change', function () {
            const selectedUnsur = elUnsur.value;
            const [selectedSubUnsur, selectedJenis] = JSON.parse(elJenis.value);
            const selectedTingkat = this.value;

            resetSelect(elPeranan, '-- Pilih Peranan --');
            hidePreview();
            updateStepIndicators(4);

            const filtered = allRules.filter(r =>
                r.unsur === selectedUnsur &&
                r.sub_unsur === selectedSubUnsur &&
                r.jenis_item === selectedJenis &&
                (selectedTingkat === '__none__' ? r.tingkat === null : r.tingkat === selectedTingkat)
            );

            const perananSet = [...new Set(filtered.map(r => r.peranan))];

            // If only one peranan, auto-select
            if (perananSet.length === 1) {
                const opt = document.createElement('option');
                opt.value = perananSet[0];
                opt.textContent = perananSet[0].replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                opt.selected = true;
                elPeranan.appendChild(opt);
                enableSelect(elPeranan);
                elPeranan.dispatchEvent(new Event('change'));
                return;
            }

            perananSet.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p;
                opt.textContent = p.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                elPeranan.appendChild(opt);
            });

            enableSelect(elPeranan);
        });

        // When Peranan changes → find the exact rule
        elPeranan.addEventListener('change', function () {
            const selectedUnsur = elUnsur.value;
            const [selectedSubUnsur, selectedJenis] = JSON.parse(elJenis.value);
            const selectedTingkat = elTingkat.value;
            const selectedPeranan = this.value;

            const rule = allRules.find(r =>
                r.unsur === selectedUnsur &&
                r.sub_unsur === selectedSubUnsur &&
                r.jenis_item === selectedJenis &&
                (selectedTingkat === '__none__' ? r.tingkat === null : r.tingkat === selectedTingkat) &&
                r.peranan === selectedPeranan
            );

            if (rule) {
                showPreview(rule);
            } else {
                hidePreview();
            }
        });

        // Modern File Upload with Progress Bar and direct preview
        const elDropzone = document.getElementById('upload-dropzone');
        const elProgressContainer = document.getElementById('upload-progress-container');
        const elProgressBar = document.getElementById('upload-progress-bar');
        const elProgressPercent = document.getElementById('upload-progress-percent');
        const elPreviewContainer = document.getElementById('upload-preview-container');
        const elPreviewMedia = document.getElementById('preview-media-wrapper');
        const elPreviewFilename = document.getElementById('preview-filename');
        const elPreviewFilesize = document.getElementById('preview-filesize');
        const elBtnRemove = document.getElementById('btn-remove-file');
        const elUploadedPath = document.getElementById('uploaded_file_path');

        if (elFile) {
            // Drag over effects for Dropzone
            elDropzone.addEventListener('dragover', function (e) {
                e.preventDefault();
                elDropzone.classList.add('border-indigo-500', 'bg-indigo-50');
            });
            elDropzone.addEventListener('dragleave', function (e) {
                e.preventDefault();
                elDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
            });
            elDropzone.addEventListener('drop', function (e) {
                elDropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
            });

            elFile.addEventListener('change', function () {
                const file = this.files[0];
                if (!file) return;

                // Validate size (5MB)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file maksimal adalah 5MB.');
                    this.value = '';
                    return;
                }

                // Show progress bar, hide dropzone, hide preview
                elDropzone.classList.add('hidden');
                elProgressContainer.classList.remove('hidden');
                elPreviewContainer.classList.add('hidden');
                elSubmit.disabled = true;

                // Create FormData
                const formData = new FormData();
                formData.append('file_bukti', file);

                // Setup CSRF Token
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

                // Perform AJAX upload via XMLHttpRequest
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route("skkm.upload-temp") }}', true);
                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                xhr.setRequestHeader('Accept', 'application/json');

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        const percent = Math.round((e.loaded / e.total) * 100);
                        elProgressBar.style.width = percent + '%';
                        elProgressPercent.textContent = percent + '%';
                    }
                };

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                elUploadedPath.value = response.path;
                                elProgressContainer.classList.add('hidden');
                                
                                // Setup preview
                                elPreviewFilename.textContent = file.name;
                                
                                // Format file size
                                const kb = file.size / 1024;
                                if (kb > 1024) {
                                    elPreviewFilesize.textContent = (kb / 1024).toFixed(2) + ' MB';
                                } else {
                                    elPreviewFilesize.textContent = kb.toFixed(1) + ' KB';
                                }

                                // Render preview media based on file type
                                elPreviewMedia.innerHTML = '';
                                if (file.type.startsWith('image/')) {
                                    const img = document.createElement('img');
                                    img.src = response.url;
                                    img.className = 'w-full h-full object-cover';
                                    elPreviewMedia.appendChild(img);
                                } else {
                                    // PDF Icon
                                    elPreviewMedia.innerHTML = `
                                        <div class="flex flex-col items-center justify-center text-rose-500">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-[9px] font-bold mt-0.5 uppercase">PDF</span>
                                        </div>
                                    `;
                                }

                                elPreviewContainer.classList.remove('hidden');
                                
                                // Enable submit button if a rule is selected
                                if (elHidden.value) {
                                    elSubmit.disabled = false;
                                }
                                
                                // Remove required validation attribute
                                elFile.removeAttribute('required');
                            } else {
                                handleUploadError();
                            }
                        } catch (e) {
                            handleUploadError();
                        }
                    } else {
                        handleUploadError();
                    }
                };

                xhr.onerror = function () {
                    handleUploadError();
                };

                xhr.send(formData);
            });

            function handleUploadError() {
                alert('Gagal mengunggah file. Silakan coba lagi.');
                resetUploadState();
            }

            function resetUploadState() {
                elFile.value = '';
                elUploadedPath.value = '';
                elFile.setAttribute('required', 'required');
                elDropzone.classList.remove('hidden');
                elProgressContainer.classList.add('hidden');
                elPreviewContainer.classList.add('hidden');
                elProgressBar.style.width = '0%';
                elProgressPercent.textContent = '0%';
                if (!elHidden.value) {
                    elSubmit.disabled = true;
                }
            }

            elBtnRemove.addEventListener('click', function (e) {
                e.preventDefault();
                resetUploadState();
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
