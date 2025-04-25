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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Nama pengguna
            $table->string('email')->unique();  // Email pengguna
            $table->string('password');  // Password pengguna
            $table->enum('role', ['admin', 'cashier']);  // Role pengguna (admin/cashier)
            $table->rememberToken();  // Untuk fitur 'remember me'
            $table->timestamps();  // Created at, updated at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
