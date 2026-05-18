<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.fakultas.index') }}" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">{{ $isEdit ? 'Edit Fakultas' : 'Tambah Fakultas' }}</h2>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
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
                    <input id="kode" type="text" name="kode" required value="{{ old('kode', $fakultas->kode) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="nama" class="block text-sm font-semibold text-slate-700 mb-2">Nama Fakultas</label>
                    <input id="nama" type="text" name="nama" required value="{{ old('nama', $fakultas->nama) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked((int) old('is_active', $fakultas->is_active ?? 1) === 1) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        Fakultas aktif
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.fakultas.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Fakultas' }}</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
