@component('admin.partials.layout', ['title' => $isSuperAdmin ? 'Dashboard Super Admin' : 'Dashboard Admin'])
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">{{ $isSuperAdmin ? 'Dashboard Super Admin' : 'Dashboard Admin' }}</h1>
        <p class="text-slate-500">Selamat datang kembali, {{ $user->nama_lengkap ?? $user->name }}.</p>
    </div>

    <div class="grid {{ $isSuperAdmin ? 'grid-cols-5' : 'grid-cols-4' }} gap-5 mb-6">

        {{-- Total User --}}
        <div class="bg-green-50 border border-green-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-green-100 flex items-center justify-center">
                    <i class="ph-fill ph-users-three text-3xl text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">Total User</p>
                    <p class="text-3xl font-extrabold mt-1">{{ $stats['users'] }}</p>
                    <p class="text-xs text-slate-500">Semua terdaftar</p>
                </div>
            </div>
        </div>
        @if($isSuperAdmin)

        {{-- Total Admin --}}
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="ph-fill ph-user-gear text-3xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">Total Admin</p>
                    <p class="text-3xl font-extrabold mt-1">{{ $stats['admins'] }}</p>
                    <p class="text-xs text-slate-500">Administrator aktif</p>
                </div>
            </div>
        </div>

        {{-- Total Mesin --}}
        <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl bg-orange-100 flex items-center justify-center">
                    <i class="ph-fill ph-desktop text-3xl text-orange-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">Total Mesin</p>
                    <p class="text-3xl font-extrabold mt-1">{{ $stats['machines'] }}</p>
                    <p class="text-xs text-slate-500">Mesin aktif</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Pending --}}
        <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="ph-fill ph-clock-countdown text-3xl text-amber-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">
                        Pending Penukaran
                    </p>
                    <p class="text-3xl font-extrabold mt-1">
                        {{ $stats['pending'] }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Menunggu ACC
                    </p>
                </div>
            </div>
        </div>

        {{-- Berhasil --}}
        <div class="bg-sky-50 border border-sky-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl bg-sky-100 flex items-center justify-center">
                    <i class="ph-fill ph-check-circle text-3xl text-sky-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">
                        Berhasil
                    </p>
                    <p class="text-3xl font-extrabold mt-1">
                        {{ $stats['approved'] }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Penukaran berhasil
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Saldo --}}
        <div class="bg-violet-50 border border-violet-100 rounded-xl p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-xl bg-violet-100 flex items-center justify-center">
                    <i class="ph-fill ph-wallet text-3xl text-violet-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-600">
                        Total Saldo
                    </p>
                    <p class="text-2xl font-extrabold mt-1">
                        Rp{{ number_format($stats['balance'],0,',','.') }}
                    </p>
                    <p class="text-xs text-slate-500">
                        Saldo keseluruhan
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid {{ $isSuperAdmin ? 'grid-cols-3' : 'grid-cols-2' }} gap-5">
        <section class="bg-white rounded-lg shadow-sm border border-slate-200 {{ $isSuperAdmin ? '' : 'col-span-1' }}">
            <div class="p-5 border-b border-slate-100 flex justify-between">
                <h2 class="font-extrabold">Penukaran Terbaru</h2>
                <a href="{{ route('admin.withdrawals.index') }}" class="text-sm font-bold text-green-700">Lihat Semua</a>
            </div>
            <table class="w-full text-sm">
                <thead class="text-left text-slate-500">
                    <tr><th class="table-cell">Nama</th><th class="table-cell">Nominal</th><th class="table-cell">Status</th><th class="table-cell">Aksi</th></tr>
                </thead>
                <tbody>
                @forelse($recentWithdrawals as $item)
                    <tr>
                        <td class="table-cell">{{ $item->user->nama_lengkap ?? '-' }}</td>
                        <td class="table-cell">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        <td class="table-cell">
                            <span class="px-2 py-1 rounded text-xs font-bold {{ $item->status === 'approved' ? 'bg-green-100 text-green-700' : ($item->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="table-cell">
                            @if($item->status === 'pending')
                                <form action="{{ route('admin.withdrawals.update', $item) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="bg-green-600 text-white px-3 py-1 rounded text-xs font-bold">ACC</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td class="table-cell text-slate-500" colspan="4">Belum ada penukaran.</td></tr>
                @endforelse
                </tbody>
            </table>
        </section>

        <section class="bg-white rounded-lg shadow-sm border border-slate-200 p-5">
            <h2 class="font-extrabold mb-5">Penukaran 7 Hari Terakhir</h2>
            <div class="h-56 flex items-end gap-3 border-l border-b border-slate-200 px-4">
                @foreach($chartValues as $index => $value)
                    @php $height = max(12, min(100, $value * 12)); @endphp
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-green-500 rounded-t" style="height: {{ $height }}px"></div>
                        <span class="text-xs text-slate-500">{{ $chartLabels[$index] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        @if($isSuperAdmin)
            <section class="bg-white rounded-lg shadow-sm border border-slate-200">
                <div class="p-5 border-b border-slate-100">
                    <h2 class="font-extrabold">Aktivitas Admin Terbaru</h2>
                </div>
                <table class="w-full text-sm">
                    <thead class="text-left text-slate-500"><tr><th class="table-cell">Admin</th><th class="table-cell">Aktivitas</th><th class="table-cell">Waktu</th></tr></thead>
                    <tbody>
                    @forelse($activityLogs as $log)
                        <tr>
                            <td class="table-cell">{{ $log->admin->nama_lengkap ?? '-' }}</td>
                            <td class="table-cell">{{ $log->activity }}</td>
                            <td class="table-cell">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td class="table-cell text-slate-500" colspan="3">Belum ada aktivitas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@endcomponent
