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
        <p class="text-slate-500">Kelola faktor emisi karbon untuk setiap jenis sampah</p>
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

                        <form id="form-{{ $item->id }}" action="{{ route('admin.carbon.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                <input
                                    type="number"
                                    step="0.01"
                                    name="co2_factor"
                                    value="{{ $item->co2_factor }}"
                                    readonly
                                    class="w-24 text-center border border-transparent bg-transparent rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-400">
                            </td>

                            <td class="px-5 py-4 text-center">
                                <input
                                    type="number"
                                    step="0.01"
                                    name="point_per_kg"
                                    value="{{ $item->point_per_kg }}"
                                    readonly
                                    class="w-20 text-center border border-transparent bg-transparent rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-green-400">
                            </td>

                            <input type="hidden" name="tree_factor" value="{{ $item->tree_factor }}">

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
                                        data-editing="false"
                                        onclick="toggleEdit(this, 'form-{{ $item->id }}')"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white transition">
                                        <i class="ph-fill ph-pencil-simple"></i>
                                    </button>
                                </div>
                            </td>

                        </form>

                        <td class="px-2 py-4">
                            <form action="{{ route('admin.carbon.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-500 hover:bg-red-600 text-white transition">
                                    <i class="ph-fill ph-trash"></i>
                                </button>
                            </form>
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

<script>
    // Toggle mode edit inline pada baris tabel
    function toggleEdit(btn, formId) {
        const form = document.getElementById(formId);
        const inputs = form.querySelectorAll('input[type="number"]');
        const editing = btn.dataset.editing === 'true';

        if (!editing) {
            inputs.forEach(inp => {
                inp.readOnly = false;
                inp.classList.remove('border-transparent', 'bg-transparent');
                inp.classList.add('border-gray-300', 'bg-white');
            });
            btn.innerHTML = '<i class="ph-fill ph-check"></i>';
            btn.dataset.editing = 'true';
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        } else {
            if (form.requestSubmit) {
                form.requestSubmit();
            } else {
                form.submit();
            }
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