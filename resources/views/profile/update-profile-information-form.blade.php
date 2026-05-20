<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informasi Akun') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Anda hanya bisa mengubah email. Data identitas akademik dikunci dan dikelola oleh admin.') }}
    </x-slot>

    <x-slot name="form">
        @php
            $role = $this->user->resolvedSkkmRole();
            $roleLabel = match ($role) {
                'mahasiswa' => 'Mahasiswa',
                'dosen_pa' => 'Dosen PA',
                'kaprodi' => 'Kaprodi',
                'kemahasiswaan' => 'Kemahasiswaan',
                'super_admin' => 'Super Admin',
                default => 'Pengguna',
            };

            $identifierLabel = match ($role) {
                'mahasiswa' => 'NIM',
                'dosen_pa', 'kaprodi' => 'NIDN',
                default => 'Identifier',
            };

            $programStudi = $this->user->programStudi
                ? trim(($this->user->programStudi->jenjang ? $this->user->programStudi->jenjang . ' ' : '') . $this->user->programStudi->nama)
                : '-';
        @endphp

        <div class="col-span-6 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-amber-600">
                    <flux:icon.lock-closed variant="mini" />
                </div>
                <div class="text-sm text-amber-800">
                    <p class="font-semibold">Data identitas dikunci</p>
                    <p class="mt-1">
                        Nama, role, {{ strtolower($identifierLabel) }}, dan informasi akademik tidak bisa diubah dari halaman profil.
                    </p>
                </div>
            </div>
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="profile_name" value="{{ __('Nama') }}" />
            <x-input id="profile_name" type="text" :value="$this->user->name" readonly variant="filled" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="profile_role" value="{{ __('Role') }}" />
            <x-input id="profile_role" type="text" :value="$roleLabel" readonly variant="filled" />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="profile_identifier" :value="$identifierLabel" />
            <x-input id="profile_identifier" type="text" :value="$this->user->identifier ?: '-'" readonly variant="filled" copyable />
        </div>

        <div class="col-span-6 sm:col-span-3">
            <x-label for="profile_program_studi" value="{{ __('Program Studi') }}" />
            <x-input id="profile_program_studi" type="text" :value="$programStudi" readonly variant="filled" />
        </div>

        @if ($role === 'mahasiswa')
            <div class="col-span-6 sm:col-span-3">
                <x-label for="profile_semester" value="{{ __('Semester') }}" />
                <x-input id="profile_semester" type="text" :value="$this->user->semester ? (string) $this->user->semester : '-'" readonly variant="filled" />
            </div>

            <div class="col-span-6 sm:col-span-3">
                <x-label for="profile_dosen_pa" value="{{ __('Dosen PA') }}" />
                <x-input id="profile_dosen_pa" type="text" :value="$this->user->lecturer?->name ?: '-'" readonly variant="filled" />
            </div>
        @endif

        <div class="col-span-6">
            <flux:separator variant="subtle" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="mt-2 text-sm text-zinc-600">
                    {{ __('Alamat email Anda belum diverifikasi.') }}

                    <button type="button" class="ms-1 font-medium text-indigo-600 hover:text-indigo-800" wire:click.prevent="sendEmailVerification">
                        {{ __('Kirim ulang email verifikasi') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 text-sm font-medium text-emerald-600">
                        {{ __('Link verifikasi baru sudah dikirim ke email Anda.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3 text-emerald-600" on="saved">
            {{ __('Email berhasil diperbarui.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled">
            {{ __('Simpan Email') }}
        </x-button>
    </x-slot>
</x-form-section>
