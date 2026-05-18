<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.index') }}" class="text-slate-400 hover:text-slate-600">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ $isEdit ? 'Edit User' : 'Tambah User Baru' }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-8">
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" class="space-y-6">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nama</label>
                        <input id="name" type="text" name="name" required value="{{ old('name', $user->name) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input id="email" type="email" name="email" required value="{{ old('email', $user->email) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="identifier" class="block text-sm font-semibold text-slate-700 mb-2">Identifier (NIM/NIDN/Kode)</label>
                        <input id="identifier" type="text" name="identifier" value="{{ old('identifier', $user->identifier) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="skkm_role" class="block text-sm font-semibold text-slate-700 mb-2">Role SKKM</label>
                        <select id="skkm_role" name="skkm_role" required class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($roleOptions as $value => $label)
                                <option value="{{ $value }}" @selected(old('skkm_role', $user->resolvedSkkmRole()) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="program_studi_id" class="block text-sm font-semibold text-slate-700 mb-2">Program Studi</label>
                        <select id="program_studi_id" name="program_studi_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">- Pilih Program Studi -</option>
                            @foreach ($programStudis as $prodi)
                                <option value="{{ $prodi->id }}" @selected((string) old('program_studi_id', $user->program_studi_id) === (string) $prodi->id)>
                                    {{ $prodi->jenjang }} {{ $prodi->nama }} ({{ $prodi->fakultas?->nama }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="jenjang_studi" class="block text-sm font-semibold text-slate-700 mb-2">Jenjang Studi</label>
                        <select id="jenjang_studi" name="jenjang_studi" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="" @selected(!old('jenjang_studi', $user->jenjang_studi))>- Pilih Jenjang -</option>
                            <option value="S1" @selected(old('jenjang_studi', $user->jenjang_studi) === 'S1')>S1</option>
                            <option value="D4" @selected(old('jenjang_studi', $user->jenjang_studi) === 'D4')>D4</option>
                            <option value="D3" @selected(old('jenjang_studi', $user->jenjang_studi) === 'D3')>D3</option>
                        </select>
                    </div>
                </div>

                <div id="studentFields" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="semester" class="block text-sm font-semibold text-slate-700 mb-2">Semester Aktif</label>
                        <input id="semester" type="number" min="1" max="14" name="semester" value="{{ old('semester', $user->semester) }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="lecturer_id" class="block text-sm font-semibold text-slate-700 mb-2">Dosen PA</label>
                        <select id="lecturer_id" name="lecturer_id" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">- Pilih Dosen PA -</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}" @selected((string) old('lecturer_id', $user->lecturer_id) === (string) $lecturer->id)>{{ $lecturer->name }} ({{ $lecturer->identifier }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Password {{ $isEdit ? '(opsional)' : '' }}</label>
                        <input id="password" type="password" name="password" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-700">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" @checked((int) old('is_active', $user->is_active ?? 1) === 1) class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            User aktif
                        </label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold">{{ $isEdit ? 'Simpan Perubahan' : 'Tambah User' }}</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const roleInput = document.getElementById('skkm_role');
        const studentFields = document.getElementById('studentFields');

        const toggleStudentFields = () => {
            const role = roleInput.value;
            if (role === 'mahasiswa') {
                studentFields.classList.remove('hidden');
            } else {
                studentFields.classList.add('hidden');
            }
        };

        toggleStudentFields();
        roleInput.addEventListener('change', toggleStudentFields);
    </script>
</x-app-layout>
