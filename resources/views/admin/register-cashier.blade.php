@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Kasir Baru</h2>

    <form method="POST" action="{{ route('admin.register.cashier') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Kasir</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Daftar Kasir</button>
    </form>
</div>
@endsection
