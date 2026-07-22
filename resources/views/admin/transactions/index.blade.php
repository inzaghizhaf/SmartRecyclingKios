@if($isAdmin)
@component('admin.partials.layout', ['title' => 'Data Transaksi'])

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Data Transaksi</h1>
        <p class="text-slate-500">Monitoring transaksi user.</p>
        </p>
    </div>

    <!-- STAT CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <!-- Total Deposit -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-shopping-cart-simple text-3xl text-green-600"></i>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-500">Total Deposit</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total_deposit'] }}</p>
            <p class="text-sm text-slate-400">
                Rp {{ number_format($stats['total_deposit_rp'],0,',','.') }}
            </p>
        </div>
    </div>

    <!-- Total Withdraw -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-wallet text-3xl text-orange-500"></i>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-500">Total Withdraw</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total_withdraw'] }}</p>
            <p class="text-sm text-slate-400">
                Rp {{ number_format($stats['total_withdraw_rp'],0,',','.') }}
            </p>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-hourglass-medium text-3xl text-red-500"></i>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-500">Pending</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $stats['pending'] }}</p>
            <p class="text-sm text-slate-400">Menunggu ACC</p>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-clipboard-text text-3xl text-blue-600"></i>
        </div>

        <div>
            <p class="text-sm font-semibold text-slate-500">Total Transaksi</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total_transaksi'] }}</p>
            <p class="text-sm text-slate-400">Semua transaksi</p>
        </div>
    </div>

</div>

    <!-- FILTER -->
    <section class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-5 gap-4 items-end">
            <div>
    <label class="text-xs font-bold text-slate-500 block mb-1.5">
        Tanggal
    </label>
            <div class="flex items-center border rounded-lg px-3 py-2.5">
                <span class="text-slate-400 mr-2"></span>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal') }}"
                    class="w-full outline-none text-sm bg-transparent">
            </div>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 block mb-1.5">Jenis</label>
                <select name="jenis" class="border rounded-lg px-3 py-2.5 w-full text-sm">
                    <option value="semua" {{ $jenis=='semua'?'selected':'' }}>Semua</option>
                    <option value="deposit" {{ $jenis=='deposit'?'selected':'' }}>Deposit</option>
                    <option value="withdraw" {{ $jenis=='withdraw'?'selected':'' }}>Withdraw</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 block mb-1.5">Status</label>
                <select name="status" class="border rounded-lg px-3 py-2.5 w-full text-sm">
                    <option value="semua" {{ $status=='semua'?'selected':'' }}>Semua</option>
                    <option value="berhasil" {{ $status=='berhasil'?'selected':'' }}>Berhasil</option>
                    <option value="pending" {{ $status=='pending'?'selected':'' }}>Pending</option>
                    <option value="ditolak" {{ $status=='ditolak'?'selected':'' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-500 block mb-1.5">Cari User</label>
                <div class="flex items-center gap-2 border rounded-lg px-3 py-2.5">
                    <i class="ph ph-magnifying-glass text-slate-400 text-lg"></i>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Nama user..." class="text-sm w-full outline-none">
                </div>
            </div>
            <div class="flex gap-2">
                <button class="bg-[#2fae27] text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <i class="ph ph-magnifying-glass"></i>
                    Cari
                </button>
                <a href="{{ route('transactions.index') }}"
                class="border px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-slate-100">
                    <i class="ph ph-arrow-counter-clockwise"></i>
                    Reset
                </a>
            </div>
        </form>
    </section>

    <!-- TABLE -->
    <section class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3.5 font-bold">No</th>
                    <th class="px-5 py-3.5 font-bold">Nama User</th>
                    <th class="px-5 py-3.5 font-bold">Jenis</th>
                    <th class="px-5 py-3.5 font-bold">Poin</th>
                    <th class="px-5 py-3.5 font-bold">Nominal</th>
                    <th class="px-5 py-3.5 font-bold">Metode</th>
                    <th class="px-5 py-3.5 font-bold">Status</th>
                    <th class="px-5 py-3.5 font-bold">Tanggal</th>
                    <th class="px-5 py-3.5 font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
            @forelse($transactions as $i => $trx)
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-5 py-4 text-slate-500">{{ $transactions->firstItem() + $i }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-sm font-bold shrink-0">
                                {{ strtoupper(substr($trx->nama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ $trx->nama }}</p>
                                <p class="text-xs text-slate-400">{{ $trx->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold {{ $trx->jenis=='Deposit' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $trx->jenis }}
                        </span>
                    </td>
                    <td class="px-5 py-4 font-bold {{ str_starts_with($trx->poin,'+') ? 'text-green-600' : 'text-red-600' }}">{{ $trx->poin }}</td>
                    <td class="px-5 py-4 font-bold text-slate-800">Rp{{ number_format($trx->nominal,0,',','.') }}</td>
                    <td class="px-5 py-4 text-slate-600">{{ $trx->metode }}</td>
                    <td class="px-5 py-4">
                        <span class="px-2.5 py-1 rounded-md text-xs font-bold
                            {{ $trx->status=='Berhasil' ? 'bg-green-100 text-green-700' : ($trx->status=='Ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $trx->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-slate-500">{{ $trx->tanggal->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-4">
                        <button type="button"
                            onclick="alert('ID: {{ $trx->id }}\nNama: {{ $trx->nama }}\nJenis: {{ $trx->jenis }}\nNominal: Rp{{ number_format($trx->nominal,0,',','.') }}\nMetode: {{ $trx->metode }}\nStatus: {{ $trx->status }}')"
                            class="bg-[#2fae27] hover:bg-[#268e20] text-white px-3 py-1.5 rounded-md text-xs font-bold whitespace-nowrap">
                            ⊙ Detail
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td class="px-5 py-8 text-center text-slate-400" colspan="9">Belum ada transaksi.</td></tr>
            @endforelse
            </tbody>
        </table>

        <!-- FOOTER: info + per-halaman + pagination -->
        <div class="px-5 py-4 flex justify-between items-center border-t border-slate-100 flex-wrap gap-3">
            <span class="text-sm text-slate-500">
                Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} data
            </span>

            <div class="flex items-center gap-4">
                <nav class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if ($transactions->onFirstPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">«</span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm">«</a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($transactions->getUrlRange(1, $transactions->lastPage()) as $page => $url)
                        @if ($page == $transactions->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#2fae27] text-white text-sm font-bold">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-500 hover:bg-slate-50 text-sm">»</a>
                    @else
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">»</span>
                    @endif
                </nav>

                <select onchange="window.location.href=this.value" class="border rounded-lg px-3 py-2 text-sm text-slate-600">
                    @foreach([6, 10, 20, 50] as $size)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $size]) }}" {{ request('per_page', 6) == $size ? 'selected' : '' }}>
                            {{ $size }} / halaman
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

@endcomponent
@else
    {{-- Tampilan sederhana untuk user biasa (bukan admin) --}}
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Transaksi Saya</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 min-h-screen p-6">
        <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-6">
            <h1 class="text-xl font-bold mb-4 text-[#46c43d]">Riwayat Transaksi Saya</h1>
            <table class="w-full text-sm">
                <thead class="text-left text-gray-500 border-b">
                    <tr><th class="py-2">Tipe</th><th>Poin</th><th>Nominal</th><th>Tanggal</th></tr>
                </thead>
                <tbody>
                @forelse($transactions as $t)
                    <tr class="border-b last:border-0">
                        <td class="py-2 capitalize">{{ $t->type }}</td>
                        <td>{{ $t->points }}</td>
                        <td>Rp{{ number_format($t->amount,0,',','.') }}</td>
                        <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada transaksi.</td></tr>
                @endforelse
                </tbody>
            </table>
            <div class="mt-4">{{ $transactions->links() }}</div>
            <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-[#46c43d] font-semibold">← Kembali ke Dashboard</a>
        </div>
    </body>
    </html>
@endif