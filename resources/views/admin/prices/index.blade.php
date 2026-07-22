@component('admin.partials.layout', ['title' => 'Harga Sampah'])
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Harga Sampah</h1>
        <p class="text-slate-500">Setting harga tukar poin dan harga per kilogram.</p>
    </div>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 mb-6">
        <h2 class="font-extrabold mb-4">Tambah Harga</h2>
        <form action="{{ route('admin.prices.store') }}" method="POST" class="grid grid-cols-5 gap-4">
            @csrf
            <select name="name" class="border rounded-md px-3 py-2" required>
                <option value="">Pilih Jenis Sampah</option>
                <option value="Botol Plastik">Botol Plastik</option>
                <option value="Kaleng Aluminium">Kaleng Aluminium</option>
            </select>
            <input name="price_per_point" type="number" step="0.01" placeholder="Harga per poin" class="border rounded-md px-3 py-2" required>
            <input name="price_per_kg" type="number" step="0.01" placeholder="Harga per kg" class="border rounded-md px-3 py-2">
            <input name="description" placeholder="Deskripsi" class="border rounded-md px-3 py-2">
            <button class="bg-[#46c43d] text-white rounded-md font-bold">Tambah</button>
        </form>
    </section>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr><th class="table-cell">Nama</th><th class="table-cell">Per Poin</th><th class="table-cell">Per Kg</th><th class="table-cell">Deskripsi</th><th class="table-cell">Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($prices as $price)
                <tr>
                    <td class="table-cell font-bold">{{ $price->name }}</td>
                    <td class="table-cell">Rp {{ number_format($price->price_per_point, 0, ',', '.') }}</td>
                    <td class="table-cell">{{ $price->price_per_kg ? 'Rp ' . number_format($price->price_per_kg, 0, ',', '.') : '-' }}</td>
                    <td class="table-cell">{{ $price->description ?? '-' }}</td>
                    <td class="table-cell">
                        <details class="inline-block">
                            <summary class="cursor-pointer bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold">Edit</summary>
                            <form action="{{ route('admin.prices.update', $price) }}" method="POST" class="absolute z-10 mt-2 bg-white border rounded-lg shadow-lg p-4 grid gap-3 w-80">
                                @csrf @method('PUT')
                                <input name="name" value="{{ $price->name }}" class="border rounded px-3 py-2" required>
                                <input name="price_per_point" type="number" step="0.01" value="{{ $price->price_per_point }}" class="border rounded px-3 py-2" required>
                                <input name="price_per_kg" type="number" step="0.01" value="{{ $price->price_per_kg }}" class="border rounded px-3 py-2">
                                <input name="description" value="{{ $price->description }}" class="border rounded px-3 py-2">
                                <button class="bg-green-600 text-white rounded px-3 py-2 font-bold">Simpan</button>
                            </form>
                        </details>
                        <form action="{{ route('admin.prices.destroy', $price) }}" method="POST" class="inline" onsubmit="return confirm('Hapus harga ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="table-cell text-slate-500" colspan="5">Belum ada harga.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $prices->links() }}</div>
    </section>
@endcomponent
