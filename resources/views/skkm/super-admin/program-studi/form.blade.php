<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.program-studi.index') }}" class="inline-flex items-center justify-center rounded-lg bg-sky-100 p-2 text-sky-700 hover:bg-sky-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="space-y-1">
                <span class="inline-flex items-center rounded-lg bg-sky-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-sky-700">Program Studi</span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">{{ $isEdit ? 'Edit Program Studi' : 'Tambah Program Studi' }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="relative isolate max-w-3xl overflow-hidden rounded-[2rem] border border-sky-100/80 bg-gradient-to-br from-sky-50 via-indigo-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative rounded-3xl border border-sky-100/80 bg-white/90 p-8 shadow-[0_8px_30px_rgb(14,165,233,0.14)] backdrop-blur-sm">
            @if ($errors->any())
                <div class="mb-6 bg-rose-100/90 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $isEdit ? route('admin.program-studi.update', $programStudi) : route('admin.program-studi.store') }}" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div>
                    <label for="fakultas_id" class="block text-sm font-semibold text-slate-700 mb-2">Fakultas</label>
                    <select id="fakultas_id" name="fakultas_id" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                        <option value="" disabled @selected(!old('fakultas_id', $programStudi->fakultas_id))>- Pilih Fakultas -</option>
                        @foreach ($fakultas as $item)
                            <option value="{{ $item->id }}" @selected((string) old('fakultas_id', $programStudi->fakultas_id) === (string) $item->id)>{{ $item->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kode" class="block text-sm font-semibold text-slate-700 mb-2">Kode Program Studi</label>
                        <input id="kode" type="text" name="kode" required value="{{ old('kode', $programStudi->kode) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                    </div>
                    <div>
                        <label for="jenjang" class="block text-sm font-semibold text-slate-700 mb-2">Jenjang</label>
                        <select id="jenjang" name="jenjang" required class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                            <option value="" disabled @selected(!old('jenjang', $programStudi->jenjang))>- Pilih Jenjang -</option>
                            <option value="S1" @selected(old('jenjang', $programStudi->jenjang) === 'S1')>S1</option>
                            <option value="D4" @selected(old('jenjang', $programStudi->jenjang) === 'D4')>D4</option>
                            <option value="D3" @selected(old('jenjang', $programStudi->jenjang) === 'D3')>D3</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Program Studi</label>
                    <input id="nama" type="text" name="nama" required value="{{ old('nama', $programStudi->nama) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                </div>

                <div>
                    <label for="registration_code" class="block text-sm font-semibold text-slate-700 mb-2">Kode Unik Pendaftaran</label>
                    <input id="registration_code" type="text" name="registration_code" value="{{ old('registration_code', $programStudi->registration_code) }}" placeholder="Contoh: INF-2026" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-sky-500 focus:ring-sky-500">
                    <p class="mt-1 text-xs text-slate-500">Kode unik ini digunakan mahasiswa untuk mendaftar akun pada Program Studi ini. Biarkan kosong jika prodi ini tidak memerlukan verifikasi kode registrasi.</p>
                </div>

                <div>
                    <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked((int) old('is_active', $programStudi->is_active ?? 1) === 1) class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        Program studi aktif
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-sky-100/80">
                    <a href="{{ route('admin.program-studi.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 text-white text-sm font-semibold shadow-[0_8px_18px_rgb(14,165,233,0.30)] transition-all">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Program Studi' }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
