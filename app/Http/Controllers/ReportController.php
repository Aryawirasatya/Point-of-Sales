<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;        // Model produk Anda
use App\Models\Sale;            // Model penjualan Anda
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Landing page untuk semua laporan
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Tampilkan Laporan Produk (HTML)
     */
    public function productReport(Request $r)
    {
        // Optional: implementasi pagination/search:
        $products = Products::with('category')
            ->when($r->filled('search'), fn($q)=> $q->where('name','like',"%{$r->search}%"))
            ->orderBy('name')
            ->get();

        return view('admin.reports.products', compact('products'));
    }

    /**
     * Tampilkan Laporan Transaksi (HTML)
     */
    public function salesReport(Request $r)
    {
        $start = $r->start_date ?? now()->startOfMonth()->toDateString();
        $end   = $r->end_date   ?? now()->endOfMonth()->toDateString();

        $sales = Sale::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reports.sales', compact('sales','start','end'));
    }

    /**
     * Tampilkan Laporan Perubahan Stok (HTML)
     */
    public function stockChanges(Request $r)
    {
        $start = $r->start_date ?? now()->startOfMonth()->toDateString();
        $end   = $r->end_date   ?? now()->endOfMonth()->toDateString();

        $products = Products::whereBetween('updated_at', [$start, $end])
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.reports.stock_changes', compact('products','start','end'));
    }

    /**
     * Export Produk ke Excel
     */
    public function productsExcel()
{
    // a) Ambil data produk beserta kategori
    $items = Products::with('category')->orderBy('name')->get();

    // b) Buat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // c) Tulis header (baris 1)
    $sheet->fromArray(
        ['Nama Produk','Kategori','Barcode','Harga','Stok'],
        null,
        'A1'
    );

    // d) Tulis data mulai baris 2
    $row = 2;
    foreach ($items as $p) {
        $sheet->setCellValue("A{$row}", $p->name);
        $sheet->setCellValue("B{$row}", $p->category->name ?? '-');
        $sheet->setCellValue("C{$row}", $p->barcode);
        $sheet->setCellValue("D{$row}", $p->price);
        $sheet->setCellValue("E{$row}", $p->stock_quantity);
        $row++;
    }

    // e) Simpan file ke storage/app/exports
    $filename = 'laporan-produk-' . now()->format('YmdHis') . '.xlsx';
    $path = storage_path("app/exports/{$filename}");
    (new Xlsx($spreadsheet))->save($path);

    // f) Download dan hapus file setelah kirim
    return response()
        ->download($path, 'laporan-produk.xlsx')
        ->deleteFileAfterSend(true);
}


    /**
     * Export Transaksi ke Excel
     */
    public function salesExcel(Request $r)
{
    // a) Ambil data transaksi sesuai filter tanggal
    $query = Sale::with('user');
    if ($r->filled('start_date') && $r->filled('end_date')) {
        $query->whereBetween('created_at', [$r->start_date, $r->end_date]);
    }
    $sales = $query->orderByDesc('created_at')->get();

    // b) Buat Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(
        ['Invoice','Kasir','Total','dibayar','kembalian','Status','Tanggal'],
        null,
        'A1'
    );

    // c) Isi data
    $row = 2;
    foreach ($sales as $s) {
        $sheet->setCellValue("A{$row}", $s->invoice_number);
        $sheet->setCellValue("B{$row}", $s->user->name ?? '-');
        $sheet->setCellValue("C{$row}", $s->total_amount);
        $sheet->setCellValue("D{$row}", $s->paid_amount);      // baru
        $sheet->setCellValue("E{$row}", $s->change_amount);    // baru
        $sheet->setCellValue("F{$row}", $s->payment_status);
        $sheet->setCellValue("G{$row}", $s->created_at->format('Y-m-d H:i'));
        $row++;
    }

    // d) Simpan & download
    $filename = 'laporan-transaksi-' . now()->format('YmdHis') . '.xlsx';
    $path = storage_path("app/exports/{$filename}");
    (new Xlsx($spreadsheet))->save($path);
    return response()
        ->download($path, 'laporan-transaksi.xlsx')
        ->deleteFileAfterSend(true);
}


    /**
     * Export Perubahan Stok ke Excel
     */
    public function stockChangesExcel(Request $r)
{
    // a) Ambil data stok sesuai filter
    $query = Products::query();
    if ($r->filled('start_date') && $r->filled('end_date')) {
        $query->whereBetween('updated_at', [$r->start_date, $r->end_date]);
    }
    $items = $query->orderByDesc('updated_at')->get();

    // b) Buat Spreadsheet & header
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->fromArray(
        ['Nama Produk','Stok','Terakhir Diubah'],
        null,
        'A1'
    );

    // c) Isi data
    $row = 2;
    foreach ($items as $p) {
        $sheet->setCellValue("A{$row}", $p->name);
        $sheet->setCellValue("B{$row}", $p->stock_quantity);
        $sheet->setCellValue("C{$row}", $p->updated_at->format('Y-m-d H:i'));
        $row++;
    }

    // d) Simpan & download
    $filename = 'laporan-stok-' . now()->format('YmdHis') . '.xlsx';
    $path = storage_path("app/exports/{$filename}");
    (new Xlsx($spreadsheet))->save($path);
    return response()
        ->download($path, 'laporan-stok.xlsx')
        ->deleteFileAfterSend(true);
}


    /**
     * Export Produk ke PDF
     */
    public function stockChangesPdf(Request $r)
{
    $query = Products::query();
    if ($r->filled('start_date') && $r->filled('end_date')) {
        $query->whereBetween('updated_at', [$r->start_date, $r->end_date]);
    }
    $items = $query->orderByDesc('updated_at')->get();

    $pdf = Pdf::loadView(
        'admin.reports.stock_changes_pdf',
        compact('items','r')
    );

    return $pdf->download('laporan-stok.pdf');
}



    /**
     * Export Transaksi ke PDF
     */
    public function salesPdf(Request $r)
{
    $query = Sale::with('user');
    if ($r->filled('start_date') && $r->filled('end_date')) {
        $query->whereBetween('created_at', [$r->start_date, $r->end_date]);
    }
    $sales = $query->orderByDesc('created_at')->get();

    // Kirim kedua variabel: sales + request
    $pdf = Pdf::loadView(
        'admin.reports.sales_pdf',
        compact('sales','r')
    );

    return $pdf->download('laporan-transaksi.pdf');
}



    /**
     * Export Stok ke PDF
     */
    public function productsPdf()
{
    // a) Ambil data
    $items = Products::with('category')->orderBy('name')->get();

    // b) Generate PDF dari view
    $pdf = Pdf::loadView('admin.reports.products_pdf', compact('items'));

    // c) Unduh
    return $pdf->download('laporan-produk.pdf');
}

public function summarySales(Request $request)
    {
        // 1. Ambil filter tanggal dari request, atau default:
        //    - start_date: tanggal pertama bulan berjalan
        //    - end_date: tanggal hari ini
        $startDateInput = $request->input('start_date');
        $endDateInput   = $request->input('end_date');

        // Jika user belum memilih, default: awal bulan hingga hari ini
        // Carbon::now()->startOfMonth() → 1 hari bulan ini pada jam 00:00:00
        // Carbon::now()->endOfDay() → hari ini pada jam 23:59:59
        $startDate = $startDateInput 
            ? Carbon::parse($startDateInput)->startOfDay() 
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $endDateInput 
            ? Carbon::parse($endDateInput)->endOfDay() 
            : Carbon::now()->endOfDay();

        /**
         * 2. Hitung Ringkasan Total untuk periode:
         *    - Total Omset (sum total_amount)
         *    - Total Transaksi (count baris di tabel sales)
         *    - Rata‑Rata Omset per Transaksi (jika ada transaksi, totalOmset / totalTransaksi)
         */
        $totalOmset = Sale::whereBetween('created_at', [$startDate, $endDate])
                          ->where('payment_status', 'paid')   // hanya transaksi lunas ('paid')
                          ->sum('total_amount');

        $totalTransaksi = Sale::whereBetween('created_at', [$startDate, $endDate])
                              ->where('payment_status', 'paid')
                              ->count();

        $avgOmset = $totalTransaksi > 0 
            ? ($totalOmset / $totalTransaksi) 
            : 0;

        /**
         * 3. Breakdown Per‐Hari (Daily):
         *    Query menggunakan Query Builder dengan SELECT DATE(created_at) as tgl,
         *    SUM(total_amount) as omset, COUNT(*) as transaksi
         *    GROUP BY DATE(created_at)
         */
        $dailyBreakdown = Sale::selectRaw('DATE(created_at) as tgl, 
                                          SUM(total_amount) as omset, 
                                          COUNT(*) as transaksi')
                              ->whereBetween('created_at', [$startDate, $endDate])
                              ->where('payment_status', 'paid')
                              ->groupBy('tgl')
                              ->orderBy('tgl', 'asc')
                              ->get();

        /**
         * 4. Breakdown Per‐Minggu (Weekly):
         *    Kita akan mengelompokkan berdasarkan YEAR(created_at) dan WEEK(created_at, 1).
         *    WEEK(created_at, 1) → mengembalikan nomor minggu ISO‐8601 (minggu dimulai Senin).
         *    Untuk menampilkan rentang tanggal di view, kita bisa menghitung di view
         *    atau menambahkan langsung DB::raw dengan CONCAT / MIN / MAX.
         *    Di sini kita fokus mengumpulkan: 
         *    YEAR(created_at) as year, WEEK(created_at, 1) as minggu, 
         *    SUM(total_amount) as omset, COUNT(*) as transaksi.
         */
        $weeklyBreakdown = Sale::selectRaw('YEAR(created_at) as year, 
                                          WEEK(created_at, 1) as minggu, 
                                          SUM(total_amount) as omset, 
                                          COUNT(*) as transaksi')
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->where('payment_status', 'paid')
                               ->groupBy('year', 'minggu')
                               ->orderBy('year', 'asc')
                               ->orderBy('minggu', 'asc')
                               ->get();

        /**
         * 5. Breakdown Per‐Bulan (Monthly):
         *    Kita gunakan YEAR(created_at) sebagai tahun, MONTH(created_at) sebagai bulan.
         */
        $monthlyBreakdown = Sale::selectRaw('YEAR(created_at) as year, 
                                           MONTH(created_at) as bulan, 
                                           SUM(total_amount) as omset, 
                                           COUNT(*) as transaksi')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->where('payment_status', 'paid')
                                ->groupBy('year', 'bulan')
                                ->orderBy('year', 'asc')
                                ->orderBy('bulan', 'asc')
                                ->get();

        /**
         * 6. Siapkan data / format tambahan jika perlu. Misalnya:
         *    - Ubah tgl di dailyBreakdown menjadi format string 'yyyy-mm-dd'.
         *    - Hitung rentang tanggal per minggu supaya tampil “2025‐05‐05 s/d 2025‐05‐11” di view.
         *    - Ubah nama bulan (contoh “05” menjadi “Mei 2025”).
         *    Kita bisa lakukan formatting di Blade view untuk fleksibilitas.
         */

        // 7. Kirim data ke view 'reports.summary_sales'
        return view('admin.reports.summary_sales', [
            'startDate'         => $startDate->format('Y-m-d'),
            'endDate'           => $endDate->format('Y-m-d'),
            'totalOmset'        => $totalOmset,
            'totalTransaksi'    => $totalTransaksi,
            'avgOmset'          => $avgOmset,
            'dailyBreakdown'    => $dailyBreakdown,
            'weeklyBreakdown'   => $weeklyBreakdown,
            'monthlyBreakdown'  => $monthlyBreakdown,
        ]);
    }

     public function summarySalesExportExcel(Request $request)
    {
        // 1. Ambil filter tanggal dari request, atau default:
        $startDateInput = $request->input('start_date');
        $endDateInput   = $request->input('end_date');

        $startDate = $startDateInput 
            ? Carbon::parse($startDateInput)->startOfDay() 
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $endDateInput 
            ? Carbon::parse($endDateInput)->endOfDay() 
            : Carbon::now()->endOfDay();

        // 2. Query data per‐hari, per‐minggu, per‐bulan
        $daily = Sale::selectRaw('DATE(created_at) as tgl, SUM(total_amount) as omset, COUNT(*) as transaksi')
                     ->whereBetween('created_at', [$startDate, $endDate])
                     ->where('payment_status', 'paid')
                     ->groupBy('tgl')
                     ->orderBy('tgl', 'asc')
                     ->get();

        $weekly = Sale::selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as minggu, SUM(total_amount) as omset, COUNT(*) as transaksi')
                      ->whereBetween('created_at', [$startDate, $endDate])
                      ->where('payment_status', 'paid')
                      ->groupBy('year', 'minggu')
                      ->orderBy('year', 'asc')
                      ->orderBy('minggu', 'asc')
                      ->get();

        $monthly = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as bulan, SUM(total_amount) as omset, COUNT(*) as transaksi')
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->where('payment_status', 'paid')
                       ->groupBy('year', 'bulan')
                       ->orderBy('year', 'asc')
                       ->orderBy('bulan', 'asc')
                       ->get();

        // 3. Buat objek Spreadsheet dan tulis data
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Summary Sales');

        // Tulis judul & periode di baris 1–2
        $sheet->setCellValue('A1', 'Laporan Ringkasan Penjualan');
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'Periode: ' . $startDate->format('Y-m-d') . ' s/d ' . $endDate->format('Y-m-d'));
        $sheet->mergeCells('A2:F2');

        $currentRow = 4;

        // Breakdown Per‐Hari
        $sheet->setCellValue("A{$currentRow}", 'Breakdown Per‑Hari');
        $currentRow++;
        $sheet->setCellValue("A{$currentRow}", 'Tanggal');
        $sheet->setCellValue("B{$currentRow}", 'Omset (Rp)');
        $sheet->setCellValue("C{$currentRow}", 'Jumlah Transaksi');
        $sheet->getStyle("A{$currentRow}:C{$currentRow}")->getFont()->setBold(true);
        $currentRow++;
        foreach ($daily as $day) {
            $tgl = Carbon::parse($day->tgl)->format('Y-m-d');
            $sheet->setCellValue("A{$currentRow}", $tgl);
            $sheet->setCellValue("B{$currentRow}", $day->omset);
            $sheet->setCellValue("C{$currentRow}", $day->transaksi);
            $currentRow++;
        }
        $currentRow += 2;

        // Breakdown Per‐Minggu
        $sheet->setCellValue("A{$currentRow}", 'Breakdown Per‑Minggu');
        $currentRow++;
        $sheet->setCellValue("A{$currentRow}", 'Tahun');
        $sheet->setCellValue("B{$currentRow}", 'Minggu Ke');
        $sheet->setCellValue("C{$currentRow}", 'Rentang Tanggal');
        $sheet->setCellValue("D{$currentRow}", 'Omset (Rp)');
        $sheet->setCellValue("E{$currentRow}", 'Jumlah Transaksi');
        $sheet->getStyle("A{$currentRow}:E{$currentRow}")->getFont()->setBold(true);
        $currentRow++;

        foreach ($weekly as $week) {
            $startOfWeek = Carbon::now()
                                ->setISODate($week->year, $week->minggu)
                                ->startOfWeek(Carbon::MONDAY)
                                ->format('Y-m-d');
            $endOfWeek = Carbon::now()
                              ->setISODate($week->year, $week->minggu)
                              ->endOfWeek(Carbon::SUNDAY)
                              ->format('Y-m-d');
            $rentang = $startOfWeek . ' s/d ' . $endOfWeek;

            $sheet->setCellValue("A{$currentRow}", $week->year);
            $sheet->setCellValue("B{$currentRow}", $week->minggu);
            $sheet->setCellValue("C{$currentRow}", $rentang);
            $sheet->setCellValue("D{$currentRow}", $week->omset);
            $sheet->setCellValue("E{$currentRow}", $week->transaksi);
            $currentRow++;
        }
        $currentRow += 2;

        // Breakdown Per‐Bulan
        $sheet->setCellValue("A{$currentRow}", 'Breakdown Per‑Bulan');
        $currentRow++;
        $sheet->setCellValue("A{$currentRow}", 'Tahun');
        $sheet->setCellValue("B{$currentRow}", 'Bulan');
        $sheet->setCellValue("C{$currentRow}", 'Omset (Rp)');
        $sheet->setCellValue("D{$currentRow}", 'Jumlah Transaksi');
        $sheet->getStyle("A{$currentRow}:D{$currentRow}")->getFont()->setBold(true);
        $currentRow++;
        foreach ($monthly as $month) {
            $tahun = $month->year;
            $namaBulan = Carbon::create($tahun, $month->bulan, 1)
                               ->translatedFormat('F Y');
            $sheet->setCellValue("A{$currentRow}", $tahun);
            $sheet->setCellValue("B{$currentRow}", $namaBulan);
            $sheet->setCellValue("C{$currentRow}", $month->omset);
            $sheet->setCellValue("D{$currentRow}", $month->transaksi);
            $currentRow++;
        }

        // Auto‑size kolom (opsional)
        foreach (range('A','E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Kirim file XLSX ke browser
        $writer = new Xlsx($spreadsheet);
        $fileName = 'summary_sales_' . $startDate->format('Y-m-d') . '_' . $endDate->format('Y-m-d') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

     public function summarySalesExportPdf(Request $request)
    {
        // 1. Ambil filter tanggal dari request, atau gunakan default (awal bulan s/d hari ini)
        $startDateInput = $request->input('start_date');
        $endDateInput   = $request->input('end_date');

        $startDate = $startDateInput 
            ? Carbon::parse($startDateInput)->startOfDay() 
            : Carbon::now()->startOfMonth()->startOfDay();

        $endDate = $endDateInput 
            ? Carbon::parse($endDateInput)->endOfDay() 
            : Carbon::now()->endOfDay();

        // 2. Hitung Ringkasan Total (hanya transaksi 'paid')
        $totalOmset = Sale::whereBetween('created_at', [$startDate, $endDate])
                          ->where('payment_status', 'paid')
                          ->sum('total_amount');

        $totalTransaksi = Sale::whereBetween('created_at', [$startDate, $endDate])
                              ->where('payment_status', 'paid')
                              ->count();

        $avgOmset = $totalTransaksi > 0 
            ? ($totalOmset / $totalTransaksi) 
            : 0;

        // 3. Breakdown Per‑Hari
        $dailyBreakdown = Sale::selectRaw('DATE(created_at) as tgl, SUM(total_amount) as omset, COUNT(*) as transaksi')
                              ->whereBetween('created_at', [$startDate, $endDate])
                              ->where('payment_status', 'paid')
                              ->groupBy('tgl')
                              ->orderBy('tgl', 'asc')
                              ->get();

        // 4. Breakdown Per‑Minggu
        $weeklyBreakdown = Sale::selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as minggu, SUM(total_amount) as omset, COUNT(*) as transaksi')
                               ->whereBetween('created_at', [$startDate, $endDate])
                               ->where('payment_status', 'paid')
                               ->groupBy('year', 'minggu')
                               ->orderBy('year', 'asc')
                               ->orderBy('minggu', 'asc')
                               ->get();

        // 5. Breakdown Per‑Bulan
        $monthlyBreakdown = Sale::selectRaw('YEAR(created_at) as year, MONTH(created_at) as bulan, SUM(total_amount) as omset, COUNT(*) as transaksi')
                                ->whereBetween('created_at', [$startDate, $endDate])
                                ->where('payment_status', 'paid')
                                ->groupBy('year', 'bulan')
                                ->orderBy('year', 'asc')
                                ->orderBy('bulan', 'asc')
                                ->get();

        // 6. Buat objek PDF dengan view khusus (summary_sales_pdf)
        //    Kita harus membuat view 'admin.reports.summary_sales_pdf'
        $pdf = Pdf::loadView('admin.reports.summary_sales_pdf', [
            'startDate'        => $startDate->format('Y-m-d'),
            'endDate'          => $endDate->format('Y-m-d'),
            'totalOmset'       => $totalOmset,
            'totalTransaksi'   => $totalTransaksi,
            'avgOmset'         => $avgOmset,
            'dailyBreakdown'   => $dailyBreakdown,
            'weeklyBreakdown'  => $weeklyBreakdown,
            'monthlyBreakdown' => $monthlyBreakdown,
        ]);

        // 7. Unduh PDF. Nama file bisa disesuaikan.
        $fileName = 'summary_sales_' . $startDate->format('Ymd') . '_' . $endDate->format('Ymd') . '.pdf';
        return $pdf->download($fileName);
    }

}
