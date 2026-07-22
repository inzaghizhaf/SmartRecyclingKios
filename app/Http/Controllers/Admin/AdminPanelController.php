<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use App\Models\Price;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\CarbonCalculator;

class AdminPanelController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $isSuperAdmin = $user->role === 'super_admin';

        $stats = [
            'users' => User::where('role', 'user')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'pending' => Withdrawal::where('status', 'pending')->count(),
            'approved' => Withdrawal::where('status', 'approved')->count(),
            'balance' => User::sum('balance'),
            'machines' => 0,
        ];

        $recentWithdrawals = Withdrawal::with('user')
            ->latest()
            ->take($isSuperAdmin ? 3 : 5)
            ->get();

        $activityLogs = AdminActivity::with('admin')
            ->latest()
            ->take(5)
            ->get();

        $chartLabels = collect(range(6, 0))->map(fn ($days) => Carbon::today()->subDays($days)->format('d/m'));
        $chartValues = collect(range(6, 0))->map(function ($days) {
            return Withdrawal::whereDate('created_at', Carbon::today()->subDays($days))->count();
        });

        return view('admin.dashboard', compact(
            'user',
            'isSuperAdmin',
            'stats',
            'recentWithdrawals',
            'activityLogs',
            'chartLabels',
            'chartValues'
        ));
    }

    public function users(Request $request)
    {
        $role = $request->routeIs('super-admin.admins.*') ? 'admin' : 'user';
        $title = $role === 'admin' ? 'Kelola Admin' : 'Kelola User';
        $users = User::where('role', $role)->latest()->paginate(10);

        return view('admin.users.index', compact('users', 'role', 'title'));
    }

    public function admins(Request $request)
    {
        $users = User::where('role', 'admin')
            ->latest()
            ->paginate(10);

        $role = 'admin';
        $title = 'Kelola Admin';

        return view('admin.users.index', compact(
            'users',
            'role',
            'title'
        ));
    }

    public function storeUser(Request $request)
    {
        $role = $request->input('role') === 'admin' ? 'admin' : 'user';
        abort_if($role === 'admin' && auth()->user()->role !== 'super_admin', 403);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['user', 'admin'])],
        ]);

        User::create([
            'name' => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? '-',
            'password' => Hash::make($data['password']),
            'konfigurasi_password' => Hash::make($data['password']),
            'role' => $role,
        ]);

        $this->log('Menambah ' . ($role === 'admin' ? 'admin' : 'user') . ' ' . $data['nama_lengkap']);

        return back()->with('success', 'Data berhasil ditambahkan.');
    }

    public function storeAdmin(Request $request)
    {
        abort_if(auth()->user()->role !== 'super_admin', 403);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        User::create([
            'name' => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? '-',
            'password' => Hash::make($data['password']),
            'konfigurasi_password' => Hash::make($data['password']),
            'role' => 'admin',
        ]);

        $this->log('Menambah admin ' . $data['nama_lengkap']);

        return back()->with('success', 'Admin berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        abort_if($user->role === 'admin' && auth()->user()->role !== 'super_admin', 403);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->fill([
            'name' => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? '-',
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->konfigurasi_password = Hash::make($data['password']);
        }

        $user->save();
        $this->log('Update data ' . $user->nama_lengkap);

        return back()->with('success', 'Data berhasil diperbarui.');
    }

    public function updateAdmin(Request $request, User $user)
    {
        abort_if(auth()->user()->role !== 'super_admin', 403);

        abort_if($user->role !== 'admin', 404);

        $data = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $user->fill([
            'name' => $data['nama_lengkap'],
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'nomor_telepon' => $data['nomor_telepon'] ?? '-',
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->konfigurasi_password = Hash::make($data['password']);
        }

        $user->save();

        $this->log('Update admin ' . $user->nama_lengkap);

        return back()->with('success', 'Admin berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        abort_if($user->id === auth()->id(), 422, 'Tidak bisa menghapus akun sendiri.');
        abort_if($user->role === 'admin' && auth()->user()->role !== 'super_admin', 403);

        $name = $user->nama_lengkap;
        $user->delete();
        $this->log('Menghapus akun ' . $name);

        return back()->with('success', 'Data berhasil dihapus.');
    }

    public function destroyAdmin(User $user)
    {
        abort_if(auth()->user()->role !== 'super_admin', 403);

        abort_if($user->role !== 'admin', 404);

        abort_if($user->id === auth()->id(), 422, 'Tidak bisa menghapus akun sendiri.');

        $nama = $user->nama_lengkap;

        $user->delete();

        $this->log('Menghapus admin ' . $nama);

        return back()->with('success', 'Admin berhasil dihapus.');
    }

    public function withdrawals()
    {
        $withdrawals = Withdrawal::with(['user', 'processor'])->latest()->paginate(12);

        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function updateWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
            'admin_note' => ['nullable', 'string', 'max:500'],
        ]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Penukaran ini sudah diproses.');
        }

        $user = $withdrawal->user;
        if ($data['status'] === 'approved') {
            if ($user->balance < $withdrawal->jumlah) {
                return back()->with('error', 'Saldo user tidak mencukupi untuk ACC.');
            }

            $user->balance -= $withdrawal->jumlah;
            $user->points = max(0, $user->points - floor($withdrawal->jumlah / 50));
            $user->save();
        }

        $withdrawal->update([
            'status' => $data['status'],
            'processed_by' => auth()->id(),
            'processed_at' => now(),
            'admin_note' => $data['admin_note'] ?? null,
        ]);

        $label = $data['status'] === 'approved' ? 'ACC' : 'Tolak';
        $this->log($label . ' penukaran ' . $withdrawal->user->nama_lengkap);

        return back()->with('success', 'Status penukaran berhasil diperbarui.');
    }

    public function prices()
    {
        $prices = Price::latest()->paginate(10);

        return view('admin.prices.index', compact('prices'));
    }

    public function storePrice(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_per_point' => ['required', 'numeric', 'min:0'],
            'price_per_kg' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        Price::create($data);
        $this->log('Tambah harga sampah ' . $data['name']);

        return back()->with('success', 'Harga sampah berhasil ditambahkan.');
    }

    public function updatePrice(Request $request, Price $price)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price_per_point' => ['required', 'numeric', 'min:0'],
            'price_per_kg' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $price->update($data);
        $this->log('Update harga sampah ' . $price->name);

        return back()->with('success', 'Harga sampah berhasil diperbarui.');
    }

    public function destroyPrice(Price $price)
    {
        $name = $price->name;
        $price->delete();
        $this->log('Hapus harga sampah ' . $name);

        return back()->with('success', 'Harga sampah berhasil dihapus.');
    }

    public function carbonCalculator()
    {
        $calculators = CarbonCalculator::orderBy('waste_type')->get();

        $totalJenis = $calculators->count();

        $totalCo2 = $calculators->sum('co2_factor');

        $totalPoint = $calculators->sum('point_per_kg');

        $lastUpdate = CarbonCalculator::latest('updated_at')->first();

        return view(
            'admin.carbon.index',
            compact(
                'calculators',
                'totalJenis',
                'totalCo2',
                'totalPoint',
                'lastUpdate'
            )
        );
    }

    public function storeCarbonCalculator(Request $request)
    {
        $request->validate([
            'waste_type' => 'required',
            'co2_factor' => 'required|numeric',
            'point_per_kg' => 'required|numeric',
            'tree_factor' => 'required|numeric',
        ]);

        CarbonCalculator::create([
            'waste_type' => $request->waste_type,
            'co2_factor' => $request->co2_factor,
            'point_per_kg' => $request->point_per_kg,
            'tree_factor' => $request->tree_factor,
        ]);

        return back()->with('success','Data berhasil ditambahkan');
    }

    public function destroyCarbonCalculator(CarbonCalculator $calculator)
    {
        $calculator->delete();

        return back()->with('success','Data berhasil dihapus');
    }

    private function log(string $activity): void
    {
        AdminActivity::create([
            'admin_id' => auth()->id(),
            'activity' => $activity,
        ]);
    }
}
