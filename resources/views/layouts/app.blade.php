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
                        ['route' => 'bimbingan.mahasiswa.index', 'active' => 'bimbingan.mahasiswa.*', 'label' => 'Bimbingan Akademik', 'icon' => 'book-open'],
                    ],
                    'dosen_pa' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.verifikasi.index', 'active' => 'skkm.verifikasi.*', 'label' => 'Antrean SKKM', 'icon' => 'clipboard-document-check'],
                        ['route' => 'skkm.monitoring.index', 'active' => 'skkm.monitoring.*', 'label' => 'Data Mahasiswa', 'icon' => 'users'],
                        ['route' => 'bimbingan.dosen.index', 'active' => 'bimbingan.dosen.*', 'label' => 'Bimbingan Akademik', 'icon' => 'book-open'],
                    ],
                    'kaprodi' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kaprodi.index', 'active' => 'skkm.kaprodi.index', 'label' => 'Monitoring SKKM', 'icon' => 'chart-bar'],
                        ['route' => 'skkm.kaprodi.mahasiswa.index', 'active' => 'skkm.kaprodi.mahasiswa.*', 'label' => 'Data Mahasiswa', 'icon' => 'users'],
                        ['route' => 'bimbingan.rekapitulasi.kaprodi', 'active' => 'bimbingan.rekapitulasi.kaprodi', 'label' => 'Rekap Bimbingan', 'icon' => 'document-chart-bar'],
                    ],
                    'kemahasiswaan' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kemahasiswaan.index', 'active' => 'skkm.kemahasiswaan.*', 'label' => 'Monitoring SKKM', 'icon' => 'chart-bar-square'],
                        ['route' => 'bimbingan.rekapitulasi.kemahasiswaan', 'active' => 'bimbingan.rekapitulasi.kemahasiswaan', 'label' => 'Rekap Bimbingan', 'icon' => 'document-chart-bar'],
                    ],
                    'super_admin' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'label' => 'Panel Admin', 'icon' => 'shield-check'],
                        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Manajemen User', 'icon' => 'users'],
                        ['route' => 'admin.fakultas.index', 'active' => 'admin.fakultas.*', 'label' => 'Manajemen Fakultas', 'icon' => 'building-office-2'],
                        ['route' => 'admin.program-studi.index', 'active' => 'admin.program-studi.*', 'label' => 'Manajemen Program Studi', 'icon' => 'book-open'],
                    ],
                    default => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                    ],
                };

                $toastNotifications = [];

                if (session()->has('success')) {
                    $toastNotifications[] = [
                        'type' => 'success',
                        'title' => 'Berhasil',
                        'message' => (string) session('success'),
                        'icon' => 'circle-check-big',
                    ];
                }

                if (session()->has('error')) {
                    $toastNotifications[] = [
                        'type' => 'error',
                        'title' => 'Terjadi Kesalahan',
                        'message' => (string) session('error'),
                        'icon' => 'octagon-alert',
                    ];
                }

                if (session()->has('warning')) {
                    $toastNotifications[] = [
                        'type' => 'warning',
                        'title' => 'Perhatian',
                        'message' => (string) session('warning'),
                        'icon' => 'triangle-alert',
                    ];
                }
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

        <div
            id="appConfirmDialog"
            class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6"
            role="dialog"
            aria-modal="true"
            aria-labelledby="appConfirmDialogTitle"
            aria-describedby="appConfirmDialogMessage"
        >
            <div data-confirm-overlay class="absolute inset-0 bg-slate-950/45 backdrop-blur-sm confirm-dialog-backdrop"></div>

            <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-rose-100 bg-white shadow-[0_24px_64px_rgba(15,23,42,0.32)] confirm-dialog-panel">
                <div id="appConfirmDialogAccent" class="h-1.5 w-full bg-gradient-to-r from-rose-500 via-amber-400 to-orange-500"></div>

                <div class="p-6 sm:p-7">
                    <div id="appConfirmDialogIconWrap" class="inline-flex items-center justify-center rounded-2xl bg-rose-100 p-3 text-rose-700">
                        <i data-lucide="triangle-alert" class="size-5"></i>
                    </div>

                    <h3 id="appConfirmDialogTitle" class="mt-4 text-lg font-bold text-slate-900">Konfirmasi Tindakan</h3>
                    <p id="appConfirmDialogMessage" class="mt-2 text-sm leading-relaxed text-slate-600">
                        Apakah Anda yakin ingin melanjutkan tindakan ini?
                    </p>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            data-confirm-cancel
                            class="inline-flex min-w-[96px] items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            data-confirm-approve
                            class="inline-flex min-w-[144px] items-center justify-center rounded-xl bg-gradient-to-r from-rose-600 to-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_10px_22px_rgba(244,63,94,0.35)] transition-all hover:from-rose-700 hover:to-orange-600"
                        >
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($toastNotifications))
            <div id="appToastRegion" class="fixed right-4 top-4 z-[95] flex w-[min(92vw,380px)] flex-col gap-3 pointer-events-none sm:right-6 sm:top-6">
                @foreach ($toastNotifications as $toast)
                    @php
                        $isSuccess = $toast['type'] === 'success';
                        $isError = $toast['type'] === 'error';
                        $toastRootClass = $isSuccess
                            ? 'border-emerald-100/90 bg-emerald-50/95 shadow-[0_14px_36px_rgba(16,185,129,0.24)]'
                            : ($isError
                                ? 'border-rose-100/90 bg-rose-50/95 shadow-[0_14px_36px_rgba(244,63,94,0.24)]'
                                : 'border-amber-100/90 bg-amber-50/95 shadow-[0_14px_36px_rgba(245,158,11,0.24)]');
                        $toastBadgeClass = $isSuccess
                            ? 'bg-emerald-100 text-emerald-700'
                            : ($isError
                                ? 'bg-rose-100 text-rose-700'
                                : 'bg-amber-100 text-amber-700');
                        $toastTitleClass = $isSuccess
                            ? 'text-emerald-900'
                            : ($isError
                                ? 'text-rose-900'
                                : 'text-amber-900');
                        $toastMessageClass = $isSuccess
                            ? 'text-emerald-800/90'
                            : ($isError
                                ? 'text-rose-800/90'
                                : 'text-amber-800/90');
                        $toastCloseClass = $isSuccess
                            ? 'text-emerald-600 hover:bg-emerald-100'
                            : ($isError
                                ? 'text-rose-600 hover:bg-rose-100'
                                : 'text-amber-600 hover:bg-amber-100');
                    @endphp

                    <div
                        data-toast
                        data-toast-duration="4800"
                        role="status"
                        class="pointer-events-auto app-toast rounded-2xl border px-4 py-3 backdrop-blur-sm {{ $toastRootClass }}"
                    >
                        <div class="flex items-start gap-3">
                            <div class="mt-0.5 inline-flex size-8 shrink-0 items-center justify-center rounded-xl {{ $toastBadgeClass }}">
                                <i data-lucide="{{ $toast['icon'] }}" class="size-4"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-bold {{ $toastTitleClass }}">{{ $toast['title'] }}</p>
                                <p class="mt-1 text-sm leading-relaxed {{ $toastMessageClass }}">{{ $toast['message'] }}</p>
                            </div>
                            <button
                                type="button"
                                data-toast-close
                                class="inline-flex size-7 shrink-0 items-center justify-center rounded-lg transition-colors {{ $toastCloseClass }}"
                                aria-label="Tutup notifikasi"
                            >
                                <i data-lucide="x" class="size-4"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

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
