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
        border-radius: 50%;
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
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="fas fa-receipt me-2"></i>Laporan Transaksi Penjualan</h4>
        </div>

        <div class="card-body">
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
                <div class="col-auto mt-4">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>

            {{-- Aksi Ekspor & Print --}}
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <a href="{{ route('admin.reports.sales.excel', request()->all()) }}"
                       class="btn-sm m-1 btn-excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('admin.reports.sales.pdf') }}"
                       class="btn-sm m-1 btn-pdf">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
                <button onclick="window.print()" class="btn btn-secondary btn-sm">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            {{-- Tabel Data --}}
            <div class="table-responsive table-wrapper">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No Invoice</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Dibayar</th>
                            <th>Kembalian</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>Rp{{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                            <td>Rp{{ number_format($sale->paid_amount, 2, ',', '.') }}</td>
                            <td>Rp{{ number_format($sale->change_amount, 2, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($sale->payment_status) }}
                                </span>
                            </td>
                            <td>{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada transaksi ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
