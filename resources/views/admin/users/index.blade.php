@extends('layout.app')
@section('content')
 <style>
  /* === Judul Halaman === */
  h2 {
    font-weight: 400;
    color: white;
  }

  /* === Tombol Register Kasir === */
  .btn-register-kasir {
    font-size: 16px;
    font-weight: 500;
    border-radius: 6px;
  }

  /* === Alert Sukses === */
  .alert-success {
    font-size: 14px;
    padding: 10px 15px;
    border-radius: 4px;
  }

  /* === Tabel Daftar User === */
  .table-user {
    border: 1px solid #ddd;
    font-size: 14px;
    border-radius:10px;
    overflow:hidden;
    /* background-color: #f8f9fa; */
  }
  
  .table-user thead {
  }
  
  
  .table-user td,
  .table-user th {
    /* border-radius:5px ; */
    border:none;
    padding: 10px;
    vertical-align: middle;
    color:white;
  background-color: #31363F;

  }
.table-wrapper {
  border-radius: 10px;
  overflow: hidden;
}

  .table-user tbody tr:nth-child(odd){
    background-color: #3a3d42;
  }

  .table-user tbody tr:nth-child(even){
    background-color: #46494f;
  }

  .table-user tbody tr:hover {
  background-color: #5c5f66;
  transition: background-color 0.3s ease;
}
  /* === Tombol Edit & Hapus === */
  .btn-action {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    font-size: 14px;
    border-radius: 4px;
  }

  .btn-action i {
    margin-right: 2px;
  }

  /* === Modal Form === */
  .modal .modal-title {
    font-size: 18px;
    font-weight: 600;
    letter-spacing: 0.5px;
  }

  .modal .form-label {
    font-weight: 500;
    margin-bottom: 6px;
  }

  .modal .form-control,
  .modal .form-select {
    font-size: 14px;
    border-radius: 4px;
  }

  /* === Modal Footer Buttons === */
  .modal-footer .btn {
    padding: 8px 16px;
    font-size: 14px;
    border-radius: 4px;
  }

  /* === Tombol Tampilkan Password === */
  .btn-transparent {
    background: transparent;
    border: none;
    padding-left: 0;
    margin-top: 4px;
  }

  /* === Responsive Container === */
  .container.py-4 {
    max-width: 800px;
  }
</style>

<div class="container py-4">
  
  <h2>Manajemen Pengguna</h2>

  {{-- tombol buka modal register --}}
  <button class="btn btn-primary mb-3"
          data-bs-toggle="modal"
          data-bs-target="#modal-create-user">
    <i class="fas fa-user-plus"></i> Register Kasir
  </button>

  {{-- pesan sukses --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Produk berhasil ditambahkan.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>


  @endif
  <div class="table-responsive">
    <table class="table table-hover table-user">
      <thead>
        <tr><th class="">NO</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ ucfirst($u->role) }}</td>
            {{-- tombol edit buka modal --}}
          <td class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-2">
              <i class="fas fa-edit"></i>
            </a>
                      {{-- tombol hapus --}}
            <form action="{{ route('admin.users.destroy',$u) }}"
                  method="POST"
                  class="form-delete-user  ">
               @csrf
               @method('DELETE')
              <button class="btn btn-sm btn-outline-danger rounded-pill px-3"><i class="fas fa-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
     <div class=" border-0 mt-2 gap-5">
        <nav class="d-flex justify-content-end mb-0 gap" aria-label="Pagination">
            {{ $users->links('pagination::bootstrap-5') }}
        </nav>
    </div>
  </div>
 
</div>

{{-- Modal: Register Kasir --}}
<div class="modal fade" id="modal-create-user" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('admin.users.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Register User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" id="reg-password" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" id="reg-password-confirmation" required>
            <button type="button" class="btn btn-transparent" onclick="togglePassword('reg-password', 'reg-password-confirmation', 'reg-toggleIcon')" tabindex="-1">
            <i class="fas fa-eye text-secondary" id="reg-toggleIcon"></i>
            </button>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select">
              <option value="cashier">Cashier</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal: Edit User --}}
 
 

{{-- Script untuk isi form edit dan set action --}}
@push('scripts')
<script>
  function togglePassword(passwordId, confirmId, iconId) {
    const passwordInput = document.getElementById(passwordId);
    const confirmInput = document.getElementById(confirmId);
    const toggleIcon = document.getElementById(iconId);

    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    confirmInput.type = isPassword ? 'text' : 'password';

    toggleIcon.classList.toggle('fa-eye');
    toggleIcon.classList.toggle('fa-eye-slash');
  }

  document.addEventListener('DOMContentLoaded', function() {
  // pilih semua form hapus
  document.querySelectorAll('.form-delete-user').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault(); // cegah submit langsung

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // kalau user tekan Ya, submit form
          form.submit();
        }
      });
    });
  });
});

</script>
@endpush

@endsection
