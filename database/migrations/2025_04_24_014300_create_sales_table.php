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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();  // Nomor invoice
            $table->decimal('total_amount', 10, 2);  // Total harga transaksi
            $table->decimal('paid_amount', 10, 2);  // Total harga transaksi
            $table->decimal('change_amount', 10, 2);  // Total harga transaksi
            $table->enum('payment_status', ['paid', 'unpaid']);  // Status pembayaran
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel users
            $table->timestamps();  // Created at, updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
