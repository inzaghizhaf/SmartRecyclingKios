@component('admin.partials.layout', ['title' => 'ACC Penukaran'])
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">ACC Penukaran</h1>
        <p class="text-slate-500">Setujui atau tolak pengajuan tukar saldo user.</p>
    </div>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="table-cell">Nama</th>
                    <th class="table-cell">Nominal</th>
                    <th class="table-cell">Metode</th>
                    <th class="table-cell">Nomor</th>
                    <th class="table-cell">Status</th>
                    <th class="table-cell">Tanggal</th>
                    <th class="table-cell">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($withdrawals as $item)
                <tr>
                    <td class="table-cell font-bold">{{ $item->user->nama_lengkap ?? '-' }}</td>
                    <td class="table-cell">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    <td class="table-cell">{{ $item->metode }}</td>
                    <td class="table-cell">{{ $item->nomor }}</td>
                    <td class="table-cell">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $item->status === 'approved' ? 'bg-green-100 text-green-700' : ($item->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="table-cell">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                    <td class="table-cell">
                        @if($item->status === 'pending')
                            <form action="{{ route('admin.withdrawals.update', $item) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="bg-green-600 text-white px-3 py-1 rounded text-xs font-bold">ACC</button>
                            </form>
                            <form action="{{ route('admin.withdrawals.update', $item) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">Tolak</button>
                            </form>
                        @else
                            <span class="text-xs text-slate-500">Diproses {{ $item->processor->nama_lengkap ?? '-' }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td class="table-cell text-slate-500" colspan="7">Belum ada pengajuan.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $withdrawals->links() }}</div>
    </section>
@endcomponent
