<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('skkm.point-rules.index') }}" class="inline-flex items-center justify-center rounded-lg bg-sky-100 p-2 text-sky-700 hover:bg-sky-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="space-y-1">
                <span class="inline-flex items-center rounded-lg bg-sky-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-sky-700">Poin SKKM</span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">{{ $isEdit ? 'Edit Kategori Poin' : 'Tambah Kategori Poin' }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="relative isolate max-w-4xl overflow-hidden rounded-[2rem] border border-sky-100/80 bg-gradient-to-br from-sky-50 via-indigo-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative rounded-3xl border border-sky-100/80 bg-white/90 p-8 shadow-[0_8px_30px_rgb(14,165,233,0.14)] backdrop-blur-sm">
            @if ($errors->any())
                <div class="mb-6 bg-rose-100/90 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $isEdit ? route('skkm.point-rules.update', $pointRule) : route('skkm.point-rules.store') }}" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="unsur" class="block text-sm font-semibold text-slate-700 mb-2">Unsur SKKM</label>
                        <select id="unsur" name="unsur" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">-- Pilih Unsur --</option>
                            @foreach ($unsurOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('unsur', $pointRule->unsur) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sub_unsur" class="block text-sm font-semibold text-slate-700 mb-2">Kode Sub-unsur</label>
                        <input id="sub_unsur" type="text" name="sub_unsur" required value="{{ old('sub_unsur', $pointRule->sub_unsur) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: lomba_kti">
                        <p class="mt-1 text-xs text-slate-500">Gunakan kode ringkas yang konsisten, misalnya <span class="font-semibold">seminar_nasional</span>.</p>
                    </div>

                    <div>
                        <label for="jenis_item" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kegiatan</label>
                        <input id="jenis_item" type="text" name="jenis_item" required value="{{ old('jenis_item', $pointRule->jenis_item) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Mendapat Prestasi">
                    </div>

                    <div>
                        <label for="tingkat" class="block text-sm font-semibold text-slate-700 mb-2">Tingkat Kegiatan</label>
                        <select id="tingkat" name="tingkat" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="">Tanpa tingkat</option>
                            @foreach ($tingkatOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('tingkat', $pointRule->tingkat) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="peranan" class="block text-sm font-semibold text-slate-700 mb-2">Kode Peranan</label>
                        <input id="peranan" type="text" name="peranan" required value="{{ old('peranan', $pointRule->peranan) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: juara_1">
                    </div>

                    <div>
                        <label for="poin" class="block text-sm font-semibold text-slate-700 mb-2">Jumlah Poin</label>
                        <input id="poin" type="number" name="poin" min="1" required value="{{ old('poin', $pointRule->poin) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: 20">
                    </div>
                </div>

                <div>
                    <label for="bukti_fisik_required" class="block text-sm font-semibold text-slate-700 mb-2">Bukti Fisik Wajib</label>
                    <textarea id="bukti_fisik_required" name="bukti_fisik_required" rows="3" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Contoh: Sertifikat, karya tulis, dan foto kegiatan">{{ old('bukti_fisik_required', $pointRule->bukti_fisik_required) }}</textarea>
                </div>

                <div>
                    <label for="keterangan" class="block text-sm font-semibold text-slate-700 mb-2">Keterangan Tambahan</label>
                    <textarea id="keterangan" name="keterangan" rows="3" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500" placeholder="Opsional">{{ old('keterangan', $pointRule->keterangan) }}</textarea>
                </div>

                <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked((int) old('is_active', $pointRule->is_active ?? 1) === 1) class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    Kategori aktif dan tampil pada form mahasiswa
                </label>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-sky-100/80">
                    <a href="{{ route('skkm.point-rules.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 text-white text-sm font-semibold shadow-[0_8px_18px_rgb(14,165,233,0.30)] transition-all">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
