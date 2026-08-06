<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Barcode | Smart Recycling</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <style> /* Tombol Back di sebelah kiri Login Card */
        .back-button {
         position: absolute;
         top: 35px;
         left: calc(50% - 350px);

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
    </style>
</head>

<body class="bg-[#eefaf0] min-h-screen flex items-start justify-center relative overflow-y-auto overflow-x-hidden py-8">
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

    <!-- Tombol Back -->
    <a href="javascript:history.back()" class="back-button" aria-label="Kembali">
        <i class="ph ph-arrow-left"></i>
    </a>

    <!-- Card -->
    <div class="relative bg-white/90 backdrop-blur-md shadow-2xl rounded-3xl
                w-[92%] max-w-[520px]
                p-10 text-center border border-white">

        <h1 class="text-3xl font-bold text-green-700">
            Login dengan Barcode
        </h1>

        <p class="text-gray-600 mt-2">
            Scan QR Code menggunakan Web di Smartphone Anda
        </p>

        <!-- QR -->
        <div class="mt-8 bg-gray-50 rounded-2xl p-5 shadow-inner">
            <img src="{{ asset('images/qrsrk.jpeg') }}"
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