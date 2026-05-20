<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <flux:button :href="route('admin.users.index')" variant="ghost" size="sm" icon="arrow-left" />
            <flux:heading size="xl" level="1">{{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}</flux:heading>
        </div>
    </x-slot>

    <div class="max-w-5xl">
        <div class="rounded-2xl border border-zinc-200/70 bg-white p-6 shadow-sm">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
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
                                <flux:select.option value="{{ $value }}" @selected(old('skkm_role', $user->resolvedSkkmRole()) === $value)>
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
                                <flux:select.option value="{{ $prodi->id }}" @selected((string) old('program_studi_id', $user->program_studi_id) === (string) $prodi->id)>
                                    {{ $prodi->jenjang }} {{ $prodi->nama }} ({{ $prodi->fakultas?->nama }})
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <flux:field>
                        <flux:label for="jenjang_studi">Jenjang Studi</flux:label>
                        <flux:select id="jenjang_studi" name="jenjang_studi">
                            <flux:select.option value="" @selected(!old('jenjang_studi', $user->jenjang_studi))>- Pilih Jenjang -</flux:select.option>
                            <flux:select.option value="S1" @selected(old('jenjang_studi', $user->jenjang_studi) === 'S1')>S1</flux:select.option>
                            <flux:select.option value="D4" @selected(old('jenjang_studi', $user->jenjang_studi) === 'D4')>D4</flux:select.option>
                            <flux:select.option value="D3" @selected(old('jenjang_studi', $user->jenjang_studi) === 'D3')>D3</flux:select.option>
                        </flux:select>
                    </flux:field>
                </div>

                <div id="studentFields" class="grid grid-cols-1 gap-5 md:grid-cols-2">
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
                                <flux:select.option value="{{ $lecturer->id }}" @selected((string) old('lecturer_id', $user->lecturer_id) === (string) $lecturer->id)>
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
                                @checked((int) old('is_active', $user->is_active ?? 1) === 1)
                            />
                            <flux:label for="is_active">User aktif</flux:label>
                        </flux:field>
                    </div>
                </div>

                <flux:separator variant="subtle" />

                <div class="flex items-center justify-end gap-2">
                    <flux:button :href="route('admin.users.index')" variant="ghost">Batal</flux:button>
                    <flux:button type="submit" variant="primary">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Tambah User' }}
                    </flux:button>
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
