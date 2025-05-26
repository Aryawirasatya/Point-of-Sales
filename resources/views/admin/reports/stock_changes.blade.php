@extends('layout.app')

@section('content')
<style>
    .card-header,.card-body{
        background-color: #3a3d42 !important;

    }
    .card-body{
        border-radius:1rem;
        height:100%;

        
    }
    .card{
        border-radius: 12%;
        background-color: #3a3d42 !important;


    }
    .table-wrapper{
            border-radius: 1rem !important;
        overflow: hidden !important;

    }
    form .col-auto .form-label {
    color: white !important;
}

    .table {
    }
    
    .table th ,td{
        background-color: #3a3d42 !important;
        color: white !important;
    }`
    
    form .col-auto label{
    border-radius: 1rem;

}

input[type="date"]{
    background-color: #3a3d42;
    border:1px solid #555;
    color: whitesmoke;
    border-radius: 0.5rem;
    padding: 0.4rem 0.6rem;
}

input[type="date"]::placeholder{
    color:#ccc;
}


 

</style>
<div class="container mt-5">

    <div class="card shadow rounded-4">
        <div class="card-header bg-info text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-sync-alt me-2"></i>Laporan Perubahan Stok Produk</h4>
        </div>

        <div class="card-body ">

        {{-- Filter Tanggal --}}
        <form method="GET" class="row g-3 align-items-end mb-4">
            <div class="col-auto">
                <label class="form-label">Dari:</label>
                <input type="date" name="start_date" value="{{ $start }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto">
                <label class="form-label">Sampai:</label>
                <input type="date" name="end_date" value="{{ $end }}" class="form-control form-control-sm">
            </div>
            <div class="col-auto d-flex align-items-end">
                <div>
                    <button type="submit" class="btn btn-sm btn-info text-white me-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.reports.stock_changes') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </div>
        </form>


            {{-- Aksi Ekspor & Print --}}
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.reports.stock_changes.excel', request()->all()) }}"
                       class="btn-sm m-1"
                       style="background-color:rgb(71, 79, 93); color: white; border-radius: 1rem;">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('admin.reports.sales.pdf') }}"
                       class="btn-sm"
                       style="background-color:rgb(99, 129, 181); color: white; border-radius: 1rem;">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
 
            </div>

            {{-- Tabel Data --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Terakhir Diubah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock_quantity }}</td>
                            <td>{{ $product->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Tidak ada data perubahan stok.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
@endsection
