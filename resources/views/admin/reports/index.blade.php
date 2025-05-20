@extends('layout.app')
@section('content')
<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">

      <div class="card shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
          <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Daftar Laporan</h4>
        </div>

        <div class="list-group list-group-flush">
          <a href="{{ route('admin.reports.products') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="fas fa-box me-2 text-secondary"></i>Laporan Produk</span>
            <i class="fas fa-chevron-right text-muted"></i>
          </a>
          <a href="{{ route('admin.reports.sales') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="fas fa-receipt me-2 text-secondary"></i>Laporan Transaksi</span>
            <i class="fas fa-chevron-right text-muted"></i>
          </a>
          <a href="{{ route('admin.reports.stock_changes') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span><i class="fas fa-warehouse me-2 text-secondary"></i>Laporan Stok</span>
            <i class="fas fa-chevron-right text-muted"></i>
          </a>
        </div>

      </div>

    </div>
  </div>
</div>
@endsection
