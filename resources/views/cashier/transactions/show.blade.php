@extends('layout.app')
@push('styles')
<!-- CSS lain… -->

<style media="print">
  /* 1) Sembunyikan elemen layout di luar invoice */
  @page {
    size: A4 portrait;
    margin: 10mm;
  }

  /* Print-only styles */
  @media print {
    /* 1) Sembunyikan elemen layout di luar invoice */
    body * {
      visibility: hidden !important;
    }

    /* 2) Tampilkan invoice dan anak-anaknya */
    #invoice, 
    #invoice * {
      visibility: visible !important;
    }

    /* 3) Posisi invoice agar memenuhi kertas */
    #invoice {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
    }

    /* 4) Optimasi tabel tanpa merubah display */
    #invoice table {
      width: 100% !important;
      border-collapse: collapse !important;
      font-size: 12px !important;
    }

    #invoice th, #invoice td {
      padding: 4px !important;
      border: 1px solid #333 !important;
      word-break: break-word !important;
    }

    #invoice tr {
      page-break-inside: avoid !important;
    }

    /* Hilangkan tombol atau elemen dengan class no-print */
    .no-print {
      display: none !important;
    }

    /* Optimasi teks dan spasi */
    body {
      font-size: 12px;
      line-height: 1.4;
    }

    h2 {
      font-size: 18px !important;
      margin-bottom: 0.2rem;
    }

    h5 {
      font-size: 14px !important;
    }
  }
</style>



@endpush

@section('content')
<div class="container py-4">
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show no-print" role="alert">
    ✅ Transaksi berhasil disimpan
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
  <div id="invoice" class="p-4 border" style="max-width:800px; margin:auto;">
    <div class="mb-4 text-center">
      <h2 class="fw-bold mb-1">Toko Kelontong XYZ</h2>
      <p class="mb-0">Jl. Merdeka No.123, Jakarta</p>
    </div>
    <div class="card shadow-sm">
      <div class="card-header bg-white border-bottom-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Invoice <span class="text-primary">{{ $sale->invoice_number }}</span></h5>
        <small class="text-muted">
          Kasir : {{ $sale->user->name }} • {{ $sale->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
        </small>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-striped mb-0">
            <thead class="table-light">
              <tr>
                <th>Produk</th>
                <th>Deskripsi</th>
                <th class="text-center" style="width:50px;">Qty</th>
                <th class="text-end" style="width:100px;">Harga</th>
                <th class="text-end" style="width:100px;">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($sale->items as $item)
                <tr>
                  <td>{{ optional($item->product)->name ?? '— produk hilang —' }}</td>
                  <td>{{ optional($item->product)->description ?? '-' }}</td>
                  <td class="text-center">{{ $item->quantity }}</td>
                  <td class="text-end">Rp{{ number_format($item->unit_price,0,',','.') }}</td>
                  <td class="text-end">Rp{{ number_format($item->total_price,0,',','.') }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-start">Total   :</th>
                <th class="text-end">Rp{{ number_format($sale->total_amount,0,',','.') }}</th>
              </tr>
              <tr>
                <th colspan="4" class="text-start">Bayar      :</th>
                <th class="text-end">Rp{{ number_format($sale->paid_amount,0,',','.') }}</th>
              </tr>
              <tr>
                <th colspan="4" class="text-start">Kembalian     :</th>
                <th class="text-end">Rp{{ number_format($sale->change_amount,0,',','.') }}</th>
              </tr>
              <tr>
                <th colspan="4" class="text-start">Status     :</th>
                <th class="text-end">{{ ucfirst($sale->payment_status) }}</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
<div class="mb-3 mt-3 text-center no-print">
  <a href="{{ route('cashier.transactions.create') }}" class="btn btn-secondary">Transaksi Baru</a>
  <button class="btn btn-primary" onclick="window.print()">Cetak</button>
</div>
</div>

@endsection
