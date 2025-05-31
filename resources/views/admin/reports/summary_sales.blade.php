@extends('layout.app')

@section('content')
<style>
    /* Styling agar sinkron dengan laporan lain */
    .card-header, .card-body {
        background-color: #3a3d42 !important;
        color: white !important;
    }
    .card {
        border-radius: 12px;
        background-color: #3a3d42 !important;
    }
    .table-wrapper {
        overflow: hidden !important;
    }
    .table th, .table td {
        background-color: #3a3d42 !important;
        color: white !important;
    }
    .form-label {
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
            <h4 class="mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Laporan Ringkasan Penjualan
            </h4>
            <small>Periode: {{ $startDate }} s/d {{ $endDate }}</small>
        </div>
        <div class="card-body">

            <!-- Tombol Export Excel -->
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('admin.admin.reports.summary_sales.export_excel', request()->all()) }}" 
                   class="btn btn-sm btn-success me-2">
                   <i class="fas fa-file-excel"></i> Export Excel
                </a>
                 <a href="{{ route('admin.admin.reports.summary_sales.export_pdf', request()->all()) }}"
                   class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>

            <!-- Form Filter Tanggal -->
            <div class="mb-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-auto">
                        <label class="form-label">Dari:</label>
                        <input type="date" name="start_date" 
                               class="form-control form-control-sm" 
                               value="{{ $startDate }}">
                    </div>
                    <div class="col-auto">
                        <label class="form-label">Sampai:</label>
                        <input type="date" name="end_date" 
                               class="form-control form-control-sm" 
                               value="{{ $endDate }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.admin.reports.summary_sales') }}" 
                           class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Ringkasan Total -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Omset</h5>
                            <p class="card-text display-6">
                                Rp {{ number_format($totalOmset, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Transaksi</h5>
                            <p class="card-text display-6">
                                {{ $totalTransaksi }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Rata‑Rata Omset/Transaksi</h5>
                            <p class="card-text display-6">
                                Rp {{ number_format($avgOmset, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Breakdown Per‑Hari -->
            <div class="mb-5">
                <h5 class="text-white">Breakdown Per‑Hari</h5>
                <div class="table-responsive table-wrapper">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Omset (Rp)</th>
                                <th>Jumlah Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dailyBreakdown as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->tgl)->format('Y-m-d') }}</td>
                                    <td>Rp {{ number_format($day->omset, 2, ',', '.') }}</td>
                                    <td>{{ $day->transaksi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Tidak ada data penjualan per‑hari dalam periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Breakdown Per‑Minggu -->
            <div class="mb-5">
                <h5 class="text-white">Breakdown Per‑Minggu</h5>
                <div class="table-responsive table-wrapper">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tahun</th>
                                <th>Minggu Ke</th>
                                <th>Rentang Tanggal</th>
                                <th>Omset (Rp)</th>
                                <th>Jumlah Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($weeklyBreakdown as $week)
                                @php
                                    $startOfWeek = \Carbon\Carbon::now()
                                                         ->setISODate($week->year, $week->minggu)
                                                         ->startOfWeek(\Carbon\Carbon::MONDAY);
                                    $endOfWeek   = \Carbon\Carbon::now()
                                                         ->setISODate($week->year, $week->minggu)
                                                         ->endOfWeek(\Carbon\Carbon::SUNDAY);
                                @endphp
                                <tr>
                                    <td>{{ $week->year }}</td>
                                    <td>{{ $week->minggu }}</td>
                                    <td>
                                        {{ $startOfWeek->format('Y-m-d') }} s/d {{ $endOfWeek->format('Y-m-d') }}
                                    </td>
                                    <td>Rp {{ number_format($week->omset, 2, ',', '.') }}</td>
                                    <td>{{ $week->transaksi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        Tidak ada data penjualan per‑minggu dalam periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabel Breakdown Per‑Bulan -->
            <div class="mb-5">
                <h5 class="text-white">Breakdown Per‑Bulan</h5>
                <div class="table-responsive table-wrapper">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Omset (Rp)</th>
                                <th>Jumlah Transaksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyBreakdown as $month)
                                <tr>
                                    <td>{{ $month->year }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::create($month->year, $month->bulan, 1)
                                           ->translatedFormat('F Y') }}
                                    </td>
                                    <td>Rp {{ number_format($month->omset, 2, ',', '.') }}</td>
                                    <td>{{ $month->transaksi }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Tidak ada data penjualan per‑bulan dalam periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div> <!-- end card-body -->
    </div> <!-- end card -->
</div> <!-- end container -->
@endsection
