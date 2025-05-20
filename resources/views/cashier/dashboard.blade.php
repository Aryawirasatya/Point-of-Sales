@extends('layout.app')

@section('content')
<div class="container-fluid py-4">

  <div class="row g-4">

    {{-- Ringkasan Metric --}}
    <div class="col-md-6">
      <div class="card bg-gradient-success text-white shadow rounded-4 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center py-5">
          <i class="fas fa-shopping-cart fa-2x mb-2"></i>
          <h5 class="card-title">Transaksi Hari Ini</h5>
          <h2 class="fw-bold">{{ $todayTransactions }}</h2>
          <small class="text-white-50">Transaksi berhasil</small>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card bg-gradient-warning text-dark shadow rounded-4 h-100">
        <div class="card-body d-flex flex-column justify-content-center text-center py-5">
          <i class="fas fa-coins fa-2x mb-2"></i>
          <h5 class="card-title">Total Pendapatan</h5>
          <h2 class="fw-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
          <small class="text-muted">Pendapatan hari ini</small>
        </div>
      </div>
    </div>

    {{-- Riwayat Transaksi Terbaru --}}
    <div class="col-12">
      <div class="card shadow-sm rounded-4">
        <div class="card-header bg-light rounded-top-4 d-flex justify-content-between align-items-center">
          <h5 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Riwayat Transaksi Terbaru</h5>
           
        </div>
        <div class="card-body">
          @if($recentSales->isEmpty())
            <p class="text-muted text-center my-4">Belum ada transaksi terbaru.</p>
          @else
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>#</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentSales as $sale)
                  <tr class="table-row-hover">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    <td>
                      <span class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : 'warning' }}">
                        {{ ucfirst($sale->payment_status) }}
                      </span>
                    </td>
                    <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                    <td>
                      <a href="{{ route('cashier.transactions.show', $sale->id) }}"
                         class="btn btn-sm btn-outline-primary">
                        Detail
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    </div>

  </div>
</div>

{{-- Dark Mode Script --}}
<script>
  document.getElementById('toggleDark').addEventListener('click', () => {
    document.body.classList.toggle('bg-dark');
    document.body.classList.toggle('text-white');
  });
</script>

{{-- Tambahan CSS --}}
<style>
  .bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
  }
  .bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
  }
  .table-row-hover:hover {
    background-color: rgba(0, 0, 0, 0.03);
  }
  #toggleDark {
    transition: background-color 0.3s, color 0.3s;
  }
  #toggleDark:hover {
    background-color: #6c757d;
    color: #fff;
  }
</style>
@endsection
