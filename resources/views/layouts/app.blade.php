<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Student Success Tracker') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-indigo-100 selection:text-indigo-900">
        <x-banner />

        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-slate-900 text-white flex flex-col fixed inset-y-0 left-0 z-50 shadow-xl">
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
                @endphp

                <div class="h-20 flex items-center px-6 bg-slate-950/50 border-b border-slate-800">
                    <div class="bg-indigo-500/20 p-2.5 rounded-xl mr-3">
                        <i data-lucide="graduation-cap" class="w-6 h-6 text-indigo-400"></i>
                    </div>
                    <div>
                        <div class="font-bold text-lg tracking-wide leading-tight">SST Portal</div>
                        <div class="text-xs text-slate-400 font-medium tracking-wider uppercase">{{ $roleLabel }}</div>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-8 space-y-2.5 overflow-y-auto">
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    @if($skkmRole === 'mahasiswa')
                    <a href="{{ route('skkm.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="award" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Poin SKKM</span>
                    </a>
                    <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white font-medium rounded-2xl transition-all duration-200 group">
                        <i data-lucide="book-open" class="w-5 h-5 mr-3 text-slate-500 group-hover:text-indigo-400 transition-colors"></i>
                        <span>Bimbingan</span>
                    </a>
                    @elseif($skkmRole === 'dosen_pa')
                    <a href="{{ route('skkm.verifikasi.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.verifikasi.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="check-square" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.verifikasi.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Antrean SKKM</span>
                    </a>
                    <a href="{{ route('skkm.monitoring.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.monitoring.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="users" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.monitoring.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Data Mahasiswa</span>
                    </a>
                    @elseif($skkmRole === 'kaprodi')
                    <a href="{{ route('skkm.kaprodi.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.kaprodi.index') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="clipboard-check" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.kaprodi.index') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Validasi Kaprodi</span>
                    </a>
                    <a href="{{ route('skkm.kaprodi.mahasiswa.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.kaprodi.mahasiswa.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="users" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.kaprodi.mahasiswa.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Data Mahasiswa</span>
                    </a>
                    @elseif($skkmRole === 'kemahasiswaan')
                    <a href="{{ route('skkm.kemahasiswaan.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('skkm.kemahasiswaan.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="shield-check" class="w-5 h-5 mr-3 {{ request()->routeIs('skkm.kemahasiswaan.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Validasi Akhir</span>
                    </a>
                    @elseif($skkmRole === 'super_admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="shield" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>Panel Admin</span>
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="users-cog" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>CRUD User</span>
                    </a>
                    <a href="{{ route('admin.fakultas.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('admin.fakultas.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="building-2" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.fakultas.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>CRUD Fakultas</span>
                    </a>
                    <a href="{{ route('admin.program-studi.index') }}" class="flex items-center px-4 py-3.5 {{ request()->routeIs('admin.program-studi.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20 font-semibold' : 'text-slate-400 hover:bg-slate-800 hover:text-white font-medium' }} rounded-2xl transition-all duration-200 group">
                        <i data-lucide="book-copy" class="w-5 h-5 mr-3 {{ request()->routeIs('admin.program-studi.*') ? 'text-white' : 'text-slate-500 group-hover:text-indigo-400 transition-colors' }}"></i>
                        <span>CRUD Program Studi</span>
                    </a>
                    @endif
                </nav>
                <div class="p-5 border-t border-slate-800 bg-slate-950/30">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center justify-center w-full px-4 py-3 text-slate-300 hover:bg-rose-500/10 hover:text-rose-400 font-semibold rounded-2xl transition-all duration-200 border border-slate-800 hover:border-rose-500/20">
                            <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                            <span>Keluar Sistem</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col ml-64 min-w-0 bg-slate-50 min-h-screen">
                <!-- Top Navigation/Header -->
                <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 flex items-center justify-between px-10 sticky top-0 z-40 shadow-sm">
                    <div class="flex items-center text-slate-800">
                        @if (isset($header))
                            {{ $header }}
                        @else
                            <h2 class="font-bold text-2xl tracking-tight">Dashboard</h2>
                        @endif
                    </div>
                    <div class="flex items-center space-x-5">
                        <button class="relative p-2 text-slate-400 hover:text-indigo-600 transition-colors rounded-full hover:bg-indigo-50">
                            <i data-lucide="bell" class="w-5 h-5"></i>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                        </button>
                        <div class="flex items-center space-x-4 pl-5 border-l border-slate-200">
                            <div class="flex flex-col text-right">
                                <span class="text-sm font-bold text-slate-900">{{ Auth::user()->name }}</span>
                                <span class="text-xs font-semibold text-slate-500">{{ Auth::user()->identifier }}</span>
                            </div>
                            <div class="h-11 w-11 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center font-bold text-lg shadow-md shadow-indigo-200 transform transition hover:scale-105">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-10">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
            lucide.createIcons();
        </script>
        @stack('scripts')
    </body>
</html>
