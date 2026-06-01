<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center rounded-xl bg-violet-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-violet-700">Akses Akun</span>
                <flux:heading size="xl" level="1">Manajemen User</flux:heading>
            </div>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 shadow-[0_8px_20px_rgb(124,58,237,0.35)] transition-all">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Tambah User
            </a>
        </div>
    </x-slot>

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-violet-100/80 bg-gradient-to-br from-violet-50 via-indigo-50 to-sky-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-52 w-52 rounded-full bg-violet-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-6 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative space-y-6">
            @php
                $hasActiveFilters = filled($selectedSearch)
                    || filled($selectedRole)
                    || filled($selectedProgramStudi)
                    || filled($selectedSemester)
                    || $selectedSort !== 'name_asc'
                    || (int) $perPage !== 15;

                $selectedProgramStudiLabel = null;
                if ($selectedProgramStudi) {
                    $selectedProdi = $programStudis->firstWhere('id', (int) $selectedProgramStudi);
                    if ($selectedProdi) {
                        $selectedProgramStudiLabel = trim($selectedProdi->jenjang . ' ' . $selectedProdi->nama);
                    }
                }
            @endphp

            <div class="rounded-3xl border border-violet-100/80 bg-white/95 shadow-[0_10px_30px_rgb(124,58,237,0.12)] backdrop-blur-sm overflow-hidden">
                <div class="border-b border-violet-100 bg-gradient-to-r from-violet-50 via-indigo-50 to-sky-50 px-5 py-4">
                    <h3 class="text-sm font-bold text-slate-800">Panel Filter & Navigasi Data</h3>
                    <p class="mt-1 text-xs text-slate-500">Gunakan pencarian dulu, lalu persempit dengan role/program studi/semester agar data lebih fokus.</p>
                </div>

                <form id="userFilterForm" method="GET" action="{{ route('admin.users.index') }}" class="space-y-4 p-5">
                    <div class="grid grid-cols-1 gap-3 xl:grid-cols-4">
                        <div class="xl:col-span-2">
                            <flux:field>
                                <flux:label for="search">Cari User</flux:label>
                                <flux:input
                                    id="search"
                                    name="search"
                                    value="{{ $selectedSearch }}"
                                    placeholder="Nama, email, atau NIM/NIDN"
                                />
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label for="sort">Urutkan Data</flux:label>
                                <flux:select id="sort" name="sort">
                                    @foreach ($sortOptions as $value => $label)
                                        <flux:select.option value="{{ $value }}" :selected="$selectedSort === $value">
                                            {{ $label }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>

                        <div>
                            <flux:field>
                                <flux:label for="per_page">Per Halaman</flux:label>
                                <flux:select id="per_page" name="per_page">
                                    @foreach ($perPageOptions as $perPageOption)
                                        <flux:select.option value="{{ $perPageOption }}" :selected="(int) $perPage === (int) $perPageOption">
                                            {{ $perPageOption }} data
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                        <div class="grid grid-cols-1 gap-3 xl:grid-cols-4">
                            <div>
                                <flux:field>
                                    <flux:label for="role">Filter Role</flux:label>
                                    <flux:select id="role" name="role">
                                        <flux:select.option value="">Semua Role</flux:select.option>
                                        @foreach ($roleOptions as $value => $label)
                                            <flux:select.option value="{{ $value }}" :selected="$selectedRole === $value">
                                                {{ $label }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>

                            <div id="programStudiFilterWrap" class="{{ $selectedRole === 'mahasiswa' ? '' : 'hidden' }} xl:col-span-2">
                                <flux:field>
                                    <flux:label for="program_studi_id">Filter Program Studi</flux:label>
                                    <flux:select id="program_studi_id" name="program_studi_id">
                                        <flux:select.option value="">Semua Program Studi</flux:select.option>
                                        @foreach ($programStudis as $prodi)
                                            <flux:select.option value="{{ $prodi->id }}" :selected="(string) $selectedProgramStudi === (string) $prodi->id">
                                                {{ $prodi->jenjang }} {{ $prodi->nama }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>

                            <div id="semesterFilterWrap" class="{{ $selectedRole === 'mahasiswa' ? '' : 'hidden' }}">
                                <flux:field>
                                    <flux:label for="semester">Filter Semester</flux:label>
                                    <flux:select id="semester" name="semester">
                                        <flux:select.option value="">Semua Semester</flux:select.option>
                                        @foreach ($semesterOptions as $semesterOption)
                                            <flux:select.option value="{{ $semesterOption }}" :selected="(int) $selectedSemester === (int) $semesterOption">
                                                Semester {{ $semesterOption }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </flux:field>
                            </div>
                        </div>
                    </div>

                    @if ($hasActiveFilters)
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Reset Semua</a>
                        </div>
                    @endif
                </form>

                @if ($hasActiveFilters)
                    <div class="border-t border-violet-100 bg-violet-50/40 px-5 py-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wide text-violet-700">Filter Aktif:</span>

                            @if ($selectedSearch)
                                <a href="{{ route('admin.users.index', request()->except('search')) }}" class="inline-flex items-center gap-1 rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 hover:bg-violet-200 transition-colors">
                                    Cari: "{{ $selectedSearch }}"
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedRole)
                                <a href="{{ route('admin.users.index', request()->except(['role', 'program_studi_id', 'semester'])) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 hover:bg-indigo-200 transition-colors">
                                    Role: {{ $roleOptions[$selectedRole] ?? $selectedRole }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedProgramStudi && $selectedProgramStudiLabel)
                                <a href="{{ route('admin.users.index', request()->except('program_studi_id')) }}" class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700 hover:bg-sky-200 transition-colors">
                                    Prodi: {{ $selectedProgramStudiLabel }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedSemester)
                                <a href="{{ route('admin.users.index', request()->except('semester')) }}" class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200 transition-colors">
                                    Semester: {{ $selectedSemester }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ($selectedSort !== 'name_asc')
                                <a href="{{ route('admin.users.index', request()->except('sort')) }}" class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 hover:bg-amber-200 transition-colors">
                                    Urut: {{ $sortOptions[$selectedSort] ?? 'Kustom' }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif

                            @if ((int) $perPage !== 15)
                                <a href="{{ route('admin.users.index', request()->except('per_page')) }}" class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-200 transition-colors">
                                    Per Halaman: {{ $perPage }}
                                    <i data-lucide="x" class="h-3.5 w-3.5"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="rounded-3xl border border-emerald-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(16,185,129,0.14)] overflow-hidden">
                <div class="px-6 py-3 border-b border-emerald-100/70 bg-emerald-50/60">
                    @if ($users->total() > 0)
                        <p class="text-xs font-semibold text-emerald-700">
                            Data yang terlihat sekarang: {{ $users->count() }} data, dari total {{ $users->total() }} data.
                        </p>
                        <p class="mt-1 text-xs text-emerald-600">
                            Di halaman ini menampilkan data nomor {{ $users->firstItem() }} sampai {{ $users->lastItem() }}.
                        </p>
                    @else
                        <p class="text-xs font-semibold text-emerald-700">
                            Belum ada data untuk ditampilkan.
                        </p>
                    @endif
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1060px] text-sm text-left text-slate-600">
                        <thead class="sticky top-0 z-10 text-xs text-emerald-700 uppercase bg-emerald-50/90 backdrop-blur">
                            <tr>
                                <th class="w-[72px] px-6 py-4 font-semibold tracking-wider">No</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Role</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Identifier</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="w-[220px] px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-emerald-100/60">
                            @forelse ($users as $user)
                                @php
                                    $resolvedRole = $user->resolvedSkkmRole();
                                    $roleLabel = $roleOptions[$resolvedRole] ?? strtoupper($resolvedRole);
                                    $roleBadgeClass = match ($resolvedRole) {
                                        'mahasiswa' => 'bg-sky-100 text-sky-700 border-sky-200',
                                        'dosen_pa' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'kaprodi' => 'bg-violet-100 text-violet-700 border-violet-200',
                                        'kemahasiswaan' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'super_admin' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <tr class="hover:bg-emerald-50/40 transition-colors">
                                    <td class="px-6 py-4 text-xs font-bold text-slate-500">
                                        {{ $users->firstItem() + $loop->index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-zinc-800">{{ $user->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-lg border px-2.5 py-1 text-xs font-semibold {{ $roleBadgeClass }}">
                                            {{ $roleLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($user->programStudi)
                                            <div class="font-medium text-slate-700">{{ $user->programStudi->nama }}</div>
                                            <div class="text-xs text-slate-500">{{ $user->programStudi->jenjang }}</div>
                                        @else
                                            <span class="text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $user->identifier ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        @if ($user->is_active)
                                            <span class="inline-flex rounded-lg border border-emerald-200 bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">Aktif</span>
                                        @else
                                            <span class="inline-flex rounded-lg border border-slate-200 bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="w-[220px] px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-nowrap items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex min-w-[86px] items-center justify-center gap-1 px-3 py-2 rounded-lg border border-amber-200 bg-amber-100 text-amber-800 text-xs font-semibold hover:bg-amber-200 transition-colors">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                                Edit
                                            </a>
                                            <form
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                method="POST"
                                                class="shrink-0"
                                                data-confirm
                                                data-confirm-variant="danger"
                                                data-confirm-title="Hapus User?"
                                                data-confirm-message="Data user yang dihapus tidak bisa dikembalikan. Apakah Anda yakin ingin melanjutkan?"
                                                data-confirm-approve="Ya, Hapus User"
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
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                        Belum ada data user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-emerald-100/70 bg-white/70">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const filterForm = document.getElementById('userFilterForm');
            const searchInput = document.getElementById('search');
            const roleFilter = document.getElementById('role');
            const sortFilter = document.getElementById('sort');
            const perPageFilter = document.getElementById('per_page');
            const programStudiFilterWrap = document.getElementById('programStudiFilterWrap');
            const semesterFilterWrap = document.getElementById('semesterFilterWrap');
            const programStudiFilter = document.getElementById('program_studi_id');
            const semesterFilter = document.getElementById('semester');
            const submitFilters = () => {
                if (filterForm) {
                    filterForm.requestSubmit();
                }
            };

            const debounce = (callback, delay = 400) => {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => callback(...args), delay);
                };
            };

            const toggleMahasiswaFilters = () => {
                const showMahasiswaFilters = roleFilter.value === 'mahasiswa';

                programStudiFilterWrap.classList.toggle('hidden', !showMahasiswaFilters);
                semesterFilterWrap.classList.toggle('hidden', !showMahasiswaFilters);

                if (!showMahasiswaFilters) {
                    programStudiFilter.value = '';
                    semesterFilter.value = '';
                }
            };

            toggleMahasiswaFilters();
            roleFilter.addEventListener('change', () => {
                toggleMahasiswaFilters();
                submitFilters();
            });

            if (sortFilter && filterForm) {
                sortFilter.addEventListener('change', submitFilters);
            }

            if (perPageFilter && filterForm) {
                perPageFilter.addEventListener('change', submitFilters);
            }

            if (programStudiFilter && filterForm) {
                programStudiFilter.addEventListener('change', submitFilters);
            }

            if (semesterFilter && filterForm) {
                semesterFilter.addEventListener('change', submitFilters);
            }

            if (searchInput && filterForm) {
                const debouncedSubmit = debounce(submitFilters, 450);
                searchInput.addEventListener('input', debouncedSubmit);
                searchInput.addEventListener('keydown', (event) => {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        submitFilters();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
