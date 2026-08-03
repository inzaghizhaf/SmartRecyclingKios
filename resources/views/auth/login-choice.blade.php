<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>Smart Recycling Kiosk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body{
            background:#f0fdf4;
            font-family:'Poppins',sans-serif;
        }

        .card{
            transition:.35s;
        }

        .card:hover{
            transform:translateY(-8px);
            box-shadow:0 20px 50px rgba(0,0,0,.15);
        }
    </style>
</head>

<body>

<div class="min-h-screen flex justify-center items-center px-3 py-4">

    <div class="w-full max-w-[850px] bg-white rounded-[20px] shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="relative overflow-hidden bg-gradient-to-r from-green-700 via-green-600 to-green-500 py-5">

            <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 160">
                <path fill="#46c43d" fill-opacity=".25"
                    d="M0,64L80,80C160,96,320,128,480,128C640,128,800,96,960,64C1120,32,1280,0,1360,0L1440,0L1440,160L0,160Z">
                </path>
            </svg>

            <div class="relative z-10">

                <img src="{{ asset('images/srk2logo.png') }}"
                     class="mx-auto w-32">

                <h1 class="text-center text-white text-2xl font-bold mt-3">
                    Selamat Datang di
                    <br>
                    Smart Recycling Kiosk
                </h1>

                <p class="text-center text-white/90 mt-2 text-sm">
                    Silakan pilih metode login yang ingin digunakan
                </p>

            </div>

        </div>

        <!-- CARD -->
        <div class="grid md:grid-cols-2 gap-5 p-5">

            <!-- BARCODE -->
            <div class="card bg-white rounded-xl border border-blue-100 shadow-lg p-5 text-center">

                <div class="w-16 h-16 rounded-full bg-blue-100 flex justify-center items-center mx-auto mb-4 shadow">

                    <i class="ph-fill ph-qr-code text-3xl text-blue-600"></i>

                </div>

                <h2 class="text-xl font-bold text-blue-700">
                    Login dengan Barcode
                </h2>

                <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                    Scan QR menggunakan smartphone Anda.
                </p>

                <a href="{{ route('login.barcode') }}">

                    <button
                        class="mt-4 bg-gradient-to-r from-blue-700 to-blue-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow hover:scale-105 transition">

                        Pilih

                    </button>

                </a>

            </div>

            <!-- TABLET -->
            <div class="card bg-white rounded-xl border border-green-100 shadow-lg p-5 text-center">

                <div class="w-16 h-16 rounded-full bg-green-100 flex justify-center items-center mx-auto mb-4 shadow">

                    <i class="ph-fill ph-device-tablet text-3xl text-green-600"></i>

                </div>

                <h2 class="text-xl font-bold text-green-700">
                    Login melalui Tablet
                </h2>

                <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                    Login langsung menggunakan email dan password.
                </p>

                <a href="{{ route('login.tab') }}">

                    <button
                        class="mt-4 bg-gradient-to-r from-green-700 to-green-500 text-white px-6 py-2 rounded-full text-sm font-bold shadow hover:scale-105 transition">

                        Pilih

                    </button>

                </a>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="pb-4">

            <p class="text-center text-gray-500 text-sm">
                Powered by
            </p>

            <div class="flex justify-center items-center gap-5 mt-3">

                <img src="{{ asset('images/uns-logo.png') }}"
                     class="h-8">

                <div class="w-px h-7 bg-gray-300"></div>

                <img src="{{ asset('images/kampus-berdampak3.png') }}"
                     class="h-8">

            </div>

        </div>

    </div>

</div>

</body>
</html>