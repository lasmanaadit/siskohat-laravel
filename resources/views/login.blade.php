<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SISKOHAT Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a472a, #2d7a3a);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .auth-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
        }
        .auth-container .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-container .logo h1 {
            color: #1a472a;
            font-size: 28px;
            font-weight: 700;
        }
        .auth-container .logo p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 4px;
        }
        .auth-container .logo img {
            max-height: 60px;
            margin-bottom: 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }
        .form-group input:focus {
            border-color: #2d7a3a;
            box-shadow: 0 0 0 4px rgba(45, 122, 58, 0.15);
        }
        .btn-auth {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1a472a, #2d7a3a);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 71, 42, 0.3);
        }
        .btn-auth:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            color: #6b7280;
            font-size: 14px;
        }
        .auth-footer a {
            color: #2d7a3a;
            text-decoration: none;
            font-weight: 600;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: none;
        }
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            display: block;
        }
        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
            display: block;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            padding-right: 48px;
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #9ca3af;
        }
        .toggle-password:hover {
            color: #374151;
        }
        @media (max-width: 480px) {
            .auth-container {
                padding: 32px 24px;
                margin: 16px;
            }
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="logo">
            <img src="{{ asset('images/logo-kemenhaj.png') }}" alt="Logo Kemenhaj">
            <h1>SISKOHAT</h1>
            <p>Sistem Informasi Haji & Umrah</p>
        </div>

        <div id="alert" class="alert"></div>

        <form id="loginForm" autocomplete="off">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" placeholder="admin@siskohot.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" placeholder="********" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn-auth" id="btnLogin">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const button = document.querySelector('.toggle-password');
            if (password.type === 'password') {
                password.type = 'text';
                button.textContent = '🙈';
            } else {
                password.type = 'password';
                button.textContent = '👁️';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const btnLogin = document.getElementById('btnLogin');
            const alert = document.getElementById('alert');

            alert.className = 'alert';
            alert.style.display = 'none';
            alert.textContent = '';

            if (!email || !password) {
                alert.className = 'alert alert-danger show';
                alert.style.display = 'block';
                alert.textContent = 'Email dan password wajib diisi.';
                return;
            }

            btnLogin.disabled = true;
            btnLogin.textContent = 'Memproses...';

            try {
                console.log('Login ke /api/login');
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                console.log('Response:', data);

                if (response.ok && data.token) {
                    // Simpan token
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    console.log('Token tersimpan!');
                    console.log('Role:', data.user.role);

                    // Redirect PASTI
                    if (data.user.role === 'admin') {
                        window.location.href = '/admin';
                    } else {
                        window.location.href = '/index';
                    }
                } else {
                    alert.className = 'alert alert-danger show';
                    alert.style.display = 'block';
                    alert.textContent = data.message || 'Login gagal.';
                }
            } catch (error) {
                console.error('Error:', error);
                alert.className = 'alert alert-danger show';
                alert.style.display = 'block';
                alert.textContent = 'Terjadi kesalahan. Pastikan server berjalan.';
            } finally {
                btnLogin.disabled = false;
                btnLogin.textContent = 'Masuk';
            }
        });
    </script>

</body>

</html>