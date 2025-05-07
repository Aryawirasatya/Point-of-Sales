@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background: linear-gradient(135deg, #f9f9f9, #e0f7fa);">
    <div class="col-md-6">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-center bg-primary rounded-top-4" style="background-image: url('/images/bg-groceries.jpg'); background-size: cover; background-position: center;">
                <h3 class="text-white fw-bold m-0" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.6);">
                    Selamat Datang di Toko Kelontong
                </h3>
            </div>
            <div class="card-body p-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input id="email" type="email" class="form-control shadow-sm @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan email Anda">
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-4">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control shadow-sm @error('password') is-invalid @enderror" name="password" required placeholder="Masukkan password">
                        <button type="button" class="btn btn-transparent" onclick="togglePassword()" tabindex="-1">
                            <i class="fas fa-eye text-secondary" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <a class="text-decoration-none small text-primary" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg shadow-sm">
                            Masuk
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <span class="text-muted ">Belum punya akun?</span>
                        <a href=" " class="text-primary fw-semibold text-decoration-none">Daftar di sini</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f5f5f5;
    }
    .card {
        overflow: hidden;
    }
    .card-header {
        height: 150px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    const isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';
    toggleIcon.classList.toggle('fa-eye');
    toggleIcon.classList.toggle('fa-eye-slash');
}
</script>

@endsection
