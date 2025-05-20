<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // INDEX: tampilkan daftar user
    public function index()
    {
        $users = User::whereIn('role',['admin','cashier'])->get();
        return view('admin.users.index', compact('users'));
    }

    // STORE: simpan user baru (register cashier)
    public function store(Request $r)
    {
        $data = $r->validate([
            'name'                  => 'required|string',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|confirmed|min:6',
            'role'                  => 'required|in:cashier,admin',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        return redirect()->route('admin.users.index')
                         ->with('success','User berhasil ditambahkan.');
    }

    // UPDATE: ubah nama, role, dan password (opsional)
    public function update(Request $r, User $user)
{
    $data = $r->validate([
        'name'                  => 'required|string|max:255',
        'role'                  => 'required|in:admin,cashier',
        'password'              => 'nullable|confirmed|min:6',
    ]);

    // Siapkan array perubahan
    $changes = [
      'name' => $data['name'],
      'role' => $data['role'],
    ];

    if (! empty($data['password'])) {
        $changes['password'] = Hash::make($data['password']);
    }

    // Pakai mass‑update
    $user->update($changes);

    return redirect()
           ->route('admin.users.index')
           ->with('success','User berhasil diperbarui.');
    }


    // DESTROY: hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success','User berhasil dihapus.');
    }
    // EDIT: tampilkan form edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

}
