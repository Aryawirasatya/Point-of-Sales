@extends('layouts.app')

@section('content')
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
/>

<div class="login-wrapper d-flex justify-content-center align-items-center vh-100">
  <div class="login-card p-5 rounded-4 shadow-lg">
    <h1 class="text-center mb-4 text-pink fw-bold">WARCOK</h1>
 

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-4">
        <label class="form-label text-white">Username</label>
        <input
          type="text"
          name="email"
          class="form-control bg-dark border-0 text-white"
          placeholder="Enter your username"
          required
        >
      </div>

      <div class="mb-4">
        <label class="form-label text-white">Password</label>
        <div class="input-group">
          <input
            type="password"
            name="password"
            id="password"
            class="form-control bg-dark border-0 text-white"
            placeholder="Enter your password"
            required
          >
          <button
            type="button"
            class="btn btn-dark border-0"
            onclick="togglePassword()"
          >
            <i class="fas fa-eye text-gray" id="toggleIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input
            class="form-check-input"
            type="checkbox"
            id="remember"
            name="remember"
          >
          <label class="form-check-label text-white" for="remember">
            Remember me
          </label>
        </div>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="text-pink small">
            Forgot Password?
          </a>
        @endif
      </div>

      <div class="text-center">
        <button type="submit" class="btn btn-pink px-5 py-2 rounded-pill">
          Login
        </button>
      </div>
    </form>
  </div>
</div>

<style>
  :root {
    --bg-dark: #222831;
    --card-dark: #31363F;
    --text-white: #ffffff;
    --text-gray: #b0b0b0;
    --pink: #76ABAE;
  }

  h1 {
    font-family: 'Swell';
    letter-spacing:2px;
 
  
  }

  body, html {
    margin: 0;
    padding: 0;
    background-color: var(--bg-dark);
    height: 100%;
  }

  .login-wrapper {
    background-color: var(--bg-dark);
  }

  .login-card {
    background-color: var(--card-dark);
    max-width: 400px;
    width: 100%;
  }

  .form-control {
    height: 45px;
    border-radius: 8px;
  }

  .btn-pink {
    background-color: var(--pink);
    color: var(--bg-dark);
  }
  .btn-pink:hover {
    background-color: white;
  }

  .text-pink {
    color: var(--pink) !important;
  }
  .text-gray {
    color: var(--text-gray) !important;
  }

  .form-label {
    font-size: 0.9rem;
  }
</style>

<script>
  function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    const shown = pwd.type === 'text';
    pwd.type = shown ? 'password' : 'text';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
  }
</script>
@endsection
