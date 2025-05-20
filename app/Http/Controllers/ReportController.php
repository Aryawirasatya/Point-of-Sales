<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;        // Model produk Anda
use App\Models\Sale;            // Model penjualan Anda
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

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

}
