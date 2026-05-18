<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">CRUD Program Studi</h2>
            <a href="{{ route('admin.program-studi.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Prodi
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
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
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Kode</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Fakultas</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-center">Jenjang</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($programStudis as $prodi)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $prodi->kode }}</td>
                                <td class="px-6 py-4">{{ $prodi->nama }}</td>
                                <td class="px-6 py-4">{{ $prodi->fakultas?->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">{{ $prodi->jenjang }}</td>
                                <td class="px-6 py-4">
                                    @if ($prodi->is_active)
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.program-studi.edit', $prodi) }}" class="inline-flex px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100">Edit</a>
                                        <form action="{{ route('admin.program-studi.destroy', $prodi) }}" method="POST" onsubmit="return confirm('Hapus program studi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold hover:bg-rose-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data program studi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $programStudis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
