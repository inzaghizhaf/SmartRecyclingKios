<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SiTukar - Buat Password Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #eefaf0 0%, #f8fffa 100%); font-family: 'Poppins', sans-serif; }
        .auth-card { background: #fff; border-radius: 16px; box-shadow: 0 4px 10px rgba(0,0,0,.15); border-top: 5px solid #46c43d; }
        .auth-input { background: #f5f7fa; border: 1px solid #e2e8f0; border-radius: 9999px; outline: none; width: 100%; padding: .75rem 1rem; }
        .auth-input:focus { box-shadow: 0 0 0 2px #46c43d; }
        .auth-button { background: #46c43d; border-radius: 9999px; color: #fff; font-weight: 600; padding: .75rem; width: 100%; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-8">
    <main class="auth-card w-full max-w-md p-7">
        <div class="flex justify-center mb-4"><img src="{{ asset('images/srklogo.png') }}" alt="Logo SiTukar" class="w-40 h-auto"></div>
        <h1 class="text-center text-gray-800 font-semibold text-xl">Buat Password Baru</h1>
        <p class="text-center text-gray-600 text-sm mt-2 mb-6">Masukkan password baru untuk akun Anda.</p>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="sr-only">Email</label>
                <input id="email" class="auth-input" type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Email" required autofocus autocomplete="username">
                @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="sr-only">Password baru</label>
                <input id="password" class="auth-input" type="password" name="password" placeholder="Password baru" required autocomplete="new-password">
                @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="sr-only">Konfirmasi password baru</label>
                <input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" placeholder="Konfirmasi password baru" required autocomplete="new-password">
            </div>
            <button class="auth-button mt-5" type="submit">Simpan password baru</button>
        </form>
    </main>
</body>
</html>
