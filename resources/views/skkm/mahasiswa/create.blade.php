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

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden backdrop-blur-xl">
                
                <div class="bg-indigo-600 px-8 py-6">
                    <h3 class="text-xl font-bold text-white">Form Pengajuan Kegiatan</h3>
                    <p class="text-indigo-200 mt-1 text-sm">Pilih kategori kegiatan secara bertahap dan unggah bukti fisik yang sesuai.</p>
                </div>

                <div class="p-8">
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
                                <input type="number" name="semester_input" id="semester_input" min="1" max="8" required value="{{ old('semester_input') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: 3">
                            </div>
                        </div>

                        {{-- File Upload --}}
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Fisik (Sertifikat / Surat Tugas)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-colors bg-slate-50 group">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="file_bukti" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span class="px-2">Upload file PDF, JPG, PNG</span>
                                            <input id="file_bukti" name="file_bukti" type="file" class="sr-only" required accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                    </div>
                                    <p class="text-xs text-slate-500">Maksimal 5MB.</p>
                                    <p id="file-name-preview" class="text-xs font-semibold text-indigo-600 hidden"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100">
                            <a href="{{ route('skkm.index') }}" class="px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Batal
                            </a>
                            <button type="submit" id="btn-submit" disabled class="px-8 py-3 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100">
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
        const elFileName = document.getElementById('file-name-preview');

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
            elSubmit.disabled = false;
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
            const jenisSet = [...new Set(filtered.map(r => r.sub_unsur + '||' + r.jenis_item))];

            jenisSet.forEach(key => {
                const [subUnsur, jenisItem] = key.split('||');
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
            const [selectedSubUnsur, selectedJenis] = this.value.split('||');

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
            const [selectedSubUnsur, selectedJenis] = elJenis.value.split('||');
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
            const [selectedSubUnsur, selectedJenis] = elJenis.value.split('||');
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

        // File name preview
        if (elFile) {
            elFile.addEventListener('change', function () {
                if (this.files.length > 0) {
                    elFileName.textContent = this.files[0].name;
                    elFileName.classList.remove('hidden');
                } else {
                    elFileName.classList.add('hidden');
                }
            });
        }
    });
    </script>
    @endpush
</x-app-layout>
