@extends('layout.app')

@section('content')
<div class="container-fluid mt-5">
    <div class="row g-4">

        <div class="col-12 text-left mt-4">
            <button class="btn btn-danger rounded-4 px-5" data-bs-toggle="modal" data-bs-target="#logoutModal">
                Logout
            </button>
        </div>

        <!-- Cards Info -->
        <div class="col-md-6">
            <div class="card text-white bg-success shadow rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <h5 class="card-title mb-2">Transaksi Hari Ini</h5>
                    <h2 class="card-text fw-bold">37</h2>
                    <p class="text-white-50 mb-0">Transaksi berhasil</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-warning shadow rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <h5 class="card-title mb-2">Total Pendapatan</h5>
                    <h2 class="card-text fw-bold">Rp 18.250.000</h2>
                    <p class="text-white-50 mb-0">Pendapatan hari ini</p>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Mulai Transaksi Baru</h5>
                     
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100 text-center">
                <div class="card-body d-flex flex-column justify-content-center">
                    <h5 class="card-title mb-3">Riwayat Transaksi</h5>
                     
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout dari sistem?
            </div>
            <div class="modal-footer">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tambahan CSS -->
<style>
    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
    }
    .card-body a {
        margin-top: 15px;
    }
</style>
@endsection
