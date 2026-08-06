@component('admin.partials.layout', ['title' => 'Carbon Calculator'])

@php
    // Mapping ikon & warna berdasarkan jenis sampah (silakan tambah jika ada jenis baru)
    $iconMap = [
        'Botol Plastik'    => ['icon' => 'ph-flask',              'bg' => 'bg-green-100',  'text' => 'text-green-600'],
        'Kaleng Aluminium' => ['icon' => 'ph-cylinder',            'bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
    ];
    $defaultIcon = ['icon' => 'ph-recycle', 'bg' => 'bg-gray-100', 'text' => 'text-gray-600'];

    $totalJenis  = $calculators->count();
    $totalCo2    = $calculators->sum('co2_factor');
    $totalPoin   = $calculators->sum('point_per_kg');
    $lastUpdated = $calculators->max('updated_at');
@endphp

<!-- Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800">Carbon Calculator</h1>
        <p class="text-slate-500">Kalkulator Carbon Footprint.</p>
    </div>

    <button
        onclick="document.getElementById('modalTambah').classList.remove('hidden')"
        class="flex items-center gap-2 bg-[#2fae27] hover:bg-[#268e20] text-white px-4 py-2 rounded-lg shadow-sm font-bold transition">
        <i class="ph-fill ph-plus-circle text-lg"></i>
        Tambah Data
    </button>
</div>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">

    <!-- Total Jenis Sampah -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-leaf text-3xl text-green-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500">Total Jenis Sampah</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ $totalJenis }}</p>
            <p class="text-sm text-slate-400">Terdaftar</p>
        </div>
    </div>

    <!-- Total Faktor CO2e -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-cloud text-3xl text-orange-500"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500">Total Faktor CO₂e</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ number_format($totalCo2, 3) }}</p>
            <p class="text-sm text-slate-400">kg CO₂e</p>
        </div>
    </div>

    <!-- Total Poin -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-recycle text-3xl text-blue-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500">Total Poin</p>
            <p class="text-3xl font-extrabold text-slate-800">{{ number_format($totalPoin, 0) }}</p>
            <p class="text-sm text-slate-400">Point</p>
        </div>
    </div>

    <!-- Terakhir Diperbarui -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-purple-100 flex items-center justify-center shadow-sm">
            <i class="ph-fill ph-calendar-check text-3xl text-purple-600"></i>
        </div>
        <div>
            <p class="text-sm font-semibold text-slate-500">Terakhir Diperbarui</p>
            <p class="text-2xl font-extrabold text-slate-800">
                {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('d/m/Y') : '-' }}
            </p>
            <p class="text-sm text-slate-400">
                {{ $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->format('H:i') . ' WIB' : '' }}
            </p>
        </div>
    </div>

</div>

<!-- FILTER / SEARCH -->
<section class="bg-white border border-slate-200 rounded-xl shadow-sm p-5 mb-6">
    <label class="text-xs font-bold text-slate-500 block mb-1.5">Cari Jenis Sampah</label>
    <div class="flex items-center gap-2 border rounded-lg px-3 py-2.5 max-w-md">
        <i class="ph ph-magnifying-glass text-slate-400 text-lg"></i>
        <input
            type="text"
            id="searchInput"
            onkeyup="filterTable()"
            placeholder="Cari jenis sampah..."
            class="text-sm w-full outline-none bg-transparent">
    </div>
</section>

<!-- Card Utama: Tabel -->
<section class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">

    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="carbonTable">

            <thead class="bg-slate-50 text-left text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-5 py-3.5 font-bold">No</th>
                    <th class="px-5 py-3.5 font-bold">Jenis Sampah</th>
                    <th class="px-5 py-3.5 font-bold text-center">Faktor CO₂e<br><span class="normal-case text-slate-400">(kg CO₂e / item)</span></th>
                    <th class="px-5 py-3.5 font-bold text-center">Poin per Item<br><span class="normal-case text-slate-400">(point)</span></th>
                    <th class="px-5 py-3.5 font-bold text-center">Status</th>
                    <th class="px-5 py-3.5 font-bold text-center">Terakhir Diperbarui</th>
                    <th class="px-5 py-3.5 font-bold text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">

                @forelse($calculators as $index => $item)

                    @php
                        $style = $iconMap[$item->waste_type] ?? $defaultIcon;
                    @endphp

                    <tr class="hover:bg-slate-50/60 transition searchable-row">
                            <td class="px-5 py-4 text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg {{ $style['bg'] }} flex items-center justify-center">
                                        <i class="ph-fill {{ $style['icon'] }} text-lg {{ $style['text'] }}"></i>
                                    </div>
                                    <span class="font-bold text-slate-800 waste-name">{{ $item->waste_type }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                {{ number_format($item->co2_factor, 2, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                {{ number_format($item->point_per_kg, 2, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center text-slate-500">
                                {{ $item->updated_at ? $item->updated_at->format('d/m/Y H:i') : '-' }}
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        type="button"
                                        onclick="openCarbonEditModal(this)"
                                        data-action="{{ route('admin.carbon.update', $item, false) }}"
                                        data-waste-type="{{ $item->waste_type }}"
                                        data-co2-factor="{{ $item->co2_factor }}"
                                        data-point-per-kg="{{ $item->point_per_kg }}"
                                        data-tree-factor="{{ $item->tree_factor }}"
                                        class="rounded bg-blue-600 px-3 py-1 text-xs font-bold text-white transition hover:bg-blue-700">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.carbon.destroy', $item, false) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded bg-red-600 px-3 py-1 text-xs font-bold text-white transition hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-slate-400">
                            Belum ada data Carbon Calculator.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>
    </div>

    <div class="px-5 py-4 flex justify-between items-center border-t border-slate-100 flex-wrap gap-3">
        <span class="text-sm text-slate-500">
            Menampilkan 1 - {{ $calculators->count() }} dari {{ $calculators->count() }} data
        </span>
        <nav class="flex items-center gap-1">
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">«</span>
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">‹</span>
            <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-[#2fae27] text-white text-sm font-bold">1</span>
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">›</span>
            <span class="w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 text-slate-300 text-sm">»</span>
        </nav>
    </div>

</section>

<!-- Modal Tambah -->
<div id="modalTambah"
     class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">

    <div class="bg-white rounded-2xl p-6 w-[450px] shadow-xl">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center">
                <i class="ph-fill ph-plus-circle text-xl text-green-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">
                Tambah Carbon Calculator
            </h3>
        </div>

        <form action="{{ route('admin.carbon.store') }}" method="POST">

            @csrf

            <div class="mb-3">
                <label class="font-semibold text-slate-700 text-sm">Jenis Sampah</label>
                <select
                    name="waste_type"
                    required
                    class="w-full border rounded-lg px-3 py-2.5 mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">

                    <option value="">Pilih Jenis Sampah</option>
                    <option value="Botol Plastik">Botol Plastik</option>
                    <option value="Kaleng Aluminium">Kaleng Aluminium</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="font-semibold text-slate-700 text-sm">CO₂ Factor</label>
                <input
                    type="number"
                    step="0.01"
                    name="co2_factor"
                    required
                    class="w-full border rounded-lg px-3 py-2.5 mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div class="mb-3">
                <label class="font-semibold text-slate-700 text-sm">Point / Kg</label>
                <input
                    type="number"
                    step="0.01"
                    name="point_per_kg"
                    required
                    class="w-full border rounded-lg px-3 py-2.5 mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div class="mb-5">
                <label class="font-semibold text-slate-700 text-sm">Tree Factor</label>
                <input
                    type="number"
                    step="0.0001"
                    name="tree_factor"
                    required
                    class="w-full border rounded-lg px-3 py-2.5 mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div class="flex justify-end gap-2">

                <button
                    type="button"
                    onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="px-4 py-2 rounded-lg bg-slate-200 hover:bg-slate-300 font-semibold text-slate-700">
                    Batal
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-[#2fae27] hover:bg-[#268e20] text-white font-semibold">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

<!-- Modal Edit -->
<div id="modalEditCarbon" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4" onclick="closeCarbonEditModal(event)">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div>
                <h3 class="text-xl font-extrabold text-slate-800">Edit Carbon Calculator</h3>
                <p class="mt-1 text-sm text-slate-500">Perbarui faktor per jenis sampah kemudian simpan perubahan.</p>
            </div>
            <button type="button" onclick="closeCarbonEditModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup form edit">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        <form id="editCarbonForm" method="POST" class="grid gap-4">
            @csrf
            @method('PUT')
            <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Jenis Sampah
                <input id="editWasteType" name="waste_type" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </label>
            <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Faktor CO₂e <span class="font-normal text-slate-400">(kg CO₂e/item)</span>
                <input id="editCo2Factor" type="number" step="0.01" min="0" name="co2_factor" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </label>
            <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Poin per Item
                <input id="editPointPerKg" type="number" step="0.01" min="0" name="point_per_kg" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </label>
            <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Tree Factor
                <input id="editTreeFactor" type="number" step="0.0001" min="0" name="tree_factor" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </label>
            <div class="mt-2 flex justify-end gap-3">
                <button type="button" onclick="closeCarbonEditModal()" class="rounded-lg bg-slate-100 px-4 py-2.5 font-bold text-slate-700 hover:bg-slate-200">Batal</button>
                <button class="rounded-lg bg-green-600 px-4 py-2.5 font-bold text-white hover:bg-green-700">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCarbonEditModal(button) {
        const form = document.getElementById('editCarbonForm');

        form.action = button.dataset.action;
        document.getElementById('editWasteType').value = button.dataset.wasteType;
        document.getElementById('editCo2Factor').value = button.dataset.co2Factor;
        document.getElementById('editPointPerKg').value = button.dataset.pointPerKg;
        document.getElementById('editTreeFactor').value = button.dataset.treeFactor;
        document.getElementById('modalEditCarbon').classList.remove('hidden');
        document.getElementById('editWasteType').focus();
    }

    function closeCarbonEditModal(event) {
        if (!event || event.target === document.getElementById('modalEditCarbon')) {
            document.getElementById('modalEditCarbon').classList.add('hidden');
        }
    }

    // Filter tabel berdasarkan nama jenis sampah
    function filterTable() {
        const keyword = document.getElementById('searchInput').value.toLowerCase();
        document.querySelectorAll('#carbonTable tbody tr.searchable-row').forEach(row => {
            const name = row.querySelector('.waste-name');
            if (!name) return;
            row.style.display = name.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    }
</script>

@endcomponent
