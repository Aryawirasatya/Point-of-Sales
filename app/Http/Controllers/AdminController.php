<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Products;
use Carbon\Carbon;
class AdminController extends Controller
{
public function index()
    {
        // Total produk
        $totalProduk = Products::count();

        // Transaksi hari ini
        $transaksiHariIni = Sale::whereDate('created_at', Carbon::today())->count();

        // Total pendapatan hari ini
        $pendapatanHariIni = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');

        // Produk stok rendah (<=5)
        $produkStokRendah = Products::where('stock_quantity', '<=', 5)->get();

        // Produk terlaris
        $produkTerlaris = DB::table('sale_items')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_terjual'))
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->groupBy('products.name')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Pendapatan per bulan (7 bulan terakhir)
        $bulan = [];
        $pendapatanBulanan = [];

        for ($i = 6; $i >= 0; $i--) {
            $bulanName = Carbon::now()->subMonths($i)->format('M');
            $bulan[] = $bulanName;

            $total = Sale::whereMonth('created_at', Carbon::now()->subMonths($i)->month)
                        ->whereYear('created_at', Carbon::now()->subMonths($i)->year)
                        ->sum('total_amount');

            $pendapatanBulanan[] = $total;
        }

        return view('admin.dashboard', compact(
            'totalProduk',
            'transaksiHariIni',
            'pendapatanHariIni',
            'produkStokRendah',
            'produkTerlaris',
            'bulan',
            'pendapatanBulanan'
        ));
    }

    // Menampilkan form pendaftaran kasir
    public function showRegisterForm()
    {
        return view('admin.register-cashier');
    }

    // Proses registrasi kasir oleh admin
    public function registerCashier(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cashier', // Set role menjadi kasir
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Kasir berhasil dibuat');
    }

    public function showResetPasswordForm(User $user)
    {
        return view('admin.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Password kasir berhasil direset');
    }

}
