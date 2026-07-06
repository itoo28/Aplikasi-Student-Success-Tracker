<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Monitoring Bimbingan Akademik') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">
        
        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Mahasiswa -->
            <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-white/10 blur-xl"></div>
                <span class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Jumlah Mahasiswa</span>
                <h3 class="mt-3 text-3xl font-extrabold text-white z-10">{{ $totalMahasiswa }}</h3>
                <p class="text-[11px] text-indigo-100 mt-1">Terdaftar bimbingan</p>
            </div>
            

            <!-- Belum Bimbingan / Peringatan (Krusial - Yellow Gradient) -->
            <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgba(245,158,11,0.30)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-white/10 blur-xl"></div>
                <span class="text-amber-100 text-xs font-semibold uppercase tracking-wider">Mahasiswa Peringatan</span>
                <h3 class="mt-3 text-3xl font-extrabold text-white z-10">{{ $belumBimbingan }}</h3>
                <p class="text-[11px] text-amber-100 mt-1">Belum melakukan bimbingan</p>
            </div>
            
            <!-- Memenuhi Syarat -->
            <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgba(37,99,235,0.35)] flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -top-6 w-20 h-20 rounded-full bg-white/10 blur-xl"></div>
                <span class="text-indigo-100 text-xs font-semibold uppercase tracking-wider">Memenuhi Syarat Administrasi</span>
                <h3 class="mt-3 text-3xl font-extrabold text-white z-10">{{ $memenuhiSyarat }}</h3>
                <p class="text-[11px] text-indigo-100 mt-1">Telah melakukan bimbingan</p>
            </div>
        </div>

        <!-- Tabel Kesamping Seperti Monitoring SKKM -->
        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col gap-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Status Bimbingan Mahasiswa</h3>
                        <p class="text-sm text-slate-500 mt-1">Memantau frekuensi bimbingan akademik di semester aktif mahasiswa.</p>
                    </div>
                </div>
                
                {{-- Form Filter --}}
                @php
                    $skkmRole = auth()->user()->resolvedSkkmRole();
                    $formAction = match ($skkmRole) {
                        'super_admin' => route('admin.bimbingan'),
                        'kaprodi' => route('bimbingan.rekapitulasi.kaprodi'),
                        'kemahasiswaan' => route('bimbingan.rekapitulasi.kemahasiswaan'),
                        default => '#',
                    };
                    $hasActiveFilters = filled($search) || filled($selectedProgramStudi) || filled($selectedSemester) || filled($selectedStatus);
                @endphp
                <form id="bimbinganFilterForm" method="GET" action="{{ $formAction }}" class="border-t border-slate-100 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        {{-- Search Input --}}
                        <div class="md:col-span-2">
                            <flux:field>
                                <flux:label for="search" class="text-xs font-semibold text-slate-650">Cari Mahasiswa</flux:label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    </span>
                                    <input
                                        type="text"
                                        id="search"
                                        name="search"
                                        value="{{ $search ?? '' }}"
                                        placeholder="Cari berdasarkan nama atau NIM..."
                                        class="block w-full pl-9 pr-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                    />
                                </div>
                            </flux:field>
                        </div>

                        {{-- Filter Prodi --}}
                        <div>
                            <flux:field>
                                <flux:label for="program_studi_id" class="text-xs font-semibold text-slate-655">Program Studi</flux:label>
                                <flux:select id="program_studi_id" name="program_studi_id" class="mt-1">
                                    <flux:select.option value="">Semua Program Studi</flux:select.option>
                                    @foreach ($programStudiOptions as $prodi)
                                        <flux:select.option value="{{ $prodi->id }}" :selected="(int) $selectedProgramStudi === (int) $prodi->id">
                                            {{ $prodi->jenjang }} {{ $prodi->nama }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>

                        {{-- Filter Semester --}}
                        <div>
                            <flux:field>
                                <flux:label for="semester" class="text-xs font-semibold text-slate-655">Semester</flux:label>
                                <flux:select id="semester" name="semester" class="mt-1">
                                    <flux:select.option value="">Semua Semester</flux:select.option>
                                    @foreach ($semesterOptions as $sem)
                                        <flux:select.option value="{{ $sem }}" :selected="(int) $selectedSemester === (int) $sem">
                                            Semester {{ $sem }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                            </flux:field>
                        </div>

                        {{-- Filter Status Bimbingan --}}
                        <div>
                            <flux:field>
                                <flux:label for="status_bimbingan" class="text-xs font-semibold text-slate-655">Status Bimbingan</flux:label>
                                <flux:select id="status_bimbingan" name="status_bimbingan" class="mt-1">
                                    <flux:select.option value="">Semua Status</flux:select.option>
                                    <flux:select.option value="sudah" :selected="$selectedStatus === 'sudah'">Sudah Memenuhi</flux:select.option>
                                    <flux:select.option value="belum" :selected="$selectedStatus === 'belum'">Belum Memenuhi</flux:select.option>
                                </flux:select>
                            </flux:field>
                        </div>
                    </div>

                    @if ($hasActiveFilters)
                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-450">Filter Aktif:</span>
                                @if ($search)
                                    <a href="{{ $formAction . '?' . http_build_query(request()->except('search')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition-colors">
                                        Cari: "{{ $search }}"
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                                @if ($selectedProgramStudi)
                                    @php
                                        $activeProdi = $programStudiOptions->firstWhere('id', (int) $selectedProgramStudi);
                                        $prodiLabel = $activeProdi ? $activeProdi->jenjang . ' ' . $activeProdi->nama : 'Prodi';
                                    @endphp
                                    <a href="{{ $formAction . '?' . http_build_query(request()->except('program_studi_id')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition-colors">
                                        Prodi: {{ $prodiLabel }}
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                                @if ($selectedSemester)
                                    <a href="{{ $formAction . '?' . http_build_query(request()->except('semester')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition-colors">
                                        Semester: {{ $selectedSemester }}
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                                @if ($selectedStatus)
                                    <a href="{{ $formAction . '?' . http_build_query(request()->except('status_bimbingan')) }}" class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-semibold text-indigo-750 hover:bg-indigo-100 transition-colors">
                                        Status Bimbingan: {{ $selectedStatus === 'sudah' ? 'Sudah Memenuhi' : 'Belum Memenuhi' }}
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </a>
                                @endif
                            </div>
                            <div>
                                <a href="{{ $formAction }}" class="text-xs font-semibold text-rose-600 hover:text-rose-750 transition-colors">
                                    Reset Semua Filter
                                </a>
                            </div>
                        </div>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-600 uppercase bg-slate-50/80">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Mahasiswa</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Semester Aktif</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Frekuensi Bimbingan</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Status Kelayakan</th>
                            <th scope="col" class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80">
                        @forelse($mahasiswaList as $mhs)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200">
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $mhs->name }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $mhs->identifier ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-semibold text-slate-800">{{ $mhs->programStudi?->nama ?? '-' }}</div>
                                    <div class="text-xs text-slate-400 mt-1">{{ $mhs->programStudi?->fakultas?->nama ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-sm font-semibold text-slate-700">{{ $mhs->semester ?? 1 }}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-sm font-bold bg-indigo-50 text-indigo-600 rounded-lg">
                                        {{ $mhs->bimbingan_semester_count }} kali
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($mhs->bimbingan_semester_count >= 1)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Memenuhi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Belum Memenuhi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $skkmRole = auth()->user()->resolvedSkkmRole();
                                        $detailRoute = match ($skkmRole) {
                                            'super_admin' => route('admin.bimbingan.detail', $mhs->id),
                                            'kaprodi' => route('bimbingan.rekapitulasi.kaprodi.detail', $mhs->id),
                                            'kemahasiswaan' => route('bimbingan.rekapitulasi.kemahasiswaan.detail', $mhs->id),
                                            default => '#',
                                        };
                                    @endphp
                                    <a href="{{ $detailRoute }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-semibold hover:bg-indigo-100 hover:text-indigo-700 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>Detail</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500 text-sm">
                                    Belum ada data mahasiswa yang terdaftar untuk direkapitulasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            const bimbinganFilterForm = document.getElementById('bimbinganFilterForm');
            const bimbinganSearchInput = document.getElementById('search');
            const programStudiInput = document.getElementById('program_studi_id');
            const semesterInput = document.getElementById('semester');
            const statusBimbinganInput = document.getElementById('status_bimbingan');

            const debounce = (callback, delay = 450) => {
                let timeoutId;
                return (...args) => {
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => callback(...args), delay);
                };
            };

            if (bimbinganFilterForm) {
                const submitFilters = () => bimbinganFilterForm.requestSubmit();

                if (programStudiInput) {
                    programStudiInput.addEventListener('change', submitFilters);
                }

                if (semesterInput) {
                    semesterInput.addEventListener('change', submitFilters);
                }

                if (statusBimbinganInput) {
                    statusBimbinganInput.addEventListener('change', submitFilters);
                }

                if (bimbinganSearchInput) {
                    const debouncedSubmit = debounce(submitFilters, 450);
                    bimbinganSearchInput.addEventListener('input', debouncedSubmit);
                    bimbinganSearchInput.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            submitFilters();
                        }
                    });
                }
            }
        </script>
    @endpush
</x-app-layout>
