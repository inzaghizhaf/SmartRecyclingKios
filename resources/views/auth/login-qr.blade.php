<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Barcode | Smart Recycling</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#eefaf0] min-h-screen flex items-center justify-center relative overflow-hidden">

        <!-- Background Eco -->
        <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">

        <!-- Daun -->
        <div class="absolute left-0 bottom-0 opacity-10">
            <i class="ph-fill ph-leaf text-[260px] text-green-500"></i>
        </div>

        <!-- Recycle -->
        <div class="absolute right-0 top-16 opacity-10">
            <i class="ph-fill ph-recycle text-[220px] text-green-500"></i>
        </div>

        <!-- Titik -->
        <div class="absolute right-14 top-1/2 -translate-y-1/2 opacity-20">
            <div class="grid grid-cols-3 gap-2">
                @for($i=0;$i<18;$i++)
                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                @endfor
            </div>
        </div>

    </div>
    <!-- Card -->
    <div class="relative bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl w-[450px] p-10 text-center border border-white">

        <h1 class="text-3xl font-bold text-green-700">
            Login dengan Barcode
        </h1>

        <p class="text-gray-600 mt-2">
            Scan QR Code menggunakan aplikasi di HP Anda
        </p>

        <!-- QR -->
        <div class="mt-8 bg-gray-50 rounded-2xl p-5 shadow-inner">
            <img src="{{ asset('images/qrcode.png') }}"
                 class="w-64 mx-auto rounded-xl">
        </div>

        <!-- Status -->
        <div class="mt-8">

            <div class="flex justify-center items-center gap-2">

                <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>

                <span id="status" class="font-semibold text-green-600">
                    Menunggu Login...
                </span>

            </div>

        </div>

        <!-- Info -->
        <div class="mt-6 bg-green-50 rounded-xl p-4 text-sm text-gray-600">
            Setelah QR berhasil dipindai dan login melalui HP,
            halaman ini akan otomatis masuk ke Dashboard.
        </div>

        <!-- Button -->
        <a href="{{ route('login.choice') }}">
            <button
                class="mt-8 w-full bg-red-500 hover:bg-red-600 transition text-white font-semibold py-3 rounded-xl shadow-lg">
                Kembali
            </button>
        </a>

    </div>

<script>

setInterval(() => {

    fetch("/qr/status")
        .then(response => response.json())
        .then(data => {

            if (data.status === "success") {

                document.getElementById("status").innerHTML =
                    "✔ Login berhasil, mengalihkan...";

                setTimeout(() => {
                    window.location.href = "/dashboard";
                }, 800);

            }

        })
        .catch(error => console.log(error));

}, 2000);

</script>

</body>
</html>