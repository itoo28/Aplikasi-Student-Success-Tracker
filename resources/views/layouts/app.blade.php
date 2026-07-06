<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Student Success Tracker') }}</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('brand/logo-uhb.svg') }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>
        @fluxAppearance
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
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
                        ['route' => 'skkm.monitoring.index', 'active' => 'skkm.monitoring.*', 'label' => 'Monitoring Poin SKKM', 'icon' => 'users'],
                        ['route' => 'bimbingan.dosen.index', 'active' => 'bimbingan.dosen.*', 'label' => 'Bimbingan Akademik', 'icon' => 'book-open'],
                    ],
                    'kaprodi' => [
                        ['route' => 'skkm.kaprodi.dashboard', 'active' => 'skkm.kaprodi.dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kaprodi.index', 'active' => 'skkm.kaprodi.index', 'label' => 'Monitoring Poin SKKM', 'icon' => 'chart-bar'],
                        ['route' => 'bimbingan.rekapitulasi.kaprodi', 'active' => 'bimbingan.rekapitulasi.kaprodi*', 'label' => 'Bimbingan Akademik', 'icon' => 'document-chart-bar'],
                    ],
                    'kemahasiswaan' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'skkm.kemahasiswaan.mahasiswa.index', 'active' => 'skkm.kemahasiswaan.mahasiswa.*', 'label' => 'Poin SKKM', 'icon' => 'users'],
                        ['route' => 'bimbingan.rekapitulasi.kemahasiswaan', 'active' => 'bimbingan.rekapitulasi.kemahasiswaan*', 'label' => 'Bimbingan Akademik', 'icon' => 'document-chart-bar'],
                        ['route' => 'skkm.point-rules.index', 'active' => 'skkm.point-rules.*', 'label' => 'Manajemen Poin SKKM', 'icon' => 'list-bullet'],
                    ],
                    'super_admin' => [
                        ['route' => 'dashboard', 'active' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'squares-2x2'],
                        ['route' => 'admin.users.index', 'active' => 'admin.users.*', 'label' => 'Manajemen User', 'icon' => 'users'],
                        ['route' => 'admin.fakultas.index', 'active' => 'admin.fakultas.*', 'label' => 'Manajemen Fakultas', 'icon' => 'building-office-2'],
                        ['route' => 'admin.program-studi.index', 'active' => 'admin.program-studi.*', 'label' => 'Manajemen Program Studi', 'icon' => 'book-open'],
                        ['route' => 'admin.bimbingan', 'active' => 'admin.bimbingan*', 'label' => 'Bimbingan Akademik', 'icon' => 'document-chart-bar'],
                        ['route' => 'skkm.point-rules.index', 'active' => 'skkm.point-rules.*', 'label' => 'Manajemen Poin SKKM', 'icon' => 'list-bullet'],
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

            <flux:sidebar sticky collapsible="mobile" class="bg-white border-r border-slate-200/70">
                <flux:sidebar.header>
                    <flux:sidebar.brand :href="route('dashboard')" name="SST Portal">
                        <img
                            src="{{ asset('brand/logo-uhb.svg') }}"
                            alt="{{ config('app.name', 'Student Success Tracker') }}"
                            class="h-8 w-auto object-contain"
                        />
                    </flux:sidebar.brand>
                    <flux:sidebar.collapse class="lg:hidden" />
                </flux:sidebar.header>

                <div class="px-2">
                    <div class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold text-slate-900 bg-slate-100 border border-slate-200">
                        {{ $roleLabel }}
                    </div>
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

            <flux:header class="border-b border-slate-200/70 bg-white">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

                <div class="ms-3 min-w-0 flex-1">
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <flux:heading size="lg">Dashboard</flux:heading>
                    @endif
                </div>

                <flux:dropdown position="top" align="start" class="lg:hidden">
                    <flux:profile class="mobile-profile-compact" :name="Auth::user()->name" :initials="$avatarInitials" />

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

        <!-- Global Loading Spinner Overlay -->
        <div id="global-page-loader" class="global-loader-overlay">
            <div class="relative flex flex-col items-center justify-center">
                <div class="relative flex items-center justify-center">
                    <!-- Spinner Ring -->
                    <div class="spinner-outer">
                        <div class="w-full h-full rounded-full bg-white"></div>
                    </div>
                    <!-- Stable Logo -->
                    <div class="absolute spinner-logo-container">
                        <img src="{{ asset('brand/logo-uhb.svg') }}" alt="UHB Logo" class="spinner-logo" />
                    </div>
                </div>
                <!-- Loader Text -->
                <span class="global-loader-text">Memuat data...</span>
            </div>
        </div>

        @livewireScripts
        @fluxScripts
        <script>
            if (window.lucide) {
                lucide.createIcons();
            }

            // Global Loader logic
            document.addEventListener('DOMContentLoaded', () => {
                const loader = document.getElementById('global-page-loader');
                let safetyTimeout = null;

                const showSpinner = (duration = null) => {
                    if (loader) {
                        loader.classList.add('active');
                        
                        // Clear existing safety timeout
                        if (safetyTimeout) {
                            clearTimeout(safetyTimeout);
                            safetyTimeout = null;
                        }

                        // Auto-hide safeguard
                        if (duration) {
                            safetyTimeout = setTimeout(hideSpinner, duration);
                        } else {
                            // Default safety timeout of 3 seconds for page loads
                            safetyTimeout = setTimeout(hideSpinner, 3000);
                        }
                    }
                };

                const hideSpinner = () => {
                    if (loader) {
                        loader.classList.remove('active');
                    }
                    if (safetyTimeout) {
                        clearTimeout(safetyTimeout);
                        safetyTimeout = null;
                    }
                };

                // 1. Navigation / Link Clicks
                document.addEventListener('click', (event) => {
                    const link = event.target.closest('a');
                    if (link) {
                        const href = link.getAttribute('href');
                        const target = link.getAttribute('target');
                        
                        // Ignore hash links, javascript void, download, target blank, external, or modifier clicks
                        if (href && 
                            !href.startsWith('#') && 
                            !href.startsWith('javascript:') && 
                            target !== '_blank' &&
                            !link.hasAttribute('download') &&
                            !event.ctrlKey && 
                            !event.metaKey && 
                            !event.shiftKey) {
                            
                            // Check if local link
                            const isLocal = href.startsWith('/') || href.startsWith(window.location.origin);
                            if (isLocal) {
                                showSpinner();
                            }
                        }
                    }
                });

                // 2. Form Submissions (checking that they aren't prevented by validation or custom modals)
                document.addEventListener('submit', (event) => {
                    // Let form handlers run first (e.g. confirmation modal dialogs)
                    setTimeout(() => {
                        if (!event.defaultPrevented && event.target.getAttribute('target') !== '_blank') {
                            showSpinner();
                        }
                    }, 0);
                });

                // 3. Tab Clicks / Alpine ActiveTab Switches / Local Filter Buttons
                document.addEventListener('click', (event) => {
                    const target = event.target.closest('button, [role="tab"], .filter-btn, [data-filter]');
                    if (target) {
                        const isTabButton = target.classList.contains('filter-btn') || 
                                            target.getAttribute('role') === 'tab' ||
                                            target.getAttribute('data-filter') ||
                                            (target.getAttribute('@click') && target.getAttribute('@click').includes('activeTab')) ||
                                            (target.getAttribute('x-on:click') && target.getAttribute('x-on:click').includes('activeTab'));
                        
                        if (isTabButton) {
                            showSpinner(250); // Premium visual loader transition for instant tab actions (250ms)
                        }
                    }
                });

                // 4. Livewire Hooks (if Livewire exists globally)
                if (window.Livewire) {
                    initLivewireHooks();
                } else {
                    document.addEventListener('livewire:init', () => {
                        initLivewireHooks();
                    });
                }

                function initLivewireHooks() {
                    if (window.Livewire && window.Livewire.hook) {
                        window.Livewire.hook('request', ({ respond, succeed, fail }) => {
                            showSpinner();
                            respond(() => {
                                hideSpinner();
                            });
                            succeed(() => {
                                hideSpinner();
                            });
                            fail(() => {
                                hideSpinner();
                            });
                        });
                    }
                }

                // 5. Unload & Back-Forward Cache (BFcache) recovery
                window.addEventListener('beforeunload', () => {
                    showSpinner();
                });

                window.addEventListener('pageshow', (event) => {
                    if (event.persisted) {
                        hideSpinner();
                    }
                });
            });
        </script>
        @stack('scripts')
    </body>
</html>
