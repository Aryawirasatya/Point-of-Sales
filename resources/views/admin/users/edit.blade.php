@extends('layout.app')

@section('content')
<div class="container py-4">
  <h2>Edit User: {{ $user->name }}</h2>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="mb-3">
      <label class="form-label">Nama</label>
      <input
        type="text"
        name="name"
        class="form-control"
        value="{{ old('name',$user->name) }}"
        required
      >
    </div>

    <div class="mb-3">
      <label class="form-label">Role</label>
      <select name="role" class="form-select" required>
        <option value="admin"   {{ old('role',$user->role)==='admin'   ? 'selected':'' }}>Admin</option>
        <option value="cashier" {{ old('role',$user->role)==='cashier'? 'selected':'' }}>Cashier</option>
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

    <button class="btn btn-success">
      <i class="fas fa-save"></i> Simpan Perubahan
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>

@endsection
