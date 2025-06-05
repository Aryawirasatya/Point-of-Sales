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
          <h5 class="mb-0 text-dark">
            <i class="fas fa-receipt me-2 text-primary"></i>Riwayat Transaksi Terbaru
          </h5>
        </div>
        <div class="card-body p-0">
          @if($recentSales->isEmpty())
            <p class="text-center text-muted my-4">Belum ada transaksi terbaru.</p>
          @else
            <div class="table-responsive">
              <table class="table table-borderless table-hover align-middle mb-0">
                <thead class="bg-light text-muted">
                  <tr>
                    <th class="px-4">#</th>
                    <th>Invoice</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentSales as $index => $sale)
                    <tr>
                      <td class="px-4">{{ $index + 1 }}</td>
                      <td class="fw-medium text-dark">{{ $sale->invoice_number }}</td>
                      <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                      <td>
                        <span class="badge rounded-pill 
                          {{ $sale->payment_status === 'paid'
                              ? 'bg-success-subtle text-success'
                              : 'bg-warning-subtle text-warning' }}">
                          {{ ucfirst($sale->payment_status) }}
                        </span>
                      </td>
                      <td>{{ $sale->created_at->format('d M Y, H:i') }}</td>
                      <td class="text-center">
                        <a href="{{ route('cashier.transactions.show', $sale->id) }}"
                           class="btn btn-sm btn-outline-primary px-3 rounded-pill">
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

    {{-- Daftar Produk --}}
    <div class="col-12 mt-4">
      <h5 class="mb-3 fw-semibold"><i class="fas fa-boxes-stacked me-2"></i>Daftar Produk</h5>
      <div class="row">
        @forelse($products as $product)
          <div class="col-6 col-md-4 col-lg-3 mb-4">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-100 transition-card dark-card">
              @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top" 
                     alt="{{ $product->name }}"
                     style="height: 220px; object-fit: cover;">
              @else
                <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-muted" 
                     style="height: 220px;">
                  <span>Tidak ada gambar</span>
                </div>
              @endif

              <div class="card-body d-flex flex-column" style="background-color: #31363F;">
                <h5 class="card-title mb-1">{{ $product->name }}</h5>
                <small class="text-light d-block mb-1">Barcode: {{ $product->barcode ?? '-' }}</small>
                <small class="text-light d-block mb-1">Kategori: {{ $product->category->name ?? '-' }}</small>
                <small class="text-light d-block mb-1">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                <small class="text-light d-block mb-2">Stok: {{ $product->stock_quantity }}</small>
                <p class="card-text text-truncate mb-2" style="max-height: 4.5em;">
                  {{ Str::limit($product->description, 100) ?? 'Tidak ada deskripsi.' }}
                </p>
                <a href="#" class="text-info small mt-auto"
                   onclick="showDescriptionModal(event, '{{ addslashes($product->name) }}', `{{ addslashes(strip_tags($product->description)) }}`)">
                  Lihat Deskripsi
                </a>
              </div>
            </div>
          </div>
        @empty
          <p class="text-muted">Belum ada produk.</p>
        @endforelse
      </div>
    </div>

  </div>
</div>

{{-- Modal Deskripsi --}}
<div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="descModalTitle">Deskripsi Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="descModalBody">
        <div class="text-muted">Memuat...</div>
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

/* Daftar Produk */
.transition-card {
  transition: transform 0.3s ease;
}
.transition-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.15);
}
.dark-card {
  background-color: #2b2b2b;
  color: #e0e0e0;
}
.bg-secondary-subtle {
  background-color: #f0f0f0;
}
.card-title {
  font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
function showDescriptionModal(event, title, description) {
  event.preventDefault();
  const modal = new bootstrap.Modal(document.getElementById('descModal'));
  document.getElementById('descModalTitle').innerText = title;
  document.getElementById('descModalBody').innerHTML = description || '<em class="text-muted">Tidak ada deskripsi.</em>';
  modal.show();
}
</script>
@endpush
@endsection
 