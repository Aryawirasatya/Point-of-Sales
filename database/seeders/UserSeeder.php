<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [];

        // Tambahkan 5 admin
        for ($i = 1; $i <= 5; $i++) {
            $users[] = [
                'name' => "Admin Toko $i",
                'email' => "admin$i@toko.com",
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Tambahkan 45 kasir
        for ($i = 1; $i <= 45; $i++) {
            $users[] = [
                'name' => "Kasir Toko $i",
                'email' => "kasir$i@toko.com",
                'password' => Hash::make('password123'),
                'role' => 'cashier',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
