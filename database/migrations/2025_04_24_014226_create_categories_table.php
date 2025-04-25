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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nama kategori produk
            $table->text('description')->nullable();  // Deskripsi kategori (opsional)
            $table->enum('status', ['active', 'inactive'])->default('active');  // Status kategori
            $table->timestamps();  // Created at, updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
