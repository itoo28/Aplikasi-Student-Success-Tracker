<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-xl bg-sky-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-sky-700">Master Data</span>
                <h2 class="font-semibold text-2xl text-slate-800 leading-tight">Manajemen Poin SKKM</h2>
            </div>
        </div>
    </x-slot>

    @php
        $hasActiveFilters = filled($search) || filled($selectedUnsur) || filled($selectedStatus);
    @endphp

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-sky-100/80 bg-gradient-to-br from-sky-50 via-indigo-50 to-cyan-50 p-6 sm:p-8 space-y-6">
        <div class="pointer-events-none absolute -left-24 top-6 h-56 w-56 rounded-full bg-sky-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-10 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-100/90 px-4 py-3 text-sm font-semibold text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="rounded-2xl border border-sky-100/80 bg-white/90 p-5 shadow-[0_8px_24px_rgb(14,165,233,0.14)] backdrop-blur-sm">
                <form id="pointRuleFilterForm" method="GET" action="{{ route('skkm.point-rules.index') }}">
                    <div class="grid grid-cols-1 gap-3 lg:grid-cols-4">
                        <div class="lg:col-span-2">
                            <flux:field>
                                <flux:label for="search">Cari Kategori</flux:label>
                                <flux:input id="search" name="search" value="{{ $search }}" placeholder="Sub-unsur, jenis kegiatan, peranan, atau bukti fisik" />
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label for="unsur">Unsur SKKM</flux:label>
                                <flux:select id="unsur" name="unsur">
                                    <flux:select.option value="">Semua Unsur</flux:select.option>
                                    @foreach ($unsurOptions as $value => $label)
                                        <flux:select.option value="{{ $value }}" :selected="$selectedUnsur === $value">{{ $label }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>
                        <div>
                            <flux:field>
                                <flux:label for="status">Status</flux:label>
                                <flux:select id="status" name="status">
                                    <flux:select.option value="">Semua Status</flux:select.option>
                                    @foreach ($statusOptions as $value => $label)
                                        <flux:select.option value="{{ $value }}" :selected="$selectedStatus === $value">{{ $label }}</flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>
                    </div>

                    @if ($hasActiveFilters)
                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            <a href="{{ route('skkm.point-rules.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">Reset Semua</a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="rounded-3xl border border-sky-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(14,165,233,0.14)] overflow-hidden">
                <div class="px-6 py-4 border-b border-sky-100/70 bg-sky-50/60 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        @if ($pointRules->total() > 0)
                            <p class="text-xs font-semibold text-sky-700">
                                Data yang terlihat sekarang: {{ $pointRules->count() }} data, dari total {{ $pointRules->total() }} data.
                            </p>
                            <p class="mt-1 text-xs text-sky-600">
                                Di halaman ini menampilkan data nomor {{ $pointRules->firstItem() }} sampai {{ $pointRules->lastItem() }}.
                            </p>
                        @else
                            <p class="text-xs font-semibold text-sky-700">
                                Belum ada data untuk ditampilkan.
                            </p>
                        @endif
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('skkm.point-rules.create') }}" class="inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-gradient-to-r from-sky-600 to-indigo-600 hover:from-sky-700 hover:to-indigo-700 shadow-[0_6px_16px_rgb(14,165,233,0.25)] transition-all hover:shadow-lg hover:-translate-y-px active:translate-y-0">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Tambah Kategori Poin
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1080px] text-left text-sm text-slate-600">
                        <thead class="text-xs text-sky-700 uppercase bg-sky-50/80">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kategori</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tingkat & Peranan</th>
                                <th class="px-6 py-4 text-center font-semibold tracking-wider">Poin</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Bukti Fisik</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="w-[220px] px-6 py-4 text-right font-semibold tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sky-100/60">
                            @forelse ($pointRules as $pointRule)
                                <tr class="hover:bg-sky-50/40 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-800">{{ $pointRule->jenis_item }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $unsurOptions[$pointRule->unsur] ?? $pointRule->unsur }} &middot; {{ str_replace('_', ' ', $pointRule->sub_unsur) }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-700">{{ $pointRule->tingkat ? ucfirst(str_replace('_', ' ', $pointRule->tingkat)) : 'Tanpa tingkat' }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ ucfirst(str_replace('_', ' ', $pointRule->peranan)) }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-indigo-100 text-indigo-700">{{ $pointRule->poin }}</span>
                                    </td>
                                    <td class="max-w-sm px-6 py-4 text-xs leading-relaxed text-slate-600">{{ $pointRule->bukti_fisik_required }}</td>
                                    <td class="px-6 py-4">
                                        @if ($pointRule->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-50 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="w-[220px] whitespace-nowrap px-6 py-4">
                                        <div class="flex flex-nowrap items-center justify-end gap-2">
                                            <a href="{{ route('skkm.point-rules.edit', $pointRule) }}" class="inline-flex min-w-[86px] items-center justify-center gap-1 rounded-lg border border-amber-200 bg-amber-100 px-3 py-2 text-xs font-semibold text-amber-800 transition-colors hover:bg-amber-200">
                                                <i data-lucide="pencil" class="h-3.5 w-3.5"></i>
                                                Edit
                                            </a>
                                            <form
                                                action="{{ route('skkm.point-rules.destroy', $pointRule) }}"
                                                method="POST"
                                                class="shrink-0"
                                                data-confirm
                                                data-confirm-variant="danger"
                                                data-confirm-title="Hapus Kategori Poin?"
                                                data-confirm-message="Kategori hanya dapat dihapus jika belum pernah dipakai pengajuan mahasiswa. Apakah Anda yakin ingin melanjutkan?"
                                                data-confirm-approve="Ya, Hapus Kategori"
                                                data-confirm-cancel="Batal"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex min-w-[86px] items-center justify-center gap-1 rounded-lg border border-rose-200 bg-rose-100 px-3 py-2 text-xs font-semibold text-rose-700 transition-colors hover:bg-rose-200">
                                                    <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada kategori poin SKKM pada filter saat ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-sky-100/70 bg-white/70">
                    {{ $pointRules->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const pointRuleFilterForm = document.getElementById('pointRuleFilterForm');
            const pointRuleSearchInput = document.getElementById('search');
            const pointRuleUnsurInput = document.getElementById('unsur');
            const pointRuleStatusInput = document.getElementById('status');

            const debounce = (callback, delay = 450) => {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => callback(...args), delay);
                };
            };

            if (pointRuleFilterForm) {
                const submitFilters = () => pointRuleFilterForm.requestSubmit();

                pointRuleUnsurInput?.addEventListener('change', submitFilters);
                pointRuleStatusInput?.addEventListener('change', submitFilters);
                pointRuleSearchInput?.addEventListener('input', debounce(submitFilters));
                pointRuleSearchInput?.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        submitFilters();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
