<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua produk beserta kategori
        $products = Products::with('category')->get();

        // Tampilkan halaman dengan data produk
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil kategori yang aktif
        $categories = Category::where('status', 'active')->get();

        // Tampilkan form untuk menambahkan produk baru
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name'           => 'required|string|max:255',
            'barcode'        => 'nullable',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate barcode unik jika tidak diinput
        do {
            $barcode = 'PRD-' . strtoupper(Str::random(8));
        } while (Products::where('barcode', $barcode)->exists());

        // Menyimpan path gambar (jika ada)
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan file ke disk 'public' di folder 'products'
            // → file disimpan di storage/app/public/products/...
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Simpan data produk baru
        Products::create([
            'name'           => $request->name,
            'barcode'        => $barcode,
            'category_id'    => $request->category_id,
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image'          => $imagePath, // bisa null jika tidak ada upload
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // (Opsional) Jika diperlukan detail produk, implementasikan di sini.
        // Saat ini belum digunakan.
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Ambil data produk berdasarkan ID
        $product = Products::findOrFail($id);

        // Ambil kategori yang aktif
        $categories = Category::where('status', 'active')->get();

        // Tampilkan form edit produk
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name'           => 'required|string|max:255',
            'barcode'        => 'nullable|string|max:255|unique:products,barcode,' . $id,
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cari produk yang akan diupdate
        $product = Products::findOrFail($id);

        // Jika ada file gambar baru yang diupload
        if ($request->hasFile('image')) {
            // Hapus gambar lama di disk 'public' jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Simpan gambar baru ke disk 'public' di folder 'products'
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Update data produk (selain image, karena sudah di-handle di atas)
        $product->update([
            'name'           => $request->name,
            'barcode'        => $request->barcode ?? $product->barcode,
            'category_id'    => $request->category_id,
            'description'    => $request->description,
            'price'          => $request->price,
            'stock_quantity' => $request->stock_quantity,
            // 'image' tidak perlu ditulis lagi karena sudah diassign di atas jika ada perubahan
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan produk berdasarkan ID
        $product = Products::findOrFail($id);

        // Hapus gambar dari disk 'public' jika ada
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        // Hapus data produk dari database
        $product->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
