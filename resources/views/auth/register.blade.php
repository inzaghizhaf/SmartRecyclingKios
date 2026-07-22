<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SiTukar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background: linear-gradient(135deg,#eefaf0 0%,#f8fffa 100%);
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            min-height:100vh;
            padding:1rem;
            overflow-x:hidden;
            position:relative;
        }

        .logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .logo img {
            width: 300px;
            height: auto;
        }

        .logo h1 {
            font-size: 1.6rem;
            font-weight: 700;
            margin-top: 0.5rem;
            color: #46c43d;
        }

        .register-box {
            background:rgba(255,255,255,.95);
            backdrop-filter:blur(10px);
            padding:2rem;
            border-radius:18px;
            box-shadow:0 15px 40px rgba(0,0,0,.12);
            width:100%;
            max-width:390px;
            border-top:5px solid #46c43d;
            transition:.3s;
        }

        .register-box:hover{
            transform:translateY(-5px);
            box-shadow:0 25px 45px rgba(0,0,0,.15)
        }

        .register-box h3 {
            text-align: center;
            color: #1f2937;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .register-box p {
            text-align: center;
            color: #4b5563;
            margin-bottom: 1.5rem;
        }

        .input-group {
            margin-bottom: 1rem;
        }

        .input-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.4rem;
            color: #374151;
        }

        .input-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            font-size: 0.95rem;
            background-color: #f5f7fa;
            transition: 0.2s;
        }

        .input-group input:focus {
            border-color: #46c43d;
            box-shadow: 0 0 0 3px rgba(1, 63, 246, 0.2);
            outline: none;
        }

        /* 🔘 Tombol */
        .btn-register {
            width: 100%;
            background-color: #46c43d;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 12px 0;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-register:hover {
            background-color: #46c43d;
        }

        /* 🔗 Link ke login */
        .login-link {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #46c43d;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* ⚙️ Footer */
        .footer {
            margin-top: 2rem;
            text-align: center;
        }

        .footer p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: #374151;
        }

        .footer img {
            width: 80px;
            margin: 0 10px;
            vertical-align: middle;
        }

        /* 📱 Responsif */
        @media (max-width: 480px) {
            .register-box {
                padding: 1.5rem;
            }
            .btn-register {
                font-size: 0.95rem;
            }
            .logo img {
                width: 65px;
            }
        }
    </style>
</head>

<body>

    @if (session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if ($errors->any())
        <div style="background-color:#fee2e2; color:#b91c1c; padding:10px; margin-bottom:10px; border-radius:8px; width:90%; max-width:400px; text-align:center;">
            <ul style="list-style:none; padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="register-box">
        <div class="logo" style="margin-bottom: 0.5rem;">
            <img src="{{ asset('images/srklogo.png') }}" alt="Logo SiTukar">
        </div>
        <h3>Buat Akun Baru</h3>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email anda" required>
            </div>

            <div class="input-group">
                <label for="nomor_telepon">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Masukkan nomor telepon aktif" required>
            </div>

            <div class="input-group" style="position: relative;">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required style="padding-right: 40px;">
                <span onclick="togglePassword('password', 'eyeIcon1')" style="position: absolute; top: 65%; right: 12px; transform: translateY(-50%); cursor: pointer;">
                    <svg id="eyeIcon1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" width="20" height="20" style="color: #777;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.38-4.362m2.83-2.083A9.955 9.955 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </span>
            </div>

            <div class="input-group" style="position: relative;">
                <label for="konfigurasi_password">Konfirmasi Kata Sandi</label>
                <input type="password" id="konfigurasi_password" name="konfigurasi_password" placeholder="Ulangi password" required style="padding-right: 40px;">
                <span onclick="togglePassword('konfigurasi_password', 'eyeIcon2')" style="position: absolute; top: 65%; right: 12px; transform: translateY(-50%); cursor: pointer;">
                    <svg id="eyeIcon2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" width="20" height="20" style="color: #777;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.38-4.362m2.83-2.083A9.955 9.955 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </span>
            </div>

            <button type="submit" class="btn-register">Daftar Sekarang</button>
        </form>

        <div class="login-link">
            <p>Sudah punya akun? 
                <a href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Powered by:</p>
        <img src="{{ asset('images/uns-logo.png') }}" alt="UNS">
        <img src="{{ asset('images/kampus-berdampak3.png') }}" alt="Kampus Berdampak">
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const password = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';

            eyeIcon.innerHTML = isHidden
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.38-4.362m2.83-2.083A9.955 9.955 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
        }
    </script>

</body>
</html>
