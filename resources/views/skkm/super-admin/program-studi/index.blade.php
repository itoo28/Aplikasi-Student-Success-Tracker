<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-xl bg-sky-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-sky-700">Master Data</span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">Manajemen Program Studi</h2>
            </div>
            <a href="{{ route('admin.program-studi.create') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 shadow-[0_8px_20px_rgb(14,165,233,0.35)] transition-all">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Prodi
            </a>
        </div>
    </x-slot>

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-sky-100/80 bg-gradient-to-br from-sky-50 via-indigo-50 to-cyan-50 p-6 sm:p-8 space-y-6">
        <div class="pointer-events-none absolute -left-24 top-6 h-56 w-56 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-10 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative space-y-6">
            <div class="rounded-3xl border border-sky-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(14,165,233,0.14)] overflow-hidden">
                <div class="px-6 py-3 border-b border-sky-100/70 bg-sky-50/60">
                    @if ($programStudis->total() > 0)
                        <p class="text-xs font-semibold text-sky-700">
                            Data yang terlihat sekarang: {{ $programStudis->count() }} data, dari total {{ $programStudis->total() }} data.
                        </p>
                        <p class="mt-1 text-xs text-sky-600">
                            Di halaman ini menampilkan data nomor {{ $programStudis->firstItem() }} sampai {{ $programStudis->lastItem() }}.
                        </p>
                    @else
                        <p class="text-xs font-semibold text-sky-700">
                            Belum ada data untuk ditampilkan.
                        </p>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] text-sm text-left text-slate-600">
                        <thead class="text-xs text-sky-700 uppercase bg-sky-50/80">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kode</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Fakultas</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Jenjang</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="w-[220px] px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100/60">
                            @forelse ($programStudis as $prodi)
                                <tr class="hover:bg-sky-50/40 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $prodi->kode }}</td>
                                    <td class="px-6 py-4">{{ $prodi->nama }}</td>
                                    <td class="px-6 py-4">{{ $prodi->fakultas?->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">{{ $prodi->jenjang }}</td>
                                    <td class="px-6 py-4">
                                        @if ($prodi->is_active)
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="w-[220px] px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-nowrap items-center justify-end gap-2">
                                            <a href="{{ route('admin.program-studi.edit', $prodi) }}" class="inline-flex min-w-[86px] items-center justify-center gap-1 px-3 py-2 rounded-lg border border-amber-200 bg-amber-100 text-amber-800 text-xs font-semibold hover:bg-amber-200 transition-colors">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                Edit
                                            </a>
                                            <form
                                                action="{{ route('admin.program-studi.destroy', $prodi) }}"
                                                method="POST"
                                                class="shrink-0"
                                                data-confirm
                                                data-confirm-variant="danger"
                                                data-confirm-title="Hapus Program Studi?"
                                                data-confirm-message="Data program studi yang dihapus tidak bisa dikembalikan. Apakah Anda yakin ingin melanjutkan?"
                                                data-confirm-approve="Ya, Hapus Program Studi"
                                                data-confirm-cancel="Batal"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex min-w-[86px] items-center justify-center gap-1 px-3 py-2 rounded-lg border border-rose-200 bg-rose-100 text-rose-700 text-xs font-semibold hover:bg-rose-200 transition-colors">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    Hapus
                                                </button>
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
                <div class="px-6 py-4 border-t border-sky-100/70 bg-white/70">
                    {{ $programStudis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
