@component('admin.partials.layout', ['title' => $title])
    @php
        $isSuperAdminRoute = request()->routeIs('super-admin.*');
        $storeRoute = $role === 'admin'
            ? ($isSuperAdminRoute ? route('super-admin.admins.store', [], false) : route('admin.admins.store', [], false))
            : route('admin.users.store', [], false);
    @endphp
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold">{{ $title }}</h1>
            <p class="text-slate-500">Tambah, ubah, dan hapus akun {{ $role === 'admin' ? 'administrator' : 'pengguna' }}.</p>
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 mb-6">
        <h2 class="font-extrabold mb-4">Tambah {{ $role === 'admin' ? 'Admin' : 'User' }}</h2>
        <form action="{{ $storeRoute }}" method="POST" class="grid grid-cols-5 gap-4">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <input name="nama_lengkap" placeholder="Nama lengkap" class="border rounded-md px-3 py-2" required>
            <input name="email" type="email" placeholder="Email" class="border rounded-md px-3 py-2" required>
            <input name="nomor_telepon" placeholder="Nomor telepon" class="border rounded-md px-3 py-2">
            <div class="relative">
                <input id="newAccountPassword" name="password" type="password" placeholder="Password" class="w-full border rounded-md px-3 py-2 pr-10" required>
                <button type="button" onclick="togglePasswordVisibility('newAccountPassword', this)" class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700" aria-label="Tampilkan password">
                    <i class="ph-bold ph-eye"></i>
                </button>
            </div>
            <button class="bg-[#46c43d] text-white rounded-md font-bold">Tambah</button>
        </form>
    </section>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-500">
                <tr>
                    <th class="table-cell">Nama</th>
                    <th class="table-cell">Email</th>
                    <th class="table-cell">Telepon</th>
                    <th class="table-cell">Saldo</th>
                    <th class="table-cell">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $item)
                <tr>
                    <td class="table-cell font-bold">{{ $item->nama_lengkap }}</td>
                    <td class="table-cell">{{ $item->email }}</td>
                    <td class="table-cell">{{ $item->nomor_telepon }}</td>
                    <td class="table-cell">Rp {{ number_format($item->balance, 0, ',', '.') }}</td>
                    <td class="table-cell">
                        <button
                            type="button"
                            onclick="openEditModal(this)"
                            data-action="{{ $role === 'admin' ? ($isSuperAdminRoute ? route('super-admin.admins.update', $item, false) : route('admin.admins.update', $item, false)) : route('admin.users.update', $item, false) }}"
                            data-name="{{ $item->nama_lengkap }}"
                            data-email="{{ $item->email }}"
                            data-phone="{{ $item->nomor_telepon }}"
                            class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold hover:bg-blue-700">
                            Edit
                        </button>
                        <form action="{{ $role === 'admin' ? ($isSuperAdminRoute ? route('super-admin.admins.destroy', $item, false) : route('admin.admins.destroy', $item, false)) : route('admin.users.destroy', $item, false) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="bg-red-600 text-white px-3 py-1 rounded text-xs font-bold">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td class="table-cell text-slate-500" colspan="5">Belum ada data.</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $users->links() }}</div>
    </section>

    <div id="editUserModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4" onclick="closeEditModal(event)">
        <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl" onclick="event.stopPropagation()">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">Edit {{ $role === 'admin' ? 'Admin' : 'User' }}</h2>
                    <p class="mt-1 text-sm text-slate-500">Perbarui informasi akun kemudian simpan perubahan.</p>
                </div>
                <button type="button" onclick="closeEditModal()" class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup form edit">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form id="editUserForm" method="POST" class="grid gap-4">
                @csrf
                @method('PUT')
                <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Nama lengkap
                    <input id="editNamaLengkap" name="nama_lengkap" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
                </label>
                <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Email
                    <input id="editEmail" name="email" type="email" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200" required>
                </label>
                <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Nomor telepon
                    <input id="editNomorTelepon" name="nomor_telepon" class="rounded-lg border border-slate-300 px-3 py-2.5 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                </label>
                <label class="grid gap-1.5 text-sm font-semibold text-slate-700">Password baru <span class="font-normal text-slate-400">(opsional)</span>
                    <span class="relative">
                        <input id="editPassword" name="password" type="password" placeholder="Kosongkan jika tidak diubah" class="w-full rounded-lg border border-slate-300 px-3 py-2.5 pr-11 font-normal focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-200">
                        <button type="button" onclick="togglePasswordVisibility('editPassword', this)" class="absolute inset-y-0 right-0 px-3 text-slate-500 hover:text-slate-700" aria-label="Tampilkan password">
                            <i class="ph-bold ph-eye"></i>
                        </button>
                    </span>
                </label>
                <div class="mt-2 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="rounded-lg bg-slate-100 px-4 py-2.5 font-bold text-slate-700 hover:bg-slate-200">Batal</button>
                    <button class="rounded-lg bg-green-600 px-4 py-2.5 font-bold text-white hover:bg-green-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(button) {
            const modal = document.getElementById('editUserModal');
            const form = document.getElementById('editUserForm');

            form.action = button.dataset.action;
            document.getElementById('editNamaLengkap').value = button.dataset.name;
            document.getElementById('editEmail').value = button.dataset.email;
            document.getElementById('editNomorTelepon').value = button.dataset.phone;
            form.querySelector('[name="password"]').value = '';
            modal.classList.remove('hidden');
            document.getElementById('editNamaLengkap').focus();
        }

        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            button.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            button.innerHTML = isHidden
                ? '<i class="ph-bold ph-eye-slash"></i>'
                : '<i class="ph-bold ph-eye"></i>';
        }

        function closeEditModal(event) {
            if (!event || event.target === document.getElementById('editUserModal')) {
                document.getElementById('editUserModal').classList.add('hidden');
            }
        }
    </script>
@endcomponent
