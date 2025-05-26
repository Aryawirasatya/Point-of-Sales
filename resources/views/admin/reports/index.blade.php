@extends('layout.app')
@section('content')
<div class="container mt-5">
  <div class="row g-4 justify-content-center">
    
    <div class="col-md-4">
      <a href="{{ route('admin.reports.products') }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 rounded-4 h-100 hover-shadow">
          <div class="card-body d-flex align-items-center">
            <div class="me-3">
              <i class="fas fa-box fa-2x text-primary"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="mb-0 text-dark">Laporan Produk</h5>
              <small class="text-muted">Lihat data produk</small>
            </div>
            <i class="fas fa-chevron-right text-muted"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="{{ route('admin.reports.sales') }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 rounded-4 h-100 hover-shadow">
          <div class="card-body d-flex align-items-center">
            <div class="me-3">
              <i class="fas fa-receipt fa-2x text-success"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="mb-0 text-dark">Laporan Transaksi</h5>
              <small class="text-muted">Lihat transaksi penjualan</small>
            </div>
            <i class="fas fa-chevron-right text-muted"></i>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-4">
      <a href="{{ route('admin.reports.stock_changes') }}" class="text-decoration-none">
        <div class="card shadow-sm border-0 rounded-4 h-100 hover-shadow">
          <div class="card-body d-flex align-items-center">
            <div class="me-3">
              <i class="fas fa-warehouse fa-2x text-warning"></i>
            </div>
            <div class="flex-grow-1">
              <h5 class="mb-0 text-dark">Laporan Stok</h5>
              <small class="text-muted">Perubahan stok barang</small>
            </div>
            <i class="fas fa-chevron-right text-muted"></i>
          </div>
        </div>
      </a>
    </div>

  </div>
</div>
@endsection
