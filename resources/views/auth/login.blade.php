<x-guest-layout>
    <div class="mx-auto flex min-h-screen w-full max-w-md items-center px-5 py-12">
        <div class="w-full rounded-3xl border border-zinc-200/70 bg-white/95 p-8 shadow-xl shadow-zinc-200/50 backdrop-blur">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-600/30">
                    <flux:icon.academic-cap />
                </div>
                <flux:heading size="xl" level="1">SST Portal</flux:heading>
                <flux:text class="mt-2">Masuk ke Student Success Tracker</flux:text>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <flux:field>
                    <flux:label for="role">Masuk Sebagai</flux:label>
                    <flux:select id="role" name="role" required>
                        <flux:select.option value="mahasiswa" @selected(old('role', 'mahasiswa') === 'mahasiswa')>Mahasiswa</flux:select.option>
                        <flux:select.option value="dosen_pa" @selected(old('role') === 'dosen_pa')>Dosen PA</flux:select.option>
                        <flux:select.option value="kaprodi" @selected(old('role') === 'kaprodi')>Kaprodi</flux:select.option>
                        <flux:select.option value="kemahasiswaan" @selected(old('role') === 'kemahasiswaan')>Kemahasiswaan</flux:select.option>
                        <flux:select.option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</flux:select.option>
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label id="label-identifier" for="email">NIM / Email</flux:label>
                    <flux:input
                        id="email"
                        name="email"
                        type="text"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="Masukkan NIM atau Email"
                    />
                </flux:field>

                <flux:field>
                    <div class="mb-2 flex items-center justify-between">
                        <flux:label for="password">Password</flux:label>
                        @if (Route::has('password.request'))
                            <flux:link :href="route('password.request')" variant="subtle">Lupa Password?</flux:link>
                        @endif
                    </div>
                    <flux:input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        viewable
                        placeholder="Masukkan password"
                    />
                </flux:field>

                <div class="flex items-center justify-between">
                    <flux:field variant="inline">
                        <flux:checkbox id="remember_me" name="remember" />
                        <flux:label for="remember_me">Ingat saya</flux:label>
                    </flux:field>
                </div>

                <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="w-full">
                    Masuk ke Sistem
                </flux:button>
            </form>

            <p class="mt-8 text-center text-xs text-zinc-500">&copy; {{ date('Y') }} Student Success Tracker</p>
        </div>
    </div>

    @push('scripts')
        <script>
            const roleInput = document.getElementById('role');
            const identifierLabel = document.getElementById('label-identifier');
            const identifierInput = document.getElementById('email');

            const updateRoleLabel = (role) => {
                if (role === 'mahasiswa') {
                    identifierLabel.textContent = 'NIM / Email';
                    identifierInput.placeholder = 'Masukkan NIM atau Email';
                    return;
                }

                if (role === 'dosen_pa' || role === 'kaprodi') {
                    identifierLabel.textContent = 'NIDN / Email';
                    identifierInput.placeholder = 'Masukkan NIDN atau Email';
                    return;
                }

                identifierLabel.textContent = 'Email / Identifier';
                identifierInput.placeholder = 'Masukkan Email atau Identifier';
            };

            updateRoleLabel(roleInput.value);
            roleInput.addEventListener('change', () => updateRoleLabel(roleInput.value));
        </script>
    @endpush
</x-guest-layout>
