{{-- resources/views/admin/reports/summary_sales_pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Ringkasan Penjualan</title>
    <style>
        /* CSS sederhana untuk PDF A4 */
        body {
            font-family: DejaVu Sans, sans-serif; /* Pastikan font mendukung karakter UTF-8 (Indonesia) */
            font-size: 12px;
            margin: 20px;
        }
        h2, h4 {
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .periode {
            text-align: center;
            margin: 5px 0 20px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 12px;
        }
        table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }
        .section-title {
            background-color: #ccc;
            padding: 4px;
            font-size: 13px;
            margin-top: 20px;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Ringkasan Penjualan</h2>
    </div>
    <div class="periode">
        Periode: {{ $startDate }} s/d {{ $endDate }}
    </div>

    <!-- Ringkasan Total -->
    <table>
        <thead>
            <tr>
                <th>Total Omset (Rp)</th>
                <th>Total Transaksi</th>
                <th>Rata-Rata Omset/Transaksi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rp {{ number_format($totalOmset, 2, ',', '.') }}</td>
                <td>{{ $totalTransaksi }}</td>
                <td>Rp {{ number_format($avgOmset, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Breakdown Per‑Hari -->
    <div class="section-title">Breakdown Per‑Hari</div>
    <table>
        <thead>
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
                    <td colspan="3" style="text-align: center;">Tidak ada data per‑hari</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Breakdown Per‑Minggu -->
    <div class="section-title">Breakdown Per‑Minggu</div>
    <table>
        <thead>
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
                    <td colspan="5" style="text-align: center;">Tidak ada data per‑minggu</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Breakdown Per‑Bulan -->
    <div class="section-title">Breakdown Per‑Bulan</div>
    <table>
        <thead>
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
                    <td colspan="4" style="text-align: center;">Tidak ada data per‑bulan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
