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
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel sales
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel products
            $table->integer('quantity');  // Jumlah produk yang dibeli
            $table->decimal('unit_price', 10, 2);  // Harga per unit
            $table->decimal('total_price', 10, 2);  // Total harga produk
            $table->timestamps();  // Created at, updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
