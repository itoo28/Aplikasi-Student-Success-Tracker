<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Student Success Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
        <flux:accent color="indigo" class="min-h-screen min-h-dvh">
            <x-banner />

            @php
                $skkmRole = auth()->user()->resolvedSkkmRole();
                $roleLabel = match ($skkmRole) {
                    'mahasiswa' => 'Mahasiswa',
                    'dosen_pa' => 'Dosen PA',
                    'kaprodi' => 'Kaprodi',
                    'kemahasiswaan' => 'Kemahasiswaan',
                    'super_admin' => 'Super Admin',
                    default => 'Pengguna',
                };

                $avatarInitials = \Illuminate\Support\Str::of(Auth::user()->name)
                    ->explode(' ')
                    ->filter()
                    ->map(fn ($part) => \Illuminate\Support\Str::substr($part, 0, 1))
                    ->take(2)
                    ->implode('');

                $navItems = match ($skkmRole) {
                    'mahasiswa' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.index', 'active' => 'skkm.*', 'label' => 'Poin SKKM', 'icon' => 'academic-cap'],
                    ],
                    'dosen_pa' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.verifikasi.index', 'active' => 'skkm.verifikasi.*', 'label' => 'Antrean SKKM', 'icon' => 'clipboard-document-check'],
                        ['route' => 'skkm.monitoring.index', 'active' => 'skkm.monitoring.*', 'label' => 'Data Mahasiswa', 'icon' => 'users'],
                    ],
                    'kaprodi' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kaprodi.index', 'active' => 'skkm.kaprodi.index', 'label' => 'Monitoring SKKM', 'icon' => 'chart-bar'],
                        ['route' => 'skkm.kaprodi.mahasiswa.index', 'active' => 'skkm.kaprodi.mahasiswa.*', 'label' => 'Data Mahasiswa', 'icon' => 'users'],
                    ],
                    'kemahasiswaan' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kemahasiswaan.index', 'active' => 'skkm.kemahasiswaan.*', 'label' => 'Monitoring SKKM', 'icon' => 'chart-bar-square'],
                    ],
                    'super_admin' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Panel Admin', 'icon' => 'shield-check'],
                        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'CRUD User', 'icon' => 'users'],
                        ['route' => 'admin.fakultas.index', 'active' => 'admin.fakultas.*', 'label' => 'CRUD Fakultas', 'icon' => 'building-office-2'],
                        ['route' => 'admin.program-studi.index', 'active' => 'admin.program-studi.*', 'label' => 'CRUD Program Studi', 'icon' => 'book-open'],
                    ],
                    default => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                    ],
                };
            @endphp

            <flux:sidebar sticky collapsible="mobile" class="bg-white border-r border-zinc-200/70">
                <flux:sidebar.header>
                    <flux:sidebar.brand :href="route('dashboard')" name="SST Portal">
                        <div class="flex size-6 items-center justify-center rounded-md bg-indigo-600 text-white">
                            <flux:icon.academic-cap variant="mini" />
                        </div>
                    </flux:sidebar.brand>
                    <flux:sidebar.collapse class="lg:hidden" />
                </flux:sidebar.header>

                <div class="px-2">
                    <flux:badge size="sm" color="indigo">{{ $roleLabel }}</flux:badge>
                </div>

                <flux:sidebar.nav>
                    @foreach ($navItems as $item)
                        <flux:sidebar.item
                            :href="route($item['route'])"
                            :icon="$item['icon']"
                            :current="request()->routeIs($item['active'])"
                        >
                            {{ $item['label'] }}
                        </flux:sidebar.item>
                    @endforeach
                </flux:sidebar.nav>

                <flux:sidebar.spacer />

                <flux:dropdown position="top" align="start" class="max-lg:hidden">
                    <flux:sidebar.profile :name="Auth::user()->name" :initials="$avatarInitials" />

                    <flux:menu>
                        <flux:menu.item :href="route('profile.show')" icon="user">
                            Profil
                        </flux:menu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle" variant="danger">
                                Keluar
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:sidebar>

            <flux:header class="border-b border-zinc-200/70 bg-white">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

                <div class="ms-3 min-w-0 flex-1">
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <flux:heading size="lg">Dashboard</flux:heading>
                    @endif
                </div>

                <flux:dropdown position="top" align="start" class="lg:hidden">
                    <flux:profile :name="Auth::user()->name" :initials="$avatarInitials" />

                    <flux:menu>
                        <flux:menu.item :href="route('profile.show')" icon="user">
                            Profil
                        </flux:menu.item>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle" variant="danger">
                                Keluar
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </flux:header>

            <flux:main>
                {{ $slot }}
            </flux:main>
        </flux:accent>

        @stack('modals')

        @livewireScripts
        @fluxScripts
        <script>
            if (window.lucide) {
                lucide.createIcons();
            }
        </script>
        @stack('scripts')
    </body>
</html>
