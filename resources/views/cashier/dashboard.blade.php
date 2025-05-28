@extends('layout.app')

@section('content')
<div class="container-fluid py-4">
  <div class="row g-4">

    {{-- Ringkasan Metric --}}
    <div class="col-md-6">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body text-center py-4">
          <div class="icon-circle mb-3 bg-success-subtle text-success">
            <i class="fas fa-shopping-cart fa-2x"></i>
          </div>
          <h6 class="text-uppercase text-muted">Transaksi Hari Ini</h6>
          <h2 class="fw-bold text-dark">{{ $todayTransactions }}</h2>
          <small class="text-secondary">Transaksi berhasil</small>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body text-center py-4">
          <div class="icon-circle mb-3 bg-warning-subtle text-warning">
            <i class="fas fa-coins fa-2x"></i>
          </div>
          <h6 class="text-uppercase text-muted">Total Pendapatan</h6>
          <h2 class="fw-bold text-dark">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
          <small class="text-secondary">Pendapatan hari ini</small>
        </div>
      </div>
    </div>

    {{-- Riwayat Transaksi Terbaru --}}
    <div class="col-12">
      <div class="card shadow-sm rounded-4 border-0">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 text-dark"><i class="fas fa-receipt me-2 text-primary"></i>Riwayat Transaksi Terbaru</h5>
        </div>
        <div class="card-body p-0">
          @if($recentSales->isEmpty())
            <p class="text-center text-muted my-4">Belum ada transaksi terbaru.</p>
          @else
            <div class="table-responsive">
              <table class="table table-borderless table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
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
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-medium text-dark">{{ $sale->invoice_number }}</td>
                    <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                    <td>
                      <span class="badge rounded-pill {{ $sale->payment_status === 'paid' ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                        {{ ucfirst($sale->payment_status) }}
                      </span>
                    </td>
                    <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                    <td>
                      <a href="{{ route('cashier.transactions.show', $sale->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">
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

@push('styles')
<style>
.icon-circle {
  width: 60px;
  height: 60px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  margin: 0 auto;
}
.bg-success-subtle { background-color: #e6f4ea; }
.bg-warning-subtle { background-color: #fff9e6; }
.card {
  transition: transform .2s ease, box-shadow .2s ease;
}
.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}
.table-borderless th,
.table-borderless td {
  border: none;
}
</style>
@endpush

@endsection
