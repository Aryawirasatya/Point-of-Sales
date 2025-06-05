<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class CashierController extends Controller
{
    public function dashboard()
    {
        $today = now()->startOfDay();
        $userId = Auth::id();

        // Hitung transaksi hari ini milik user yang login
        $todayTransactions = Sale::where('user_id', $userId)
            ->where('created_at', '>=', $today)
            ->count();

        // Hitung total pendapatan hari ini milik user yang login
        $todayRevenue = Sale::where('user_id', $userId)
            ->where('created_at', '>=', $today)
            ->sum('total_amount');

        // Ambil 5 transaksi terbaru milik user yang login
        $recentSales = Sale::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();
        
        $products = Products::orderBy('name')->get();


        return view('cashier.dashboard', compact(
            'todayTransactions',
            'todayRevenue',
            'recentSales',
            'products'
        ));
    }
}
