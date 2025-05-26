<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Makanan',
                'description' => 'Produk makanan ringan dan berat',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Minuman',
                'description' => 'Minuman botol, sachet, dan galon',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kebutuhan Rumah Tangga',
                'description' => 'Sabun, detergen, pembersih lantai, dan lainnya',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alat Tulis',
                'description' => 'Pulpen, pensil, buku, dan keperluan sekolah',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elektronik',
                'description' => 'Lampu, kabel, charger, dan perangkat listrik kecil',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kecantikan',
                'description' => 'Kosmetik, sabun wajah, dan skincare',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Perawatan Pribadi',
                'description' => 'Sampo, sabun mandi, deodorant, pasta gigi',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bumbu Dapur',
                'description' => 'Garam, gula, penyedap rasa, kecap, dan lainnya',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Makanan Instan',
                'description' => 'Mi instan, sarden, kornet, dan makanan cepat saji',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cemilan Anak',
                'description' => 'Permen, cokelat, biskuit, dan snack anak-anak',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kesehatan',
                'description' => 'Masker, vitamin, tisu basah, alkohol',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pakaian Dalam',
                'description' => 'Kaos dalam, celana dalam, dan pakaian rumah',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
