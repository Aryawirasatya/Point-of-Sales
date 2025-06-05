@extends('layout.app')

@section('content')
<style>
    .card-header,
    .card-body {
        background-color: #3a3d42 !important;
    }

    .card-body {
        border-radius: 1rem;
        height: 100%;
    }

    .card {
        border-radius: 12%;
        background-color: #3a3d42 !important;
    }

    .table-wrapper {
        overflow: hidden !important;
    }

    form .col-auto .form-label {
        color: white !important;
    }

    .table th,
    .table td {
        background-color: #3a3d42 !important;
        color: white !important;
    }

    .btn-excel {
        background-color: rgb(71, 79, 93);
        color: white;
        border-radius: 1rem;
    }

    .btn-pdf {
        background-color: rgb(99, 129, 181);
        color: white;
        border-radius: 1rem;
    }
</style>

<div class="container mt-5">
    <div class="card shadow rounded-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h4 class="mb-0">
                <i class="fas fa-boxes-stacked me-2"></i> Laporan Produk
            </h4>
        </div>

        <div class="card-body">

            {{-- Aksi Ekspor & Print --}}
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.reports.products.excel', request()->all()) }}" class="btn-sm m-1 btn-excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('admin.reports.products.pdf', request()->all()) }}" class="btn-sm m-1 btn-pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            <div class="row g-3">
                            {{-- Produk Terlaris --}}
                <div class="col-md-6">
                    <div class="card shadow-sm rounded-4 border-2 border border-white bg-dark text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="fas fa-star text-warning me-2"></i> Produk Terlaris
                            </h5>
                            @forelse ($produkTerlaris as $produk)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded bg-secondary bg-opacity-25">
                                    <span class="fw-semibold">{{ $produk->name }}</span>
                                    <span class="badge bg-primary px-3 py-2">{{ $produk->total_terjual }}</span>
                                </div>
                            @empty
                                <div class="text-muted text-center py-3">Belum ada data.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Produk Stok Rendah --}}
                <div class="col-md-6">
                    <div class="card shadow-sm rounded-4 border-2 border border-white bg-dark text-white h-100">
                        <div class="card-body">
                            <h5 class="card-title mb-4 d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i> Produk Stok Rendah
                            </h5>
                            @forelse ($produkStokRendah as $produk)
                                <div class="d-flex justify-content-between align-items-center mb-3 p-3 rounded bg-secondary bg-opacity-25">
                                    <span class="fw-semibold">{{ $produk->name }}</span>
                                    <span class="badge bg-danger px-3 py-2">{{ $produk->stock_quantity }}</span>
                                </div>
                            @empty
                                <div class="text-muted text-center py-3">Semua stok aman.</div>
                            @endforelse
                        </div>
                    </div>
                </div>


            {{-- Tabel Data Produk --}}
            <div class="table-responsive table-wrapper mt-4">
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
