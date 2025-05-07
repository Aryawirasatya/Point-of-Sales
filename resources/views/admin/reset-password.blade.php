@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Reset Password Kasir</h2>

    <form method="POST" action="{{ route('admin.reset.password', $user->id) }}">
        @csrf

        <div class="form-group">
            <label for="password">Password Baru</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>
@endsection
    