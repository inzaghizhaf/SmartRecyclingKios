<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
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
        <div class="flex items-center space-x-3">
            <!-- User Button -->
            <button onclick="toggleUserMenu()" class="relative focus:outline-none">
                <img src="{{ asset('images/user-icon.png') }}" alt="User" class="w-9 h-9 rounded-full border-2 border-white hover:opacity-80 transition">
            </button>
            <img src="{{ asset('images/srk2logo.png') }}" alt="Logo SiTukar" class="w-28 h-auto ml-2">
        </div>

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

    <!-- Dashboard Content -->
    <main class="flex-1 container mx-auto px-6 py-10">
        <!-- Judul -->
        <div class="mb-6">
             <h1 class="text-2xl font-bold text-green-600 flex items-center gap-2">
            Menu Dashboard
            <i class="ph-fill ph-leaf text-green-500 text-3xl"></i>
            </h1>
        </div>

         <!-- Notifikasi Hijau/Merah -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded-lg mb-6 shadow-md transition-opacity duration-500">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-600 text-red-800 p-4 rounded-lg mb-6 shadow-md transition-opacity duration-500">
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Card Utama -->
        <div class="bg-white rounded-2xl shadow-lg p-8 relative">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Welcome, {{ $user->nama_lengkap }} 👋</h2>
            <p class="text-gray-600 mb-6">{{ $user->email }}</p>

            <!-- Total Poin dan Saldo -->
            <div class="bg-blue-50 p-6 rounded-xl shadow-inner border border-blue-100 relative">
                <div class="flex items-center gap-6">
                    <!-- Icon -->
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-blue-100 to-blue-200 shadow-lg flex items-center justify-center">
                        <i class="ph-fill ph-wallet text-4xl text-blue-600"></i>
                    </div>
                    <!-- Informasi -->
                    <div>
                        <p class="text-gray-600 text-lg font-medium">
                            Saldo yang Didapatkan
                        </p>
                        <h2 id="totalPoin" class="text-5xl font-bold text-[#013FF6] leading-none">
                            {{ $user->points }}
                        </h2>
                        <p id="saldo" class="text-2xl font-bold text-green-600 mt-2">
                            Rp {{ number_format($user->balance,0,',','.') }}
                        </p>
                    </div>
                </div>


                <!-- Tombol Tarik Saldo -->
                <div class="mt-4">
                    <button onclick="openModal()" 
                        class="w-full py-3 text-lg font-semibold rounded-lg shadow-md transition hover:opacity-90"
                        style="background-color:#46c43d; color:#FFFFFF;">
                        Tarik Saldo
                    </button>
                </div>

                <!-- Icon History -->
                <button onclick="toggleHistory()" class="absolute top-6 right-6 text-[#46c43d] hover:text-[#ACEC00]">
                    <i class="ph ph-clock-counter-clockwise text-2xl"></i>
                </button>
            </div>
        </div>

       <!-- ========================= -->
        <!-- Carbon Footprint -->
        <!-- ========================= -->

        <section class="relative overflow-hidden bg-gradient-to-br from-white via-green-50 to-white rounded-3xl shadow-xl border border-green-100 p-8 mt-8">

            <!-- Background Decoration -->
            <div class="absolute -right-10 -top-10 opacity-10">
                <i class="ph-fill ph-leaf text-[220px] text-green-600"></i>
            </div>

            <!-- Header -->
            <div class="flex flex-col lg:flex-row justify-between lg:items-center gap-4">

                <div class="flex items-center gap-4">

                    <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center shadow">
                        <i class="ph-fill ph-leaf text-4xl text-[#46c43d]"></i>
                    </div>

                    <div>

                        <h2 class="text-2xl font-bold text-grey-800">
                            Jejak Karbon Anda
                        </h2>

                        <p class="text-gray-600 text-base">
                            Dampak positif dari aktivitas daur ulang Anda
                        </p>

                    </div>

                </div>

                <span
                    class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-5 py-2 rounded-full font-semibold shadow">

                    <i class="ph-fill ph-medal"></i>

                    {{ $badge }}

                </span>

            </div>

            <!-- Total Carbon -->
            <div class="text-center py-10">

                <p class="text-gray-600 text-lg font-medium">
                    Total Pengurangan Emisi
                </p>

                <h1 class="text-5xl font-bold text-[#46c43d] leading-none mt-3">

                    {{ number_format($carbonSaved,2) }}

                </h1>

                <p class="text-xl font-semibold text-gray-600 mt-3">

                    kg CO₂e

                </p>

            </div>

            <!-- Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">

                <!-- Plastik -->

                <div class="bg-white rounded-2xl p-6 shadow border hover:shadow-lg transition">

                    <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center mb-4">

                        <i class="ph-fill ph-jar text-3xl text-green-600"></i>

                    </div>

                    <p class="text-gray-500">
                        Botol Plastik
                    </p>

                    <h2 class="text-4xl font-bold mt-2">
                        {{ $plasticCount }}
                    </h2>

                    <p class="text-green-600 font-semibold mt-1">
                        {{ number_format($plasticCarbon,2) }} kg CO₂e
                    </p>

                </div>

                <!-- Kaleng -->

                <div class="bg-white rounded-2xl p-6 shadow border hover:shadow-lg transition">

                    <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center mb-4">

                        <i class="ph-fill ph-cylinder text-3xl text-blue-600"></i>

                    </div>

                    <p class="text-gray-500">
                        Kaleng
                    </p>

                    <h2 class="text-4xl font-bold mt-2">
                        {{ $canCount }}
                    </h2>

                    <p class="text-blue-600 font-semibold mt-1">
                        {{ number_format($canCarbon,2) }} kg CO₂e
                    </p>

                </div>

                <!-- Total Item -->

                <div class="bg-white rounded-2xl p-6 shadow border hover:shadow-lg transition">

                    <div class="w-14 h-14 rounded-xl bg-yellow-100 flex items-center justify-center mb-4">

                        <i class="ph-fill ph-recycle text-3xl text-yellow-600"></i>

                    </div>

                    <p class="text-gray-500">
                        Sampah Didaur Ulang
                    </p>

                    <h2 class="text-4xl font-bold mt-2">

                        {{ $plasticCount + $canCount }}

                    </h2>

                    <p class="text-yellow-600 font-semibold mt-1">

                        Item

                    </p>

                </div>

                <!-- Pohon -->

                <div class="bg-white rounded-2xl p-6 shadow border hover:shadow-lg transition">

                    <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">

                        <i class="ph-fill ph-tree-evergreen text-3xl text-emerald-600"></i>

                    </div>

                    <p class="text-gray-500">
                        Setara Pohon
                    </p>

                    <h2 class="text-4xl font-bold mt-2">

                        {{ number_format($treeEquivalent,2) }}

                    </h2>

                    <p class="text-emerald-600 font-semibold mt-1">

                        Pohon

                    </p>

                </div>

            </div>

            <!-- Progress -->
            <div class="mt-10">

                <div class="flex justify-between mb-3">

                    <span class="font-semibold text-gray-700">

                        Target Bulanan

                    </span>

                    <span class="font-semibold text-[#46c43d]">

                        {{ number_format($carbonSaved,2) }} / {{ $targetCarbon }} kg

                    </span>

                </div>

                <div class="h-5 rounded-full bg-gray-200 overflow-hidden">

                    <div
                        class="h-full rounded-full bg-gradient-to-r from-green-500 to-lime-400 transition-all duration-700"
                        style="width: {{ $progressCarbon }}%;">
                    </div>

                </div>

                <div class="text-right mt-2 text-sm text-gray-500">

                    {{ number_format($progressCarbon,0) }}% Target Tercapai

                </div>

            </div>

            <!-- Eco Fact -->
            <div class="mt-8 bg-green-100 rounded-2xl p-5 flex items-start gap-4">

                <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center">

                    <i class="ph-fill ph-lightbulb text-2xl text-yellow-500"></i>

                </div>

                <div>

                    <h3 class="font-bold text-green-700">
                        Fakta Lingkungan
                    </h3>

                    <p class="text-gray-700 mt-1">

                        Mendaur ulang satu botol plastik dapat mengurangi emisi karbon sekitar
                        <strong>0,05 kg CO₂e</strong>.
                        Teruskan kebiasaan baik Anda untuk membantu menjaga bumi.

                    </p>

                </div>

            </div>

        </section>

        <!-- Logout Section -->
        <div class="mt-10 text-center space-y-3">
            <p class="text-gray-600 text-sm">Silahkan logout jika sudah selesai</p>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" 
                    onclick="confirmLogout()" 
                    class="bg-red-600 hover:bg-red-600 text-white text-lg font-semibold px-16 py-4 rounded-2xl shadow-lg transition">
                    <i class="ph-fill ph-sign-out"></i>
                    Logout
                </button>
            </form>
        </div>
    </main>

    <!-- Modal Tarik Saldo -->
    <div id="withdrawModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Tarik Saldo</h3>
            <form id="withdrawForm" method="POST" action="{{ route('tarik.saldo') }}">
                @csrf
                <label class="block text-sm font-semibold text-gray-600 mb-2">Metode Penarikan</label>
                <div class="relative">
                    <select name="metode" id="metode" 
                        class="w-full border rounded-lg p-2 mb-4 appearance-none focus:ring-2 focus:ring-green-400 focus:border-green-400 cursor-pointer"
                        size="1" required onfocus="this.size=5" onblur="this.size=1" onchange="this.size=1; this.blur();">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Gopay">Gopay</option>
                        <option value="OVO">OVO</option>
                        <option value="Shopeepay">Shopeepay</option>
                        <option value="Bank BNI">Bank BNI</option>
                        <option value="Bank BCA">Bank BCA</option>
                        <option value="Bank Mandiri">Bank Mandiri</option>
                        <option value="Bank Jateng">Bank Jateng</option>
                        <option value="Bank BRI">Bank BRI</option>
                    </select>
                    <span class="absolute right-3 top-3 text-gray-500">▼</span>
                </div>

                <label class="block text-sm font-semibold text-gray-600 mb-2">Nomor Rekening / No HP</label>
                <input type="text" name="nomor" id="nomor" class="w-full border rounded-lg p-2 mb-4" placeholder="Masukkan nomor rekening / HP" required>

                <label class="block text-sm font-semibold text-gray-600 mb-2">Jumlah Penarikan (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="w-full border rounded-lg p-2 mb-4" placeholder="Minimum Rp10.000" required>

                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-lg text-white font-semibold" style="background-color:#46c43d;">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal History -->
<div id="historyModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-11/12 md:w-1/2 lg:w-1/3 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">History Aktivitas Poin Hari Ini</h3>
            <button onclick="toggleHistory()" class="text-gray-500 hover:text-[#013FF6] text-2xl">&times;</button>
        </div>

        <div class="space-y-3">
            @if($history)
                <p><strong>Total Sampah:</strong> {{ $history->total_sampah }} item</p>
                <p><strong>Total Poin:</strong> {{ $history->total_poin }} poin</p>
                <p><strong>Total Saldo:</strong> Rp {{ number_format($history->total_rp, 0, ',', '.') }}</p>
            @else
                <p><strong>Total Sampah:</strong> 0 item</p>
                <p><strong>Total Poin:</strong> 0 poin</p>
                <p><strong>Total Saldo:</strong> Rp 0</p>
            @endif

            <p class="text-gray-500 text-sm italic mt-2">Data diperbarui otomatis setiap hari</p>
        </div>
    </div>
</div>


    <script>
        function toggleUserMenu() {
            document.getElementById('userMenu').classList.toggle('hidden');
        }

        function openModal() {
            document.getElementById('withdrawModal').classList.remove('hidden');
        }
        function closeModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }

        function toggleHistory() {
            document.getElementById('historyModal').classList.toggle('hidden');
        }

        document.getElementById('withdrawForm').addEventListener('submit', function(e) {
            const jumlah = parseInt(document.getElementById('jumlah').value);
            if (jumlah < 10000) {
                e.preventDefault();
                alert('Minimal penarikan adalah Rp 10.000');
            }
        });

        function confirmLogout() {
            const confirmAction = confirm("Apakah Anda yakin ingin logout?");
            if (confirmAction) document.getElementById('logoutForm').submit();
        }

        // Tutup dropdown kalau klik di luar
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            const button = document.querySelector('button[onclick="toggleUserMenu()"]');
            if (!menu.contains(e.target) && !button.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>

    <script>
document.addEventListener("DOMContentLoaded", function() {
    fetch("/api/esp32/set-user", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: {{ Auth::user()->id }} })
    })
    .then(res => res.json())
    .then(data => console.log("User ID dikirim ke ESP32:", data))
    .catch(err => console.error("Gagal kirim user_id:", err));
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Jalankan fungsi pertama kali saat halaman dibuka
    updateDashboard();

    // Jalankan fungsi update setiap 3 detik (3000 ms)
    setInterval(updateDashboard, 3000);
});

</script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const userId = {{ Auth::user()->id }};

    // Jalankan pertama kali
    updateDashboard();

    // Ulangi setiap 3 detik
    setInterval(updateDashboard, 1000);

    function updateDashboard() {
        fetch(`/api/user/${userId}/refresh`)
            .then(response => response.json())
            .then(data => {
                if (data.points !== undefined) {
                    document.getElementById('totalPoin').textContent = data.points;
                    document.getElementById('saldo').textContent = 
                        'Rp ' + Number(data.balance).toLocaleString('id-ID');
                    console.log('🔁 Dashboard diperbarui:', data);
                } else {
                    console.warn('Data tidak valid:', data);
                }
            })
            .catch(error => console.error('Gagal ambil data terbaru:', error));
    }
});
</script>
</body>
</html>
