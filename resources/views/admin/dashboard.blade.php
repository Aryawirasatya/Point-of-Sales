@extends('layout.app')

@section('content')
<style>
  /* Tambahkan style khusus agar tampil lebih menarik */
  .card-custom {
    border-radius: 15px;
    transition: transform 0.2s, box-shadow 0.2s;
  }
  .card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  }
  /* Gradient background custom */
  .bg-gradient {
    background: linear-gradient(135deg, #667eea, #764ba2);
  }
</style>

<div class="container-fluid mt-4">
  <div class="row g-4">

    {{-- Waktu Saat Ini --}}
    <div class="container mt-4">
      <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center text-white bg-gradient card-custom shadow-lg">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="fas fa-calendar-alt"></i> Waktu Saat Ini</h5>
                    <p class="card-text fs-4">
                        📅 {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y - H:i') }}
                    </p>
                </div>
            </div>
        </div>
      </div>
    </div>

    {{-- Summary Cards --}}
    @php
      $summaryCards = [
        ['title' => 'Total Produk', 'value' => $totalProduk, 'desc' => 'Produk aktif di toko', 'color' => 'primary', 'icon' => 'box-open'],
        ['title' => 'Transaksi Hari Ini', 'value' => $transaksiHariIni, 'desc' => 'Transaksi berhasil', 'color' => 'success', 'icon' => 'shopping-cart'],
        ['title' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'), 'desc' => 'Pendapatan hari ini', 'color' => 'info', 'icon' => 'dollar-sign'],
      ];
    @endphp

    @foreach ($summaryCards as $card)
      <div class="col-md-4">
        <div class="card shadow rounded-4 border-0 bg-gradient text-white card-custom">
          <div class="card-body text-center py-4">
            <h5 class="card-title mb-2"><i class="fas fa-{{ $card['icon'] }} fa-lg me-2"></i>{{ $card['title'] }}</h5>
            <h2 class="fw-bold mb-2">{{ $card['value'] }}</h2>
            <p class="mb-0">{{ $card['desc'] }}</p>
          </div>
        </div>
      </div>
    @endforeach

    {{-- Produk Terlaris --}}
    <div class="col-md-6">
      <div class="card shadow rounded-4 border-0 h-100">
        <div class="card-body">
          <h5 class="card-title mb-3"><i class="fas fa-star"></i> Produk Terlaris</h5>
          <ul class="list-group list-group-flush">
            @forelse ($produkTerlaris as $produk)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $produk->name }}
                <span class="badge rounded-pill bg-primary">{{ $produk->total_terjual }}</span>
              </li>
            @empty
              <li class="list-group-item text-muted text-center">Belum ada data.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    {{-- Produk Stok Rendah --}}
    <div class="col-md-6">
      <div class="card shadow rounded-4 border-0 h-100">
        <div class="card-body">
          <h5 class="card-title mb-3"><i class="fas fa-exclamation-triangle"></i> Produk Stok Rendah</h5>
          <ul class="list-group list-group-flush">
            @forelse ($produkStokRendah as $produk)
              <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $produk->name }}
                <span class="badge rounded-pill bg-danger">{{ $produk->stock_quantity }}</span>
              </li>
            @empty
              <li class="list-group-item text-muted text-center">Semua stok aman.</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>

    {{-- Grafik Pendapatan --}}
    <div class="col-12 mt-4">
      <div class="card shadow rounded-4 border-0">
        <div class="card-body">
          <h5 class="card-title mb-3"><i class="fas fa-chart-line"></i> Grafik Pendapatan Bulanan</h5>
          <div class="chart-container" style="height: 320px;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('revenueChart').getContext('2d');
  const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($bulan),
      datasets: [{
        label: 'Pendapatan (Rp)',
        data: @json($pendapatanBulanan),
        borderColor: 'rgb(75, 118, 192)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        pointBackgroundColor: 'white',
        borderWidth: 2,
        tension: 0.4,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: true, labels: { color: '#666' } },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: { color: '#666' }
        },
        x: {
          ticks: { color: '#666' }
        }
      }
    }
  });
</script>
@endsection