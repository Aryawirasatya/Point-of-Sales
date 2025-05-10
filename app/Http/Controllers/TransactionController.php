<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /** Tampilkan form transaksi */
    public function create()
    {
        $products = Products::where('stock_quantity', '>', 0)->get();
        return view('cashier.transactions.create', compact('products'));
    }

    /** Proses simpan transaksi */
    public function store(Request $request)
{
    $data = $request->validate([
        'items' => 'required|json',
        'paid'  => 'required|numeric|min:0',
    ]);

    $items = json_decode($data['items'], true);

    $total = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
    $paid = $data['paid'];
    $change = $paid - $total;

    if ($data['paid'] < $total) {
        return back()->withErrors('Jumlah bayar tidak boleh kurang dari total.');
    }

    // Validasi stok terlebih dahulu
    foreach ($items as $i) {
        $product = Products::find($i['id']);
        if (!$product || $product->stock_quantity < $i['qty']) {
            $productName = $product ? $product->name : 'Produk tidak ditemukan';
            return back()->withErrors("Stok untuk {$productName} tidak mencukupi.");
        }
    }
    

    // Jalankan transaksi hanya jika validasi lolos
    try {
        $sale = DB::transaction(function () use ($items, $total,$change,$paid) {
            $sale = Sale::create([
                'invoice_number' => 'INV'.now()->format('YmdHis'),
                'total_amount'   => $total,
                'paid_amount'    => $paid,    // simpan bayar
                'change_amount'  => $change, 
                'payment_status' => 'paid',
                'user_id'        => Auth::id(),
            ]);

            foreach ($items as $i) {
                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $i['id'],
                    'quantity'    => $i['qty'],
                    'unit_price'  => $i['price'],
                    'total_price' => $i['price'] * $i['qty'],
                ]);

                $product = Products::find($i['id']);
                if ($product) {
                    $product->decrement('stock_quantity', $i['qty']);
                }
            }

            return $sale;
        });

        return redirect()
    ->route('cashier.transactions.show', $sale->id)
    ->with([
      'success'   => true,
      'invoice_id'=> $sale->id,
      'paid'      => $data['paid'],
      'change'    => $data['paid'] - $total,
    ]);

    

    } catch (\Exception $e) {
        return back()->withErrors('Gagal menyimpan transaksi: '.$e->getMessage());
    }
}


    /** Tampilkan invoice */
    public function invoice(Sale $sale)
{
    $sale->load(['user', 'items.product']);
    return view('cashier.transactions.show', compact('sale'));
}

    public function index(Request $request)
    {
        // Ambil query pencarian jika ada
        $search = $request->query('q');
    
        // Mulai query dasar: hanya transaksi milik kasir ini
        $query = Sale::with('user');
    
        // Jika ada pencarian invoice, filter berdasarkan itu
        if ($search) {
            $query->where('invoice_number', 'like', '%' . $search . '%');
        }
    
        // Ambil data dengan urutan terbaru dan paginasi
        $sales = $query->orderBy('created_at', 'desc')
                       ->paginate(20)
                       ->appends(['q' => $search]); // supaya query tetap dibawa saat pindah halaman
    
        return view('cashier.transactions.index', compact('sales'));
    }
    

}
