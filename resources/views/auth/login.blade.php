<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Success Tracker') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('brand/logo-uhb.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    @fluxAppearance
    @livewireStyles
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-indigo-100 selection:text-indigo-900 relative overflow-x-hidden">
    <flux:accent color="indigo">
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-20%] left-[10%] w-[50%] h-[50%] rounded-full bg-indigo-400/20 blur-[120px]"></div>
            <div class="absolute top-[30%] right-[-10%] w-[40%] h-[40%] rounded-full bg-violet-400/20 blur-[120px]"></div>
            <div class="absolute bottom-[-10%] left-[20%] w-[30%] h-[30%] rounded-full bg-emerald-400/10 blur-[100px]"></div>
        </div>

        <div class="relative z-10 grid min-h-screen lg:grid-cols-[60%_40%]">
            <section class="hidden lg:flex relative min-h-screen flex-col overflow-hidden bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-950 text-white">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.12),transparent_50%)] pointer-events-none"></div>
                
                <nav class="relative z-10 w-full px-6 py-5 md:px-10 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img
                            src="{{ asset('brand/logo-uhb.svg') }}"
                            alt="{{ config('app.name', 'Student Success Tracker') }}"
                            class="h-12 w-12 rounded-2xl bg-white p-1 object-contain shadow-lg shadow-indigo-950/30"
                        />
                        <span class="font-extrabold text-2xl tracking-tight text-white">SST Portal</span>
                    </div>

                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold rounded-xl text-indigo-600 bg-white hover:bg-indigo-50 transition-colors shadow-lg shadow-indigo-950/20">
                            Buka Dashboard <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                        </a>
                    @endauth
                </nav>

                <main class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 pt-4 pb-8 text-center">
                
                    <h1 class="text-4xl md:text-5xl xl:text-6xl font-black text-white tracking-tight leading-[1.08] max-w-4xl mb-5">
                        Platform Terpadu untuk <span class="text-white">Kesuksesan Mahasiswa.</span>
                    </h1>

                    <p class="text-base xl:text-lg text-indigo-100/80 font-medium max-w-2xl mb-6 leading-relaxed">
                        SST Portal memudahkan mahasiswa dalam melacak poin Sistem Kredit Kegiatan Mahasiswa (SKKM) dan memonitor aktivitas bimbingan akademik bersama Dosen PA secara terpusat, digital, dan efisien.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-4xl w-full mt-4 text-left">
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 xl:p-6 rounded-[1.5rem] shadow-xl group hover:bg-white/15 hover:border-white/20 transition-all">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 text-sky-300 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="award" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Modul Poin SKKM</h3>
                            <p class="text-sm xl:text-base text-indigo-100/70 font-medium leading-relaxed">
                                Ajukan sertifikat kegiatan Anda dengan mudah. Sistem akan menghitung otomatis poin Anda, dan Dosen PA dapat melakukan verifikasi langsung melalui dashboard khusus.
                            </p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 xl:p-6 rounded-[1.5rem] shadow-xl group hover:bg-white/15 hover:border-white/20 transition-all">
                            <div class="w-12 h-12 rounded-2xl bg-white/10 text-sky-300 border border-white/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Logbook Bimbingan</h3>
                            <p class="text-sm xl:text-base text-indigo-100/70 font-medium leading-relaxed">
                                Catat dan pantau seluruh sesi bimbingan akademik Anda. Dosen PA dapat memonitor mahasiswa berisiko berdasarkan riwayat aktivitas dan perolehan poin per semester.
                            </p>
                        </div>
                    </div>
                </main>
            </section>

            <aside class="border-t border-slate-200/70 bg-white/80 backdrop-blur-xl lg:border-l lg:border-t-0">
                <div class="flex min-h-screen items-center px-6 py-10 sm:px-10 lg:px-10">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-8">
                            {{-- Mobile Logo & Brief Intro (Only visible on screens below lg) --}}
                            <div class="flex items-center space-x-3 mb-6 lg:hidden">
                                <img
                                    src="{{ asset('brand/logo-uhb.svg') }}"
                                    alt="SST Portal"
                                    class="h-10 w-10 rounded-xl bg-slate-50 p-1 object-contain shadow-md"
                                />
                                <div class="min-w-0">
                                    <span class="font-black text-lg tracking-tight text-slate-900 block leading-none">SST Portal</span>
                                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mt-1">Student Success Tracker</span>
                                </div>
                            </div>

                            <div class="mb-4 inline-flex size-12 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-lg shadow-indigo-500/30">
                                <i data-lucide="log-in" class="w-5 h-5"></i>
                            </div>
                            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Masuk Sistem</h2>
                            <p class="mt-2 text-sm font-medium leading-relaxed text-slate-500">Gunakan akun yang sudah terdaftar untuk melanjutkan.</p>
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
                                <flux:label for="email">Username</flux:label>
                                <flux:input
                                    id="email"
                                    name="email"
                                    type="text"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="231234567 atau email kampus"
                                />
                            </flux:field>

                            <flux:field>
                                <div class="mb-2 flex items-center justify-between">
                                    <flux:label for="password">Password</flux:label>
                                    @if (Route::has('password.request'))
                                        <flux:link :href="route('password.request')" variant="subtle" class="text-xs">Lupa Password?</flux:link>
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

                            <flux:field variant="inline">
                                <flux:checkbox id="remember_me" name="remember" />
                                <flux:label for="remember_me">Ingat saya</flux:label>
                            </flux:field>

                            <flux:button type="submit" variant="primary" icon:trailing="arrow-right" class="w-full">
                                Masuk
                            </flux:button>
                        </form>

                        @if (Route::has('register'))
                            <div class="mt-6 text-center border-t border-slate-100 pt-5">
                                <p class="text-sm font-medium text-slate-500 mb-3">Daftar jika belum memiliki akun</p>
                                <flux:button href="{{ route('register') }}" class="w-full">
                                    Daftar
                                </flux:button>
                            </div>
                        @endif

                        <p class="mt-6 text-center text-xs font-medium text-slate-500">&copy; {{ date('Y') }} Student Success Tracker</p>
                    </div>
                </div>
            </aside>
        </div>
    </flux:accent>

    @livewireScripts
    @fluxScripts
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
