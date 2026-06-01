<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-lg bg-violet-100 p-2 text-violet-700 hover:bg-violet-200 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="space-y-1">
                <span class="inline-flex items-center rounded-lg bg-violet-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-violet-700">Manajemen User</span>
                <flux:heading size="xl" level="1">{{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}</flux:heading>
            </div>
        </div>
    </x-slot>

    <div class="relative isolate max-w-5xl overflow-hidden rounded-[2rem] border border-violet-100/80 bg-gradient-to-br from-violet-50 via-indigo-50 to-sky-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-6 h-52 w-52 rounded-full bg-violet-200/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-20 bottom-0 h-56 w-56 rounded-full bg-indigo-200/35 blur-3xl"></div>

        <div class="relative rounded-2xl border border-violet-100/80 bg-white/90 p-6 shadow-[0_8px_30px_rgb(124,58,237,0.14)] backdrop-blur-sm">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-100/90 px-4 py-3 text-sm font-medium text-rose-800">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <flux:input
                        id="name"
                        name="name"
                        label="Nama"
                        type="text"
                        required
                        :value="old('name', $user->name)"
                    />

                    <flux:input
                        id="email"
                        name="email"
                        label="Email"
                        type="email"
                        required
                        :value="old('email', $user->email)"
                    />

                    <flux:input
                        id="identifier"
                        name="identifier"
                        label="Identifier (NIM/NIDN/Kode)"
                        type="text"
                        :value="old('identifier', $user->identifier)"
                    />

                    <flux:field>
                        <flux:label for="skkm_role">Role SKKM</flux:label>
                        <flux:select id="skkm_role" name="skkm_role" required>
                            @foreach ($roleOptions as $value => $label)
                                <flux:select.option value="{{ $value }}" :selected="old('skkm_role', $user->resolvedSkkmRole()) === $value">
                                    {{ $label }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <flux:field>
                        <flux:label for="program_studi_id">Program Studi</flux:label>
                        <flux:select id="program_studi_id" name="program_studi_id">
                            <flux:select.option value="">- Pilih Program Studi -</flux:select.option>
                            @foreach ($programStudis as $prodi)
                                <flux:select.option value="{{ $prodi->id }}" :selected="(string) old('program_studi_id', $user->program_studi_id) === (string) $prodi->id">
                                    {{ $prodi->jenjang }} {{ $prodi->nama }} ({{ $prodi->fakultas?->nama }})
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <flux:field>
                        <flux:label for="jenjang_studi">Jenjang Studi</flux:label>
                        <flux:select id="jenjang_studi" name="jenjang_studi">
                            <flux:select.option value="" :selected="!old('jenjang_studi', $user->jenjang_studi)">- Pilih Jenjang -</flux:select.option>
                            <flux:select.option value="S1" :selected="old('jenjang_studi', $user->jenjang_studi) === 'S1'">S1</flux:select.option>
                            <flux:select.option value="D4" :selected="old('jenjang_studi', $user->jenjang_studi) === 'D4'">D4</flux:select.option>
                            <flux:select.option value="D3" :selected="old('jenjang_studi', $user->jenjang_studi) === 'D3'">D3</flux:select.option>
                        </flux:select>
                    </flux:field>
                </div>

                <div id="studentFields" class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <flux:input
                        id="phone_number"
                        name="phone_number"
                        label="Nomor HP / WhatsApp"
                        type="tel"
                        placeholder="Contoh: 081234567890"
                        :value="old('phone_number', $user->phone_number)"
                    />

                    <flux:input
                        id="semester"
                        name="semester"
                        label="Semester Aktif"
                        type="number"
                        min="1"
                        max="14"
                        :value="old('semester', $user->semester)"
                    />

                    <flux:field>
                        <flux:label for="lecturer_id">Dosen PA</flux:label>
                        <flux:select id="lecturer_id" name="lecturer_id">
                            <flux:select.option value="">- Pilih Dosen PA -</flux:select.option>
                            @foreach ($lecturers as $lecturer)
                                <flux:select.option value="{{ $lecturer->id }}" :selected="(string) old('lecturer_id', $user->lecturer_id) === (string) $lecturer->id">
                                    {{ $lecturer->name }} ({{ $lecturer->identifier }})
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <flux:input
                        id="password"
                        name="password"
                        :label="$isEdit ? 'Password (opsional)' : 'Password'"
                        type="password"
                        viewable
                    />

                    <div class="flex items-end">
                        <flux:field variant="inline">
                            <input type="hidden" name="is_active" value="0">
                            <flux:checkbox
                                id="is_active"
                                name="is_active"
                                value="1"
                                :checked="(int) old('is_active', $user->is_active ?? 1) === 1"
                            />
                            <flux:label for="is_active">User aktif</flux:label>
                        </flux:field>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:from-violet-700 hover:to-indigo-700 shadow-[0_8px_18px_rgb(124,58,237,0.30)] transition-all">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const roleInput = document.getElementById('skkm_role');
            const studentFields = document.getElementById('studentFields');

            const toggleStudentFields = () => {
                studentFields.classList.toggle('hidden', roleInput.value !== 'mahasiswa');
            };

            toggleStudentFields();
            roleInput.addEventListener('change', toggleStudentFields);
        </script>
    @endpush
</x-app-layout>
