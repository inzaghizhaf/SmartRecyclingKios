@component('admin.partials.layout', ['title' => $title])
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold">{{ $title }}</h1>
            <p class="text-slate-500">Tambah, ubah, dan hapus akun {{ $role === 'admin' ? 'administrator' : 'pengguna' }}.</p>
        </div>
    </div>

    <section class="bg-white rounded-lg shadow-sm border border-slate-200 p-5 mb-6">
        <h2 class="font-extrabold mb-4">Tambah {{ $role === 'admin' ? 'Admin' : 'User' }}</h2>
        <form action="{{ $role === 'admin' ? route('super-admin.admins.store') : route('admin.users.store') }}" method="POST" class="grid grid-cols-5 gap-4">
            @csrf
            <input type="hidden" name="role" value="{{ $role }}">
            <input name="nama_lengkap" placeholder="Nama lengkap" class="border rounded-md px-3 py-2" required>
            <input name="email" type="email" placeholder="Email" class="border rounded-md px-3 py-2" required>
            <input name="nomor_telepon" placeholder="Nomor telepon" class="border rounded-md px-3 py-2">
            <input name="password" type="password" placeholder="Password" class="border rounded-md px-3 py-2" required>
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
                        <details class="inline-block">
                            <summary class="cursor-pointer bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold">Edit</summary>
                            <form action="{{ $role === 'admin' ? route('super-admin.admins.update', $item) : route('admin.users.update', $item) }}" method="POST" class="absolute z-10 mt-2 bg-white border rounded-lg shadow-lg p-4 grid gap-3 w-80">
                                @csrf @method('PUT')
                                <input name="nama_lengkap" value="{{ $item->nama_lengkap }}" class="border rounded px-3 py-2" required>
                                <input name="email" type="email" value="{{ $item->email }}" class="border rounded px-3 py-2" required>
                                <input name="nomor_telepon" value="{{ $item->nomor_telepon }}" class="border rounded px-3 py-2">
                                <input name="password" type="password" placeholder="Password baru, kosongkan jika tetap" class="border rounded px-3 py-2">
                                <button class="bg-green-600 text-white rounded px-3 py-2 font-bold">Simpan</button>
                            </form>
                        </details>
                        <form action="{{ $role === 'admin' ? route('super-admin.admins.destroy', $item) : route('admin.users.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data ini?')">
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
@endcomponent
