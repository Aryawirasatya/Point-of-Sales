<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    // Menampilkan form pendaftaran kasir
    public function showRegisterForm()
    {
        return view('admin.register-cashier');
    }

    // Proses registrasi kasir oleh admin
    public function registerCashier(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cashier', // Set role menjadi kasir
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Akun Kasir berhasil dibuat');
    }

    public function showResetPasswordForm(User $user)
    {
        return view('admin.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'Password kasir berhasil direset');
    }

}
