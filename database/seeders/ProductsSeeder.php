<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Kategori: Makanan (ID 1)
            ['name' => 'Nasi Goreng Spesial', 'barcode' => Str::random(10), 'description' => 'Nasi goreng lezat dengan bumbu spesial.', 'price' => 25000, 'stock_quantity' => 50, 'image' => 'nasigoreng.jpg', 'category_id' => 1],
            ['name' => 'Ayam Geprek Level 5', 'barcode' => Str::random(10), 'description' => 'Ayam geprek dengan sambal pedas.', 'price' => 30000, 'stock_quantity' => 40, 'image' => 'ayamgeprek.jpg', 'category_id' => 1],

            // Kategori: Minuman (ID 2)
            ['name' => 'Teh Botol Jasmine', 'barcode' => Str::random(10), 'description' => 'Minuman teh botol segar.', 'price' => 7000, 'stock_quantity' => 100, 'image' => 'tehbotol.jpg', 'category_id' => 2],
            ['name' => 'Kopi Hitam Arabica', 'barcode' => Str::random(10), 'description' => 'Kopi hitam pekat dari biji Arabica pilihan.', 'price' => 15000, 'stock_quantity' => 60, 'image' => 'kopihitam.jpg', 'category_id' => 2],

            // Kategori: Kebutuhan Rumah Tangga (ID 3)
            ['name' => 'Sabun Cuci Piring Lemon Fresh', 'barcode' => Str::random(10), 'description' => 'Sabun dengan aroma lemon yang menyegarkan.', 'price' => 15000, 'stock_quantity' => 80, 'image' => 'sabun.jpg', 'category_id' => 3],
            ['name' => 'Pembersih Lantai Wangi Lavender', 'barcode' => Str::random(10), 'description' => 'Pembersih lantai dengan wangi lavender.', 'price' => 25000, 'stock_quantity' => 50, 'image' => 'pembersihlantai.jpg', 'category_id' => 3],

            // Kategori: Alat Tulis (ID 4)
            ['name' => 'Pulpen Gel SmoothWrite', 'barcode' => Str::random(10), 'description' => 'Pulpen gel dengan tinta yang mengalir halus.', 'price' => 5000, 'stock_quantity' => 150, 'image' => 'pulpen.jpg', 'category_id' => 4],
            ['name' => 'Buku Tulis 100 Halaman', 'barcode' => Str::random(10), 'description' => 'Buku tulis tebal untuk sekolah.', 'price' => 12000, 'stock_quantity' => 90, 'image' => 'bukutulis.jpg', 'category_id' => 4],

            // Kategori: Handphone (ID 8)
            ['name' => 'Smartphone XYZ Pro', 'barcode' => Str::random(10), 'description' => 'Smartphone terbaru dengan layar AMOLED.', 'price' => 8000000, 'stock_quantity' => 25, 'image' => 'smartphone.jpg', 'category_id' => 8],
            ['name' => 'Earbuds Noise-Cancel X', 'barcode' => Str::random(10), 'description' => 'Earbuds dengan fitur noise-cancelling.', 'price' => 900000, 'stock_quantity' => 35, 'image' => 'earbuds.jpg', 'category_id' => 8],

            // Kategori: Elektronik (ID 17)
            ['name' => 'Lampu LED Hemat Energi', 'barcode' => Str::random(10), 'description' => 'Lampu LED dengan pencahayaan terang dan hemat energi.', 'price' => 50000, 'stock_quantity' => 70, 'image' => 'lampuled.jpg', 'category_id' => 17],
            ['name' => 'Charger FastCharge 45W', 'barcode' => Str::random(10), 'description' => 'Charger dengan teknologi fast charging.', 'price' => 350000, 'stock_quantity' => 100, 'image' => 'charger.jpg', 'category_id' => 17],

            // Kategori: Kesehatan (ID 23)
            ['name' => 'Vitamin C 1000mg', 'barcode' => Str::random(10), 'description' => 'Vitamin C dosis tinggi untuk daya tahan tubuh.', 'price' => 50000, 'stock_quantity' => 50, 'image' => 'vitaminc.jpg', 'category_id' => 23],
            ['name' => 'Masker Medis 3 Lapis', 'barcode' => Str::random(10), 'description' => 'Masker medis berkualitas tinggi.', 'price' => 20000, 'stock_quantity' => 200, 'image' => 'masker.jpg', 'category_id' => 23]
        ];

        foreach ($products as &$product) {
            $product['created_at'] = now();
            $product['updated_at'] = now();
        }

        DB::table('products')->insert($products);
    }
}