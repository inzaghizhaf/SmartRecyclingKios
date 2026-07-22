<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>Smart Recycling Kiosk</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #f0fdf4;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            transition: .35s;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
        }
    </style>
</head>

<body>

    <div class="min-h-screen flex justify-center items-start pt-10 p-6">

        <div class="w-[70%] min-w-[900px] max-w-[1050px] bg-white rounded-[24px] shadow-2xl overflow-hidden">

            <!-- HEADER -->
            <div class="relative overflow-hidden bg-gradient-to-r from-green-700 via-green-600 to-green-500 py-7">

                <!-- Gelombang -->
                <svg class="absolute bottom-0 left-0 w-full" viewBox="0 0 1440 160">
                    <path fill="#46c43d" fill-opacity=".25"
                        d="M0,64L80,80C160,96,320,128,480,128C640,128,800,96,960,64C1120,32,1280,0,1360,0L1440,0L1440,160L0,160Z">
                    </path>
                </svg>

                <!-- Titik -->
                <div class="relative z-10">
                    <img src="{{ asset('images/srk2logo.png') }}" class="mx-auto w-48">

                    <h1 class="text-center text-white text-3xl font-bold mt-4">
                        Selamat Datang di
                        <br>
                        Smart Recycling Kiosk
                    </h1>

                    <p class="text-center text-white/90 mt-2 text-base">
                        Silakan pilih metode login yang ingin digunakan
                    </p>
                </div>

            </div>


            <!-- CARD -->
            <div class="grid lg:grid-cols-2 gap-8 p-8">

                <!-- BARCODE -->
                <div class="card bg-white rounded-[20px] border border-blue-100 shadow-lg p-6 text-center">

                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-6 shadow-md">
                        <i class="ph-fill ph-qr-code text-5xl text-blue-600"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-blue-700 mt-5">
                        Login dengan Barcode
                    </h2>

                    <p class="text-gray-500 mt-3 text-base">
                        Scan QR menggunakan
                        smartphone Anda.
                    </p>

                    <a href="{{ route('login.barcode') }}">
                        <button
                            class="mt-6 bg-gradient-to-r from-blue-700 to-blue-500 text-white px-10 py-2.5 rounded-full text-base font-bold shadow-lg hover:scale-105 transition">
                            Pilih
                        </button>
                    </a>

                </div>


                <!-- TABLET -->
                <div class="card bg-white rounded-[20px] border border-green-100 shadow-lg p-6 text-center">

                    <div class="w-24 h-24 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-6 shadow-md">
                        <i class="ph-fill ph-device-tablet text-5xl text-green-600"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-green-700 mt-5">
                        Login melalui Tablet
                    </h2>

                    <p class="text-gray-500 mt-3 text-base">
                        Login langsung
                        menggunakan email
                        dan password.
                    </p>

                    <a href="{{ route('login.tab') }}">
                        <button
                            class="mt-6 bg-gradient-to-r from-green-700 to-green-500 text-white px-10 py-2.5 rounded-full text-base font-bold shadow-lg hover:scale-105 transition">
                            Pilih
                        </button>
                    </a>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="pb-6">

                <p class="text-center text-gray-500 text-sm">
                    Powered by
                </p>

                <div class="flex justify-center items-center gap-6 mt-3">
                    <img src="{{ asset('images/uns-logo.png') }}" class="h-12">
                    <div class="w-px h-10 bg-gray-300"></div>
                    <img src="{{ asset('images/kampus-berdampak3.png') }}" class="h-12">
                </div>

            </div>

        </div>

    </div>

</body>
</html>