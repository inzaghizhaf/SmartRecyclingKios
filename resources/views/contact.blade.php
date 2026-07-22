<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Contact Us</title>
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
                <img src="{{ asset('images/user-icon.png') }}" alt="User" 
                     class="w-9 h-9 rounded-full border-2 border-white hover:opacity-80 transition">
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
        <div id="userMenu" 
             class="hidden absolute top-16 left-6 bg-white text-gray-800 rounded-xl shadow-lg w-56 p-4 z-50">
            <div class="flex flex-col items-center mb-4">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" 
                     class="w-16 h-16 rounded-full mb-2">
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
        </div>

        <!-- Kontak Kami -->
       <div class="bg-white rounded-3xl shadow-xl p-10 text-center border border-green-100 relative overflow-hidden">

        <!-- Ornamen Titik -->
        <div class="absolute right-5 top-1/2 -translate-y-1/2 grid grid-cols-3 gap-2 opacity-20">
            @for($i=0;$i<18;$i++)
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
            @endfor
        </div>

        <!-- Icon -->
        <div class="flex justify-center mb-5">
            <div class="w-16 h-16 rounded-full bg-green-100 shadow-md flex items-center justify-center">
                <i class="ph-fill ph-headset text-5xl text-green-600"></i>
            </div>
        </div>

        <!-- Judul -->
        <h2 class="text-2xl font-bold text-gray-800 mb-3">
            Hubungi Kami
        </h2>
        <p class="text-gray-600 mb-8 text-base">
            Jika Anda memiliki pertanyaan atau kendala, silakan hubungi kami melalui kontak berikut:
        </p>
        <div class="flex flex-col md:flex-row justify-center items-center gap-6">

            <!-- Instagram -->
            <a href="https://instagram.com/situkaruns" target="_blank"
            class="flex items-center bg-[#F3F6FF] hover:bg-[#E0E7FF] px-6 py-3 rounded-xl shadow-md transition border border-[#013FF6]/20">
                <img src="{{ asset('images/instagram.webp') }}"
                    class="w-7 h-7 mr-3">

                <span class="text-[#013FF6] font-semibold text-lg">
                    Instagram
                </span>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/6285283968556" target="_blank"
            class="flex items-center bg-[#F3F6FF] hover:bg-[#E0E7FF] px-6 py-3 rounded-xl shadow-md transition border border-[#ACEC00]/30">

                <img src="{{ asset('images/whatsapp.webp') }}"
                    class="w-7 h-7 mr-3">

                <span class="text-green-700 font-semibold text-lg">
                    WhatsApp
                </span>
            </a>
        </div>

        <!-- Tambahan Hiasan Bawah -->
        <div class="mt-8 border-t border-gray-300 pt-5">
            <div class="flex justify-center items-center gap-3">
                <i class="ph-fill ph-heart text-2xl text-green-500"></i>
                <p class="text-gray-500 text-base">
                    Kami akan dengan senang hati membantu Anda 
                </p>
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