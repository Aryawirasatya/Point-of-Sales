@extends('layout.app')
@section('content')
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
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- tabel daftar user --}}
  <table class="table table-bordered">
    <thead class="table-light">
      <tr><th class="">NO</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr>
    </thead>
    <tbody>
      @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ ucfirst($u->role) }}</td>
        <td class="d-flex gap-1">
          {{-- tombol edit buka modal --}}
          <button class="btn btn-sm btn-warning"
                  data-bs-toggle="modal"
                  data-bs-target="#modal-edit-user"
                  data-id="{{ $u->id }}"
                  data-name="{{ $u->name }}"
                  data-role="{{ $u->role }}">
            <i class="fas fa-edit"></i>
          </button>
          {{-- tombol hapus --}}
          <form action="{{ route('admin.users.destroy',$u) }}"
                method="POST"
                onsubmit="return confirm('Yakin ingin menghapus?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
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
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
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
<div class="modal fade" id="modal-edit-user" tabindex="-1">
  <div class="modal-dialog">
    <form id="form-edit-user" method="POST">
      @csrf
       @method('PATCH')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input name="name" id="edit-name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" id="edit-role" class="form-select" required>
              <option value="admin">Admin</option>
              <option value="cashier">Cashier</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Password Baru (opsional)</label>
            <input type="password" name="password" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Script untuk isi form edit dan set action --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function(){
    let editModal = document.getElementById('modal-edit-user');
    editModal.addEventListener('show.bs.modal', function(e){
      let btn = e.relatedTarget;
      let id  = btn.getAttribute('data-id');
      let name= btn.getAttribute('data-name');
      let role= btn.getAttribute('data-role');

      document.getElementById('edit-name').value = name;
      document.getElementById('edit-role').value = role;
      document.getElementById('form-edit-user')
              .action = `/admin/users/${id}`;
    });
  });
</script>
@endpush

@endsection
