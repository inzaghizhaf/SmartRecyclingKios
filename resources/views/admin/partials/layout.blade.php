<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} | Smart Recycling Kiosk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .sidebar-link.active, .sidebar-link:hover { background: #46c43d; color: #fff; }
        .table-cell { padding: .85rem 1rem; border-top: 1px solid #eef2f7; }
    </style>
</head>
<body class="bg-[#eefaf0] text-slate-900 relative overflow-x-hidden">
<!-- Background Eco -->
<div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">

    <!-- Daun kiri bawah -->
    <div class="absolute left-0 bottom-0 opacity-10">
        <i class="ph-fill ph-leaf text-[260px] text-green-500"></i>
    </div>

    <!-- Recycle kanan atas -->
    <div class="absolute right-0 top-20 opacity-10">
        <i class="ph-fill ph-recycle text-[220px] text-green-500"></i>
    </div>

    <!-- Titik kanan -->
    <div class="absolute right-12 top-1/2 opacity-20">
        <div class="grid grid-cols-3 gap-2">
            @for($i=0;$i<18;$i++)
                <div class="w-2 h-2 rounded-full bg-green-400"></div>
            @endfor
        </div>
    </div>

</div>
@php
    $currentUser = auth()->user();
    $isSuper = $currentUser->role === 'super_admin';
    $dashboardRoute = $isSuper ? route('super-admin.dashboard') : route('admin.dashboard');
@endphp

<div class="min-h-screen flex">
    <aside class="w-64 bg-slate-800 text-white fixed inset-y-0 left-0">
        <div class="h-16 bg-[#46c43d] flex items-center px-5 gap-3">
            <img src="{{ asset('images/srk2logo.png') }}" alt="Smart Recycling Kiosk" class="h-10 w-auto">
        </div>

        <div class="px-5 py-6 flex items-center gap-3 border-b border-slate-700">
            <img src="{{ asset('images/user-icon.png') }}" alt="Admin" class="w-12 h-12 rounded-full bg-white">
            <div>
                <p class="font-bold leading-tight">{{ $currentUser->nama_lengkap ?? $currentUser->name }}</p>
                <p class="text-xs text-slate-300">{{ $isSuper ? 'Super Administrator' : 'Administrator' }}</p>
                <div class="flex items-center gap-2 mt-1">
                <span class="w-2.5 h-2.5 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-xs text-green-300 font-medium">Online</span>
            </div>
            </div>
        </div>

        <nav class="p-4 space-y-2 text-sm font-semibold">

        {{-- Dashboard --}}
        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('super-admin.dashboard') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ $dashboardRoute }}">
            <i class="ph-fill ph-house text-xl"></i>
            <span>Dashboard</span>
        </a>

        {{-- Kelola Admin --}}
        @if($isSuper)
        <a class="sidebar-link {{ request()->routeIs('super-admin.admins.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ route('super-admin.admins.index') }}">
            <i class="ph-fill ph-user-gear text-xl"></i>
            <span>Kelola Admin</span>
        </a>
        @endif

        {{-- Kelola User --}}
        <a class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ route('admin.users.index') }}">
            <i class="ph-fill ph-users-three text-xl"></i>
            <span>Kelola User</span>
        </a>

        {{-- ACC Penukaran --}}
        <a class="sidebar-link {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ route('admin.withdrawals.index') }}">
            <i class="ph-fill ph-check-circle text-xl"></i>
            <span>ACC Penukaran</span>
        </a>

        {{-- Harga Sampah --}}
        <a class="sidebar-link {{ request()->routeIs('admin.prices.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ route('admin.prices.index') }}">
            <i class="ph-fill ph-tag text-xl"></i>
            <span>Harga Sampah</span>
        </a>

        {{-- Data Transaksi --}}
        <a class="sidebar-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md"
            href="{{ route('admin.transactions.index') }}">
            <i class="ph-fill ph-receipt text-xl"></i>
            <span>Data Transaksi</span>
        </a>

        {{-- Carbon Calculator --}}
        <a
            href="{{ route('admin.carbon.index') }}"
            class="sidebar-link {{ request()->routeIs('admin.carbon.*') ? 'active' : '' }} flex items-center gap-3 px-4 py-3 rounded-md">
            <i class="ph-fill ph-calculator text-xl"></i>
            <span>Carbon Calculator</span>
        </a>

        {{-- Logout --}}
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="pt-2">
            @csrf
            <button type="button"
                onclick="confirmLogout()"
                class="sidebar-link w-full flex items-center gap-3 px-4 py-3 rounded-md">
                <i class="ph-fill ph-sign-out text-xl"></i>
                <span>Logout</span>
            </button>
        </form>

    </nav>
    </aside>

    <div class="ml-64 flex-1 min-h-screen">
        <header class="h-16 bg-[#46c43d] text-white flex items-center justify-end px-6 shadow">
            <div class="flex items-center gap-4">
                <div class="bg-white text-slate-700 rounded-md px-4 py-2 text-sm font-semibold">
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>

                <img src="{{ asset('images/user-icon.png') }}"
                    class="w-10 h-10 rounded-full bg-white">

                <span class="font-bold">
                    {{ $isSuper ? 'Super Admin' : 'Admin' }}
                </span>
            </div>

        </header>

        <main class="p-6">
            @if(session('success'))
                <div class="mb-5 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-green-700">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>
<script>
    function confirmLogout() {
        if (confirm("Apakah Anda yakin ingin logout?")) {
            document.getElementById('logoutForm').submit();
        }
    }
</script>
</body>
</html>
