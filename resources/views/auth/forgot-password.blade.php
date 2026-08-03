<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiTukar - Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #eefaf0 0%, #f8fffa 100%); font-family: 'Poppins', sans-serif; }
        .auth-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,.15); border-top: 5px solid #46c43d; }
        .auth-input { background: #f5f7fa; border: 1px solid #e2e8f0; border-radius: 9999px; outline: none; width: 100%; padding: .75rem 1rem; }
        .auth-input:focus { box-shadow: 0 0 0 2px #46c43d; }
        .auth-button { background: #46c43d; border-radius: 9999px; color: #fff; font-weight: 600; padding: .75rem; transition: background .2s; width: 100%; }
        .auth-button:hover { background: #35a92e; }

        /* Tombol Back di sebelah kiri Login Card */
        .back-button {
         position: absolute;
         top: 45px;
         left: calc(50% - 305px);

         width: 64px;
         height: 64px;

         border: 2px solid #46c43d;
         border-radius: 50%;
         background-color: white;
         color: #222;

         display: flex;
         align-items: center;
         justify-content: center;

         text-decoration: none;
         box-shadow: 0 2px 6px rgba(0,0,0,0.08);

         transition: all 0.2s ease;
         z-index: 10;
    }

    .back-button:hover {
        background-color: #46c43d;
        color: white;
    }

    .back-button i {
        font-size: 25px;
    }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center px-4 py-8 relative overflow-y-auto">

    <!-- Tombol Back -->
    <a href="javascript:history.back()" class="back-button" aria-label="Kembali">
        <i class="ph ph-arrow-left"></i>
    </a>

    <main class="auth-card w-full max-w-md p-7 relative">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/srklogo.png') }}" alt="Logo SiTukar" class="w-40 h-auto">
        </div>
        <h1 class="text-center text-gray-800 font-semibold text-xl">Lupa Password?</h1>
        <p class="text-center text-gray-600 text-sm mt-2 mb-6">
            Masukkan email akun Anda. Kami akan mengirim tautan untuk membuat password baru.
        </p>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('email'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email" class="sr-only">Email</label>
            <input id="email" class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus autocomplete="email">

            <button class="auth-button mt-5" type="submit">Kirim tautan reset password</button>
        </form>

        <p class="mt-5 text-center text-sm text-gray-600">
            Ingat password Anda?
            <a href="{{ route('login') }}" class="font-semibold text-green-600 hover:underline">Login</a>
        </p>
    </main>

    <div class="mt-7 text-center text-sm text-gray-600">
        <p class="mb-2">Powered by:</p>
        <div class="flex justify-center items-center space-x-6">
            <img src="{{ asset('images/uns-logo.png') }}" alt="UNS" class="w-16 h-auto">
            <img src="{{ asset('images/kampus-berdampak3.png') }}" alt="Kampus Berdampak" class="w-20 h-auto">
        </div>
    </div>
</body>
</html>
