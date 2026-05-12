<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Success Tracker') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-indigo-100 selection:text-indigo-900 relative overflow-x-hidden">
    
    <!-- Ambient Background Decorations -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-20%] left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-400/20 blur-[120px]"></div>
        <div class="absolute top-[30%] right-[-10%] w-[40%] h-[40%] rounded-full bg-violet-400/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[30%] h-[30%] rounded-full bg-emerald-400/10 blur-[100px]"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 w-full px-6 py-6 md:px-12 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <span class="font-extrabold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-700">SST Portal</span>
        </div>
        
        <div>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors">
                        Buka Dashboard <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3.5 border border-transparent text-sm font-bold rounded-xl text-white bg-slate-900 hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all transform hover:-translate-y-0.5">
                        Login Sistem
                    </a>
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative z-10 flex flex-col items-center justify-center px-6 pt-20 pb-32 text-center min-h-[85vh]">
        <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-bold mb-8">
            <span class="flex w-2 h-2 rounded-full bg-indigo-600 mr-2 animate-pulse"></span>
            Versi 2.0 Resmi Dirilis
        </div>

        <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight leading-[1.1] max-w-4xl mb-8">
            Platform Terpadu untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-500">Kesuksesan Mahasiswa.</span>
        </h1>
        
        <p class="text-lg md:text-xl text-slate-500 font-medium max-w-2xl mb-12 leading-relaxed">
            SST Portal memudahkan mahasiswa dalam melacak poin Sistem Kredit Kegiatan Mahasiswa (SKKM) dan memonitor aktivitas bimbingan akademik bersama Dosen PA secara terpusat, digital, dan efisien.
        </p>

        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-700 hover:to-violet-600 shadow-xl shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                    Lanjutkan ke Dashboard
                    <i data-lucide="arrow-right" class="w-5 h-5 ml-2"></i>
                </a>
            @else
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold rounded-2xl text-white bg-gradient-to-r from-indigo-600 to-violet-500 hover:from-indigo-700 hover:to-violet-600 shadow-xl shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                        Mulai Sekarang
                        <i data-lucide="rocket" class="w-5 h-5 ml-2"></i>
                    </a>
                </div>
            @endauth
        @endif

        <!-- Features Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 max-w-5xl w-full mt-24 text-left">
            <!-- Feature 1 -->
            <div class="bg-white/60 backdrop-blur-xl border border-white p-8 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] group hover:shadow-[0_8px_40px_rgb(99,102,241,0.1)] transition-all">
                <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="award" class="w-7 h-7"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Modul Poin SKKM</h3>
                <p class="text-slate-500 font-medium leading-relaxed">
                    Ajukan sertifikat kegiatan Anda dengan mudah. Sistem akan menghitung otomatis poin Anda, dan Dosen PA dapat melakukan verifikasi langsung melalui dashboard khusus.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white/60 backdrop-blur-xl border border-white p-8 rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] group hover:shadow-[0_8px_40px_rgb(99,102,241,0.1)] transition-all">
                <div class="w-14 h-14 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i data-lucide="book-open" class="w-7 h-7"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Logbook Bimbingan</h3>
                <p class="text-slate-500 font-medium leading-relaxed">
                    Catat dan pantau seluruh sesi bimbingan akademik Anda. Dosen PA dapat memonitor mahasiswa berisiko berdasarkan riwayat aktivitas dan perolehan poin per semester.
                </p>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-200/60 bg-white/50 backdrop-blur-md py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-6 md:px-12 flex flex-col md:flex-row justify-between items-center text-slate-500 text-sm font-medium">
            <p>&copy; {{ date('Y') }} Student Success Tracker. All rights reserved.</p>
            <div class="flex items-center space-x-6 mt-4 md:mt-0">
                <span class="hover:text-indigo-600 transition-colors cursor-pointer">Panduan Pengguna</span>
                <span class="hover:text-indigo-600 transition-colors cursor-pointer">Bantuan</span>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
