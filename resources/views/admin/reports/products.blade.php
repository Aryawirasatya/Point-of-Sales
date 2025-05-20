@extends('layout.app')

@section('content')
<div class="container mt-5">

    <div class="card shadow rounded-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-boxes-stacked me-2"></i>Laporan Produk</h4>
        </div>

        <div class="card-body">

            {{-- Aksi Ekspor dan Print --}}
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.reports.products.excel', request()->all()) }}"
                       class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route(Route::currentRouteName().'.pdf', request()->all()) }}"
                       class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            {{-- Tabel Data Produk --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Barcode</th>
                            <th>Harga</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td>{{ $product->barcode }}</td>
                            <td>Rp{{ number_format($product->price, 2, ',', '.') }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada produk ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
