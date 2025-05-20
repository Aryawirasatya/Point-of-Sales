@extends('layout.app')

@section('content')
<div class="container-fluid mt-4">
  <div class="row g-4">

    {{-- Ringkasan --}}
    @php
      $summaryCards = [
        ['title' => 'Total Produk', 'value' => $totalProduk, 'desc' => 'Produk aktif di toko', 'color' => 'primary'],
        ['title' => 'Transaksi Hari Ini', 'value' => $transaksiHariIni, 'desc' => 'Transaksi berhasil', 'color' => 'success'],
        ['title' => 'Total Pendapatan', 'value' => 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'), 'desc' => 'Pendapatan hari ini', 'color' => 'info'],
      ];
    @endphp

    @foreach ($summaryCards as $card)
      <div class="col-md-4">
        <div class="card shadow rounded-4 border-0 text-white bg-gradient bg-{{ $card['color'] }} h-100">
          <div class="card-body text-center py-4">
            <h5 class="card-title mb-2">{{ $card['title'] }}</h5>
            <h2 class="fw-bold mb-2">{{ $card['value'] }}</h2>
            <p class="text-white-50 mb-0">{{ $card['desc'] }}</p>
          </div>
        </div>
      </div>
    @endforeach

    {{-- Produk Terlaris --}}
    <div class="col-md-6">
      <div class="card shadow rounded-4 border-0 h-100">
        <div class="card-body">
          <h5 class="card-title mb-3">Produk Terlaris</h5>
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
          <h5 class="card-title mb-3">Produk Stok Rendah</h5>
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
    <div class="col-12">
      <div class="card shadow rounded-4 border-0">
        <div class="card-body">
          <h5 class="card-title mb-3">Grafik Pendapatan Bulanan</h5>
          <div class="chart-container" style="height: 320px;">
            <canvas id="revenueChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- Logout Modal --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">Apakah Anda yakin ingin logout dari sistem?</div>
          <div class="modal-footer">
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-danger">Logout</button>
            </form>
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
        borderColor: 'rgba(75, 192, 192, 1)',
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
        legend: {
          display: true,
          labels: {
            color: '#666'
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            color: '#666'
          }
        },
        x: {
          ticks: {
            color: '#666'
          }
        }
      }
    }
  });
</script>
@endsection
