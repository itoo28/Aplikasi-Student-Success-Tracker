<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Student Success Tracker') }} - Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('prototype/css/style.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div id="view-login" class="view active view-transition">
        <div class="login-container">
            <div class="login-card">
                <div class="login-header">
                    <div class="logo-placeholder">
                        <i data-lucide="graduation-cap" class="logo-icon"></i>
                    </div>
                    <h1>Student Success Tracker</h1>
                    <p>Masuk ke portal akademik Anda</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4" style="margin-bottom: 16px; padding: 12px; border-radius: 12px; background: #FEE2E2; color: #991B1B; font-size: 14px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                @session('status')
                    <div class="mb-4" style="margin-bottom: 16px; padding: 12px; border-radius: 12px; background: #D1FAE5; color: #065F46; font-size: 14px;">
                        {{ $value }}
                    </div>
                @endsession

                <div class="role-switcher">
                    <button type="button" class="role-btn {{ old('role', 'student') === 'student' ? 'active' : '' }}" data-role="student">Mahasiswa</button>
                    <button type="button" class="role-btn {{ old('role') === 'lecturer' ? 'active' : '' }}" data-role="lecturer">Dosen PA</button>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="role" id="role" value="{{ old('role', 'student') }}">

                    <div class="form-group">
                        <label for="email" id="label-identifier">NIM / Email</label>
                        <div class="input-wrapper">
                            <i data-lucide="user" class="input-icon"></i>
                            <input type="text" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan NIM atau Email" required autofocus autocomplete="username">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock" class="input-icon"></i>
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required autocomplete="current-password">
                            <button type="button" class="toggle-password" id="toggle-password-btn">
                                <i data-lucide="eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-actions">
                        <label class="checkbox-container" for="remember_me">
                            <input id="remember_me" type="checkbox" name="remember"> Ingat saya
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
                </form>
            </div>
        </div>
        <div class="login-background"></div>
    </div>

    <script>
        lucide.createIcons();

        const roleButtons = document.querySelectorAll('.role-btn');
        const roleInput = document.getElementById('role');
        const identifierLabel = document.getElementById('label-identifier');
        const identifierInput = document.getElementById('email');

        const updateRoleLabel = (role) => {
            if (role === 'lecturer') {
                identifierLabel.innerText = 'NIDN / Email';
                identifierInput.placeholder = 'Masukkan NIDN atau Email';
                return;
            }

            identifierLabel.innerText = 'NIM / Email';
            identifierInput.placeholder = 'Masukkan NIM atau Email';
        };

        updateRoleLabel(roleInput.value);

        roleButtons.forEach((button) => {
            button.addEventListener('click', () => {
                roleButtons.forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
                roleInput.value = button.dataset.role;
                updateRoleLabel(button.dataset.role);
            });
        });

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
