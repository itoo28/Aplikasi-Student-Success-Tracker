<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.fakultas.index') }}" class="inline-flex items-center justify-center rounded-lg bg-emerald-100 p-2 text-emerald-700 hover:bg-emerald-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="space-y-1">
                <span class="inline-flex items-center rounded-lg bg-emerald-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-emerald-700">Fakultas</span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">{{ $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas' }}</h2>
            </div>
        </div>
    </x-slot>

    <div class="relative isolate max-w-2xl overflow-hidden rounded-[2rem] border border-emerald-100/80 bg-gradient-to-br from-emerald-50 via-cyan-50 to-sky-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-emerald-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-56 w-56 rounded-full bg-cyan-200/35 blur-3xl"></div>

        <div class="relative rounded-3xl border border-emerald-100/80 bg-white/90 p-8 shadow-[0_8px_30px_rgb(16,185,129,0.14)] backdrop-blur-sm">
            @if ($errors->any())
                <div class="mb-6 bg-rose-100/90 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $isEdit ? route('admin.fakultas.update', $fakultas) : route('admin.fakultas.store') }}" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div>
                    <label for="kode" class="block text-sm font-semibold text-slate-700 mb-2">Kode Fakultas</label>
                    <input id="kode" type="text" name="kode" required value="{{ old('kode', $fakultas->kode) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Fakultas</label>
                    <input id="nama" type="text" name="nama" required value="{{ old('nama', $fakultas->nama) }}" class="block w-full rounded-xl border-slate-200 bg-white py-2.5 px-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked((int) old('is_active', $fakultas->is_active ?? 1) === 1) class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        Fakultas aktif
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-emerald-100/80">
                    <a href="{{ route('admin.fakultas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white text-sm font-semibold shadow-[0_8px_18px_rgb(16,185,129,0.30)] transition-all">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Fakultas' }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
