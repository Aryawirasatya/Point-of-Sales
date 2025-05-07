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
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <h5 class="card-title mb-2">Total Produk</h5>
                    <h2 class="card-text fw-bold">152</h2>
                    <p class="text-white-50 mb-0">Produk aktif di toko</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success shadow rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <h5 class="card-title mb-2">Transaksi Hari Ini</h5>
                    <h2 class="card-text fw-bold">37</h2>
                    <p class="text-white-50 mb-0">Transaksi berhasil</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning shadow rounded-4 h-100">
                <div class="card-body d-flex flex-column justify-content-center text-center">
                    <h5 class="card-title mb-2">Total Pendapatan</h5>
                    <h2 class="card-text fw-bold">Rp 18.250.000</h2>
                    <p class="text-white-50 mb-0">Pendapatan hari ini</p>
                </div>
            </div>
        </div>

        <!-- Produk Terlaris & Stok Rendah -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title">Produk Terlaris</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Beras Premium</span> <span class="badge bg-primary rounded-pill">150</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Minyak Goreng</span> <span class="badge bg-primary rounded-pill">130</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Gula Pasir</span> <span class="badge bg-primary rounded-pill">110</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <h5 class="card-title">Produk Stok Rendah</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Sabun Mandi</span> <span class="badge bg-danger rounded-pill">4</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Kopi Bubuk</span> <span class="badge bg-danger rounded-pill">2</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Detergen</span> <span class="badge bg-danger rounded-pill">1</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Grafik Pendapatan -->
        <div class="col-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="card-title">Grafik Pendapatan Bulanan</h5>
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Button -->

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

    .list-group-item {
        font-size: 1rem;
        font-weight: 500;
    }

    .chart-container {
        position: relative;
        height: 300px;
    }
</style>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: [12000000, 15000000, 17000000, 14000000, 16000000, 18000000, 20000000],
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection
