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

        <div class="relative z-10 grid min-h-screen lg:grid-cols-[55%_45%]">
            <!-- Left Panel (Intro Card) -->
            <section class="hidden lg:flex relative flex-col overflow-hidden justify-between min-h-full bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-950 text-white">
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
                </nav>

                <main class="relative z-10 flex flex-1 flex-col items-center justify-center px-6 py-8 text-center max-w-4xl mx-auto">
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-[1.08] mb-5">
                        Platform Terpadu untuk <span class="text-white">Kesuksesan Mahasiswa.</span>
                    </h1>

                    <p class="text-sm md:text-base text-indigo-100/80 font-medium max-w-xl mb-8 leading-relaxed">
                        SST Portal memudahkan mahasiswa dalam melacak poin Sistem Kredit Kegiatan Mahasiswa (SKKM) dan memonitor aktivitas bimbingan akademik bersama Dosen PA secara terpusat, digital, dan efisien.
                    </p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full text-left">
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-[1.5rem] shadow-xl group hover:bg-white/15 hover:border-white/20 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-300 border border-white/10 flex items-center justify-center mb-3.5 group-hover:scale-105 transition-transform">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-1.5">Modul Poin SKKM</h3>
                            <p class="text-xs text-indigo-100/70 leading-relaxed">
                                Ajukan sertifikat kegiatan Anda dengan mudah. Sistem akan menghitung otomatis poin Anda, dan Dosen PA dapat melakukan verifikasi langsung.
                            </p>
                        </div>

                        <div class="bg-white/10 backdrop-blur-md border border-white/10 p-5 rounded-[1.5rem] shadow-xl group hover:bg-white/15 hover:border-white/20 transition-all">
                            <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-300 border border-white/10 flex items-center justify-center mb-3.5 group-hover:scale-105 transition-transform">
                                <i data-lucide="book-open" class="w-5 h-5"></i>
                            </div>
                            <h3 class="text-lg font-bold text-white mb-1.5">Logbook Bimbingan</h3>
                            <p class="text-xs text-indigo-100/70 leading-relaxed">
                                Catat dan pantau seluruh sesi bimbingan akademik Anda. Dosen PA dapat memonitor mahasiswa berisiko berdasarkan riwayat aktivitas dan perolehan poin.
                            </p>
                        </div>
                    </div>
                </main>

                <div class="py-4"></div>
            </section>

            <!-- Right Panel (Form Card Container) -->
            <aside class="border-t border-slate-200/70 bg-white/80 backdrop-blur-xl lg:border-l lg:border-t-0 flex items-center justify-center">
                <div class="flex items-center justify-center min-h-screen px-4 py-8 sm:px-8 lg:px-10 w-full">
                    <div class="w-full max-w-lg">
                        @if(!session()->has('registration_prodi_id'))
                            <div class="mb-6">
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

                                <div class="mb-3 inline-flex size-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-md shadow-indigo-500/20">
                                    <i data-lucide="key-round" class="w-5 h-5"></i>
                                </div>
                                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Verifikasi Kode Unik</h2>
                                <p class="mt-1 text-sm font-medium leading-relaxed text-slate-500">Masukkan kode unik pendaftaran Program Studi Anda untuk melanjutkan registrasi.</p>
                            </div>

                            @if ($errors->has('registration_code'))
                                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                    {{ $errors->first('registration_code') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register.verify') }}" class="space-y-4">
                                @csrf
                                <flux:field>
                                    <flux:label for="registration_code">Kode Unik Pendaftaran</flux:label>
                                    <flux:input
                                        id="registration_code"
                                        name="registration_code"
                                        type="text"
                                        required
                                        autofocus
                                        placeholder="Masukkan kode unik pendaftaran"
                                    />
                                </flux:field>

                                <flux:button type="submit" variant="primary" color="amber" class="w-full mt-4">
                                    Verifikasi Kode
                                </flux:button>
                            </form>

                            <div class="mt-5 text-center border-t border-slate-100 pt-4">
                                <p class="text-sm font-medium text-slate-500 mb-2.5">Sudah memiliki akun?</p>
                                <flux:button href="{{ route('login') }}" class="w-full">
                                    Masuk
                                </flux:button>
                            </div>
                        @else
                            <div class="mb-4 p-4 rounded-2xl border border-indigo-100 bg-indigo-50/50 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-indigo-100 text-indigo-700 rounded-xl shrink-0">
                                        <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">Program Studi Terverifikasi</p>
                                        <h4 class="text-sm font-bold text-slate-900 leading-snug">{{ $verifiedProdi->jenjang }} {{ $verifiedProdi->nama }}</h4>
                                        <p class="text-[11px] font-medium text-slate-500 leading-none mt-0.5">{{ $verifiedProdi->fakultas?->nama }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('register.reset') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-2.5 py-1.5 border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-950 transition-colors shrink-0">
                                    Ganti Kode
                                </a>
                            </div>

                            <div class="mb-6">
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

                                <div class="mb-3 inline-flex size-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-md shadow-indigo-500/20">
                                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                                </div>
                                <h2 class="text-2xl font-extrabold tracking-tight text-slate-900">Daftar Akun</h2>
                                <p class="mt-1 text-sm font-medium leading-relaxed text-slate-500">Lengkapi data pendaftaran mahasiswa berikut ini.</p>
                            </div>

                            @if ($errors->any())
                                <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                @csrf

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3.5">
                                <!-- Nama Lengkap -->
                                <div class="sm:col-span-2">
                                    <flux:field>
                                        <flux:label for="name">Nama Lengkap</flux:label>
                                        <flux:input
                                            id="name"
                                            name="name"
                                            type="text"
                                            :value="old('name')"
                                            required
                                            autofocus
                                            placeholder="Masukkan nama lengkap"
                                        />
                                    </flux:field>
                                </div>

                                <!-- NIM -->
                                <flux:field>
                                    <flux:label for="identifier">Nim</flux:label>
                                    <flux:input
                                        id="identifier"
                                        name="identifier"
                                        type="text"
                                        :value="old('identifier')"
                                        required
                                        placeholder="Masukkan NIM Anda"
                                    />
                                </flux:field>

                                <!-- Nomor HP -->
                                <flux:field>
                                    <flux:label for="phone_number">Nomor HP</flux:label>
                                    <flux:input
                                        id="phone_number"
                                        name="phone_number"
                                        type="text"
                                        inputmode="numeric"
                                        pattern="[0-9]{10,15}"
                                        :value="old('phone_number')"
                                        required
                                        placeholder="Masukkan nomor HP aktif"
                                    />
                                </flux:field>

                                <!-- Hidden Fakultas & Program Study (ditentukan oleh kode unik pendaftaran) -->
                                <div class="hidden">
                                    <select id="fakultas_id" name="fakultas_id">
                                        <option value="{{ $verifiedProdi->fakultas_id }}" selected>{{ $verifiedProdi->fakultas?->nama }}</option>
                                    </select>
                                    <select id="program_studi_id" name="program_studi_id">
                                        <option value="{{ $verifiedProdi->id }}" selected>{{ $verifiedProdi->jenjang }} {{ $verifiedProdi->nama }}</option>
                                    </select>
                                </div>

                                <!-- Semester -->
                                <flux:field>
                                    <flux:label for="semester">Semester</flux:label>
                                    <flux:input
                                        id="semester"
                                        name="semester"
                                        type="text"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        :value="old('semester')"
                                        required
                                        placeholder="Masukkan semester aktif"
                                    />
                                </flux:field>

                                <!-- Dosen PA -->
                                <flux:field>
                                    <flux:label for="lecturer_id">Dosen PA</flux:label>
                                    <select id="lecturer_id" name="lecturer_id" class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-sm font-medium text-slate-700 focus:border-indigo-500 focus:ring-indigo-500/20 focus:ring-2 focus:outline-none transition shadow-sm h-10" required>
                                        <option value="">-- Pilih Dosen PA --</option>
                                        @foreach($lecturers as $lecturer)
                                            <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                                                {{ $lecturer->name }} ({{ $lecturer->identifier }})
                                            </option>
                                        @endforeach
                                    </select>
                                </flux:field>

                                <!-- Email -->
                                <div class="sm:col-span-2">
                                    <flux:field>
                                        <flux:label for="email">Email</flux:label>
                                        <flux:input
                                            id="email"
                                            name="email"
                                            type="email"
                                            :value="old('email')"
                                            required
                                            placeholder="Masukkan email Anda"
                                        />
                                    </flux:field>
                                </div>

                                <!-- Password -->
                                <flux:field>
                                    <flux:label for="password">Password</flux:label>
                                    <flux:input
                                        id="password"
                                        name="password"
                                        type="password"
                                        required
                                        viewable
                                        placeholder="Password baru"
                                    />
                                </flux:field>

                                <!-- Konfirmasi Password -->
                                <flux:field>
                                    <flux:label for="password_confirmation">Konfirmasi</flux:label>
                                    <flux:input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        required
                                        viewable
                                        placeholder="Ulangi password"
                                    />
                                </flux:field>
                            </div>

                            <flux:button type="submit" variant="primary" class="w-full mt-4">
                                Daftar
                            </flux:button>
                        </form>

                        <div class="mt-5 text-center border-t border-slate-100 pt-4">
                            <p class="text-sm font-medium text-slate-500 mb-2.5">Sudah memiliki akun?</p>
                            <flux:button href="{{ route('login') }}" class="w-full">
                                Masuk
                            </flux:button>
                        </div>
                        @endif

                        <p class="mt-5 text-center text-xs font-medium text-slate-500">&copy; {{ date('Y') }} Student Success Tracker</p>
                    </div>
                </div>
            </aside>
        </div>
    </flux:accent>

    @livewireScripts
    @fluxScripts
    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', function() {
            const fakultasSelect = document.getElementById('fakultas_id');
            const prodiSelect = document.getElementById('program_studi_id');
            
            if (fakultasSelect && prodiSelect) {
                // Simpan semua opsi prodi pada array
                const allProdiOptions = Array.from(prodiSelect.options).map(option => ({
                    value: option.value,
                    text: option.textContent.trim(),
                    fakultasId: option.getAttribute('data-fakultas')
                }));

                function filterProdi() {
                    const selectedFakultasId = fakultasSelect.value;
                    const oldVal = "{{ old('program_studi_id') }}";
                    
                    // Bersihkan opsi saat ini kecuali placeholder
                    prodiSelect.innerHTML = '<option value="">-- Pilih Program Study --</option>';
                    
                    if (!selectedFakultasId) {
                        return;
                    }
                    
                    // Filter opsi yang sesuai
                    const filteredOptions = allProdiOptions.filter(opt => opt.value === "" || opt.fakultasId === selectedFakultasId);
                    
                    filteredOptions.forEach(opt => {
                        if (opt.value === "") return;
                        const newOption = document.createElement('option');
                        newOption.value = opt.value;
                        newOption.textContent = opt.text;
                        newOption.setAttribute('data-fakultas', opt.fakultasId);
                        
                        // Re-select jika cocok dengan nilai input sebelumnya (old value)
                        if (opt.value == oldVal) {
                            newOption.selected = true;
                        }
                        prodiSelect.appendChild(newOption);
                    });
                }

                // Jalankan filter saat pertama kali load untuk menangani validasi error
                if (fakultasSelect.value) {
                    filterProdi();
                }
                
                fakultasSelect.addEventListener('change', filterProdi);
            }
        });
    </script>
</body>
</html>
