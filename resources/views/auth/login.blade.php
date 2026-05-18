<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Student Success Tracker') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="font-sans antialiased text-slate-900 bg-slate-50 selection:bg-indigo-100 selection:text-indigo-900 relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Ambient Background -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-500/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-violet-500/20 blur-[120px]"></div>
    </div>

    <!-- Login Container -->
    <div class="relative z-10 w-full max-w-md px-6 py-12">
        
        <!-- Header -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-500 text-white shadow-xl shadow-indigo-500/30 mb-6 transform transition hover:scale-105">
                <i data-lucide="graduation-cap" class="w-8 h-8"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">SST Portal</h1>
            <p class="text-slate-500 font-medium mt-2">Student Success Tracker</p>
        </div>

        <!-- Glass Card -->
        <div class="bg-white/70 backdrop-blur-xl border border-white rounded-[2rem] shadow-[0_8px_40px_rgb(0,0,0,0.06)] p-8 sm:p-10">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-600 text-sm font-semibold flex items-center">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-3 shrink-0"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @session('status')
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-semibold flex items-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3 shrink-0"></i>
                    <span>{{ $value }}</span>
                </div>
            @endsession

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Role Selector -->
                <div class="mb-8">
                    <label for="role" class="block text-sm font-bold text-slate-700 mb-2">Masuk Sebagai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="shield-check" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <select id="role" name="role" class="block w-full pl-11 pr-10 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none">
                            <option value="mahasiswa" @selected(old('role', 'mahasiswa') === 'mahasiswa')>Mahasiswa</option>
                            <option value="dosen_pa" @selected(old('role') === 'dosen_pa')>Dosen PA</option>
                            <option value="kaprodi" @selected(old('role') === 'kaprodi')>Kaprodi</option>
                            <option value="kemahasiswaan" @selected(old('role') === 'kemahasiswaan')>Kemahasiswaan</option>
                            <option value="super_admin" @selected(old('role') === 'super_admin')>Super Admin</option>
                        </select>
                    </div>
                </div>

                <!-- Email/Identifier -->
                <div class="mb-5">
                    <label for="email" id="label-identifier" class="block text-sm font-bold text-slate-700 mb-2">NIM / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <input type="text" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                            class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none" 
                            placeholder="Masukkan NIM atau Email">
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Lupa Password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <input type="password" id="password" name="password" required autocomplete="current-password" 
                            class="block w-full pl-11 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all outline-none" 
                            placeholder="Masukkan password">
                        <button type="button" id="toggle-password-btn" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="mb-8">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center w-5 h-5 mr-3">
                            <input id="remember_me" type="checkbox" name="remember" class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all"></div>
                            <i data-lucide="check" class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya pada perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-indigo-600/30 transform transition hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Masuk ke Sistem <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <p class="text-xs font-semibold text-slate-400">&copy; {{ date('Y') }} Student Success Tracker.</p>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const roleInput = document.getElementById('role');
        const identifierLabel = document.getElementById('label-identifier');
        const identifierInput = document.getElementById('email');

        const updateRoleLabel = (role) => {
            if (role === 'mahasiswa') {
                identifierLabel.innerText = 'NIM / Email';
                identifierInput.placeholder = 'Masukkan NIM atau Email';
                return;
            }

            if (role === 'dosen_pa' || role === 'kaprodi') {
                identifierLabel.innerText = 'NIDN / Email';
                identifierInput.placeholder = 'Masukkan NIDN atau Email';
            } else {
                identifierLabel.innerText = 'Email / Identifier';
                identifierInput.placeholder = 'Masukkan Email atau Identifier';
            }
        };

        updateRoleLabel(roleInput.value);
        roleInput.addEventListener('change', () => updateRoleLabel(roleInput.value));

        const togglePasswordButton = document.getElementById('toggle-password-btn');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        togglePasswordButton.addEventListener('click', () => {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
