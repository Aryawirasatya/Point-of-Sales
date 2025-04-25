<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nama produk
            $table->string('barcode')->nullable()->unique();  // Barcode produk (opsional)
            $table->text('description')->nullable();  // Deskripsi produk
            $table->decimal('price', 10, 2);  // Harga produk
            $table->integer('stock_quantity');  // Stok produk
            $table->string('image')->nullable();  // Gambar produk
            $table->foreignId('category_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel categories
            $table->timestamps();  // Created at, updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
