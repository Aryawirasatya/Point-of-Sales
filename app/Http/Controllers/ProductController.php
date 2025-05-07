<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan baris ini untuk mengimpor Storage
use Illuminate\Support\Str; // pastikan ada ini di atas file

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
            'name' => 'required|string|max:255',
            'barcode' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        do {
            $barcode = 'PRD-' . strtoupper(Str::random(8));
        } while (Products::where('barcode', $barcode)->exists());

        // Menyimpan gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan gambar ke storage
            $imagePath = $request->file('image')->store('products', 'public');

        }

        // Menyimpan data produk
        Products::create([
            'name' => $request->name,
            'barcode' => $barcode,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'image' => $imagePath, // Simpan path gambar
        ]);

        // Redirect ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Menampilkan detail produk berdasarkan ID (jika diperlukan)
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

        // Tampilkan form untuk mengedit produk
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input dari pengguna
        $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $id, // Pastikan tidak duplikat untuk produk yang sama
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi gambar
        ]);

        // Menemukan produk yang akan diupdate
        $product = Products::findOrFail($id);

        // Menyimpan gambar baru jika ada
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::delete($product->image);
            }

            // Simpan gambar baru ke storage
            $imagePath = $request->file('image')->store('public/products');
            $product->image = $imagePath; // Update path gambar
        }

        // Update data produk
        $product->update([
            'name' => $request->name,
            'barcode' => $request->barcode,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
        ]);

        // Redirect ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Menemukan produk berdasarkan ID
        $product = Products::findOrFail($id);

        // Hapus gambar produk jika ada
        if ($product->image) {
            Storage::delete($product->image); // Hapus gambar dari storage
        }

        // Hapus produk dari database
        $product->delete();

        // Redirect ke halaman daftar produk dengan pesan sukses
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
