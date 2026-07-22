<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiTukar - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #eefaf0 0%, #f8fffa 100%);
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        .login-card {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            padding: 2rem;
            max-width: 380px;
            width: 90%;
            margin: 0 auto;
            border-top: 5px solid #46c43d;
        }
        .login-card input {
            background-color: #f5f7fa;
            border-radius: 9999px;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            outline: none;
            width: 100%;
            transition: box-shadow 0.2s ease;
        }
        .login-card input:focus {
            box-shadow: 0 0 0 2px #46c43d;
        }
        .login-card button {
            background-color: #46c43d;
            color: white;
            border-radius: 9999px;
            padding: 0.75rem;
            width: 100%;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .login-card button:hover {
            background-color: #46c43d;
        }
        .powered {
            margin-top: 2rem;
        }
        @media (max-width: 640px) {
            .login-card {
                padding: 1.5rem;
                margin-top: 1.5rem;
            }
        }
    </style>
</head>

@if(session('success'))
<script>
    alert("{{ session('success') }}");
</script>
@endif

<body class="min-h-screen flex flex-col items-center justify-center px-4 relative overflow-hidden">

<!-- Background Eco -->
<div class="fixed inset-0 -z-10 opacity-10 pointer-events-none">

    <div class="absolute left-0 bottom-0">
        <i class="ph-fill ph-leaf text-[260px] text-green-500"></i>
    </div>

    <div class="absolute right-0 top-20">
        <i class="ph-fill ph-recycle text-[220px] text-green-500"></i>
    </div>

</div>
</div>

    <!-- Login Card -->
    <div class="login-card">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/srklogo.png') }}" alt="SiTukar Icon" class="w-45">
        </div>
        <h3 class="text-center text-gray-800 font-semibold mb-2 text-lg">Welcome User!</h3>
        <p class="text-center text-gray-600 text-sm mb-4">Masukkan email dan password Anda untuk login</p>
       
        @if ($errors->has('email'))
        <div id="login-alert"
            class="mb-4 rounded-xl border border-red-300 bg-red-50 px-4 py-3 shadow-sm">
            <div class="flex items-start gap-3">
                <i class="ph-fill ph-warning-circle text-red-600 text-xl mt-0.5"></i>
                <div>
                    <p class="text-sm text-red-600">
                        {{ $errors->first('email') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Input Email -->
            <div class="mb-3">
                <input id="email" type="email" name="email" placeholder="Email" required autofocus>
            </div>

            <!-- Input Password + Toggle Eye -->
            <div class="mb-4 relative">
                <input id="password" type="password" name="password" placeholder="Password"
                       class="w-full px-4 py-2 rounded-full bg-gray-100 focus:outline-none" required>

                <span class="absolute right-4 top-1/3 -translate-y-1/2 cursor-pointer" onclick="togglePassword()">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.38-4.362m2.83-2.083A9.955 9.955 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>
                </span>
            </div>

            <!-- Tombol Login -->
            <div class="mb-4">
                <button type="submit">Login</button>
            </div>

            <!-- Link ke Register -->
            <p class="text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-[#46c43d] hover:underline font-semibold">Daftar</a>
            </p>
        </form>
    </div>

    <!-- Logo Partner -->
    <div class="powered text-center">
        <p class="text-sm text-black mb-2">Powered by:</p>
        <div class="flex justify-center space-x-6 items-center">
            <img src="{{ asset('images/uns-logo.png') }}" alt="UNS" class="w-20 h-auto object-contain">
            <img src="{{ asset('images/kampus-berdampak3.png') }}" alt="Kampus Merdeka" class="w-24 h-auto object-contain">
        </div>
    </div>

    <!-- Script Toggle Password -->
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';
            eyeIcon.innerHTML = isHidden
                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />'
                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.978 9.978 0 012.38-4.362m2.83-2.083A9.955 9.955 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.97 9.97 0 01-4.043 5.132M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />';
        }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const alert = document.getElementById('login-alert');

        if (alert) {
            setTimeout(function () {
                alert.style.transition = 'opacity .3s ease';
                alert.style.opacity = '0';
                setTimeout(function () {
                    alert.remove();
                }, 300);
            }, 4000);
        }
    });
    </script>
</body>
</html>
