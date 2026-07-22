<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu List</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#eefaf0] min-h-screen flex flex-col relative overflow-x-hidden">

<div class="fixed inset-0 -z-10 opacity-10 pointer-events-none">

    <div class="absolute left-0 bottom-0">
        <i class="ph-fill ph-leaf text-[260px] text-green-500"></i>
    </div>

    <div class="absolute right-0 top-20">
        <i class="ph-fill ph-recycle text-[220px] text-green-500"></i>
    </div>

</div>


    <!-- Navbar -->
    <nav class="flex justify-between items-center text-white px-6 py-4 shadow-md" style="background-color:#46c43d;">
        <!-- Logo + User Button -->
        <div class="flex items-center space-x-3">
            <button onclick="toggleUserMenu()" class="relative focus:outline-none">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" class="w-9 h-9 rounded-full border-2 border-white hover:opacity-80 transition">
            </button>
            <img src="{{ asset('images/srk2logo.png') }}" alt="Logo SiTukar" class="w-28 h-auto ml-2">
        </div>

        <!-- Logout Button -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button"
                onclick="confirmLogout()"
                class="bg-red-600 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold">
                <i class="ph-fill ph-sign-out"></i>
                Logout
            </button>
        </form>

        <!-- User Dropdown Menu -->
        <div id="userMenu" class="hidden absolute top-16 left-6 bg-white text-gray-800 rounded-xl shadow-lg w-56 p-4 z-50">
            <div class="flex flex-col items-center mb-4">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" class="w-16 h-16 rounded-full mb-2">
                <h2 class="font-semibold text-lg text-center">{{ Auth::user()->nama_lengkap }}</h2>
            </div>
            <div class="border-t border-gray-300 my-2"></div>
            <ul class="space-y-1">

            <li>
                <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-100">
                    <i class="ph-fill ph-house text-lg"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="{{ url('/list') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-100">
                    <i class="ph-fill ph-list-bullets text-lg"></i>
                    List
                </a>
            </li>

            <li>
                <a href="{{ url('/contact') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-green-100">
                    <i class="ph-fill ph-phone-call text-lg"></i>
                    Contact Us
                </a>
            </li>

        </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 container mx-auto px-6 py-10">

        <!-- Judul Halaman -->
        <div class="mb-6">
             <h1 class="text-2xl font-bold text-green-600 flex items-center gap-2">
            Menu Contact Us
            <i class="ph-fill ph-leaf text-green-500 text-3xl"></i>
            </h1>
            <p class="text-white-600 text-sm mt-1">Berikut daftar jenis sampah yang bisa ditukar dengan poin.</p>
        </div>

        <!-- Daftar Poin Sampah -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-bold text-green-600 mb-6">Daftar Poin Sampah</h2>

            <div class="flex flex-col gap-6">
                <!-- Sampah Plastik -->
                <div class="bg-[#BBF7D0] p-6 rounded-xl shadow-inner flex flex-col md:flex-row items-center gap-6 border border-[#DCE2FF]">
                    <img src="{{ asset('images/plastic-bottles.jpg') }}" 
                         alt="Sampah Plastik" 
                         class="w-48 h-36 object-cover rounded-lg shadow-md border border-[#013FF6]/20">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-black">Sampah Plastik</h3>
                        <p class="text-white-700 text-sm mt-1">1 Botol = <span class="bg-[#46c43d] text-white px-3 py-1 rounded-full font-bold shadow-md">1 Poin</span>
                        <p class="text-white-500 text-sm">(Tidak tergantung satuan ml)</p>
                    </div>
                </div>

                <!-- Sampah Kaleng -->
                <div class="bg-[#BBF7D0] p-6 rounded-xl shadow-inner flex flex-col md:flex-row items-center gap-6 border border-[#DCE2FF]">
                    <img src="{{ asset('images/cans.jpg') }}" 
                         alt="Sampah Kaleng" 
                         class="w-48 h-36 object-cover rounded-lg shadow-md border border-[#013FF6]/20">
                    <div class="text-center md:text-left">
                        <h3 class="text-lg font-semibold text-black">Sampah Kaleng</h3>
                        <p class="text-white-700 text-sm mt-1">1 Kaleng = <span class="bg-[#46c43d] text-white px-3 py-1 rounded-full font-bold shadow-md">2 Poin</span>
                        <p class="text-white-500 text-sm">(Tidak tergantung satuan ml)</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('hidden');
        }

        function confirmLogout() {
            const confirmAction = confirm("Apakah Anda yakin ingin logout?");
            if (confirmAction) {
                document.getElementById('logoutForm').submit();
            }
        }

        window.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            const button = document.querySelector('button[onclick="toggleUserMenu()"]');
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

</body>
</html>