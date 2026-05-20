<x-app-layout>
    @php
        $profileUser = Auth::user();
        $role = $profileUser->resolvedSkkmRole();

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

        $avatarInitials = \Illuminate\Support\Str::of($profileUser->name)
            ->explode(' ')
            ->filter()
            ->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))
            ->take(2)
            ->implode('');

        $programStudi = $profileUser->programStudi
            ? trim(($profileUser->programStudi->jenjang ? $profileUser->programStudi->jenjang . ' ' : '') . $profileUser->programStudi->nama)
            : '-';
    @endphp

    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <flux:heading size="xl" level="1">Profil Akun</flux:heading>
                <flux:text class="mt-1">Kelola keamanan akun dan informasi kontak Anda.</flux:text>
            </div>
            <flux:badge color="indigo">{{ $roleLabel }}</flux:badge>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm">
                <div class="flex items-start gap-4">
                    <flux:avatar :name="$profileUser->name" :initials="$avatarInitials" size="xl" />
                    <div class="min-w-0">
                        <flux:heading size="lg" class="truncate">{{ $profileUser->name }}</flux:heading>
                        <flux:text class="mt-1 truncate">{{ $profileUser->email }}</flux:text>
                    </div>
                </div>

                <flux:separator variant="subtle" class="my-4" />

                <div class="space-y-3">
                    <div class="flex items-center justify-between gap-3">
                        <flux:text size="sm">Status Email</flux:text>
                        @if ($profileUser->hasVerifiedEmail())
                            <flux:badge color="emerald">Terverifikasi</flux:badge>
                        @else
                            <flux:badge color="amber">Belum Verifikasi</flux:badge>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <flux:text size="sm">Status Akun</flux:text>
                        @if ($profileUser->is_active)
                            <flux:badge color="emerald">Aktif</flux:badge>
                        @else
                            <flux:badge color="zinc">Nonaktif</flux:badge>
                        @endif
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm lg:col-span-2">
                <div class="mb-4 flex items-center gap-2">
                    <flux:icon.lock-closed variant="mini" class="text-zinc-500" />
                    <flux:heading size="lg">Identitas Akun (Read-only)</flux:heading>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:input :label="$identifierLabel" type="text" :value="$profileUser->identifier ?: '-'" readonly variant="filled" copyable />
                    <flux:input label="Role" type="text" :value="$roleLabel" readonly variant="filled" />
                    <flux:input label="Program Studi" type="text" :value="$programStudi" readonly variant="filled" />
                    <flux:input label="Nama Lengkap" type="text" :value="$profileUser->name" readonly variant="filled" />

                    @if ($role === 'mahasiswa')
                        <flux:input label="Semester" type="text" :value="$profileUser->semester ? (string) $profileUser->semester : '-'" readonly variant="filled" />
                        <flux:input label="Dosen PA" type="text" :value="$profileUser->lecturer?->name ?: '-'" readonly variant="filled" />
                    @endif
                </div>
            </div>
        </div>

        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="pt-2">
                @livewire('profile.update-password-form')
            </div>
        @endif
    </div>
</x-app-layout>
