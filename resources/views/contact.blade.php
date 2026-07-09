<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Contact Us</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="flex justify-between items-center text-white px-6 py-4 shadow-md" style="background-color:#46c43d;">
        <!-- Logo + User Button -->
        <div class="flex items-center space-x-3">
            <button onclick="toggleUserMenu()" class="relative focus:outline-none">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" 
                     class="w-9 h-9 rounded-full border-2 border-white hover:opacity-80 transition">
            </button>
            <img src="{{ asset('images/srklogo.png') }}" alt="Logo SiTukar" class="w-28 h-auto ml-2">
        </div>

        <!-- Logout Button -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="button"
                onclick="confirmLogout()"
                class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold">
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
            <ul class="space-y-2">
                <li><a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Dashboard</a></li>
                <li><a href="{{ url('/list') }}" class="block px-3 py-2 rounded hover:bg-blue-100">List</a></li>
                <li><a href="{{ url('/contact') }}" class="block px-3 py-2 rounded hover:bg-blue-100">Contact Us</a></li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <main class="flex-1 container mx-auto px-6 py-10">

        <!-- Judul Halaman -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#46c43d]">Menu Contact Us</h1>
        </div>

        <!-- Kontak Kami -->
        <div class="bg-white rounded-2xl shadow-lg p-8 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
            <p class="text-gray-600 mb-6">
                Jika Anda memiliki pertanyaan atau kendala, silakan hubungi kami melalui kontak berikut:
            </p>

            <div class="flex flex-col md:flex-row justify-center items-center gap-8">
                <!-- Instagram -->
                <a href="https://instagram.com/situkaruns" target="_blank"
                   class="flex items-center bg-[#F3F6FF] hover:bg-[#E0E7FF] px-6 py-3 rounded-xl shadow-md transition border border-[#013FF6]/20">
                    <img src="{{ asset('images/instagram.webp') }}" alt="Instagram" 
                         class="w-6 h-6 mr-3">
                    <span class="text-[#013FF6] font-semibold">Instagram</span>
                </a>

                <!-- WhatsApp -->
                <a href="https://wa.me/6285283968556" target="_blank"
                   class="flex items-center bg-[#F3F6FF] hover:bg-[#E0E7FF] px-6 py-3 rounded-xl shadow-md transition border border-[#ACEC00]/30">
                    <img src="{{ asset('images/whatsapp.webp') }}" alt="WhatsApp" 
                         class="w-6 h-6 mr-3">
                    <span class="text-green-700 font-semibold">WhatsApp</span>
                </a>
            </div>

            <!-- Tambahan Hiasan Bawah -->
            <div class="mt-8 border-t border-gray-300 pt-4">
                <p class="text-gray-500 text-sm">
                    Kami akan dengan senang hati membantu Anda 💬
                </p>
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