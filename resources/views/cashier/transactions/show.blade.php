@extends('layout.app')
@push('styles')
<!-- CSS lain… -->

<style media="print">
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
  <div id="invoice">
{{-- 1) Alert sukses --}}
  <div class="mb-4 text-center">
        <h4>Nama Toko Kelontong XYZ</h4>
        <p>Jl. Merdeka No.123, Jakarta</p>
      </div>
  <div class="card">
    
    <div class="card-header">
      <h3>Invoice {{ $sale->invoice_number }}</h3>
      <small>
        Kasir: {{ $sale->user->name }} • 
        {{ $sale->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i:s') }}
      </small>
    </div>
    <div class="card-body">
      <table class="table">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Deskripsi</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Subtotal</th>
        </tr>
      </thead>
        <tbody>
          @foreach($sale->items as $item)
          <tr>
            <td>{{ optional($item->product)->name ?? '— produk hilang —' }}</td>
            <td>{{ optional($item->product)->description ?? '-' }}</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp{{ number_format($item->unit_price,0,',','.') }}</td>
            <td>Rp{{ number_format($item->total_price,0,',','.') }}</td>
          </tr>
          @endforeach
        </tbody>
      <tfoot>
      <tr>
        <th colspan="4">Total</th>
        <th>Rp{{ number_format($sale->total_amount,0,',','.') }}</th>
      </tr>
      <tr>
        <th colspan="4">Bayar</th>
        <th>Rp{{ number_format($sale->paid_amount,0,',','.') }}</th>
      </tr>
      <tr>
        <th colspan="4">Kembalian</th>
        <th>Rp{{ number_format($sale->change_amount,0,',','.') }}</th>
      </tr>
      <tr>
        <th colspan="4">Status</th>
        <th>{{ ucfirst($sale->payment_status) }}</th>
      </tr>
      </tfoot>

      </table>
    </div>
  </div>
</div>
<div class="mb-3 text-end no-print">
  <a href="{{ route('cashier.transactions.create') }}" class="btn btn-secondary">Transaksi Baru</a>
  <button class="btn btn-primary" onclick="window.print()">Cetak</button>
</div>
</div>

@endsection
