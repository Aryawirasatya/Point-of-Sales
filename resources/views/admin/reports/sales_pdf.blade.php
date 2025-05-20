<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Transaksi</title>
  <style>
    body { font-family: sans-serif; }
    h3 { text-align: center; }
    .periode { text-align: center; margin-top: -10px; font-size: 0.9rem; }
    table { width:100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border:1px solid #000; padding: 6px; }
    th { background: #f0f0f0; }
  </style>
</head>
<body>
  <h3>Laporan Transaksi Penjualan</h3>
  @if(isset($r))
    <div class="periode">
      Periode: {{ $r->start_date ?? '-' }} s/d {{ $r->end_date ?? '-' }}
    </div>
  @endif
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>No Invoice</th>
        <th>Kasir</th>
        <th>Total</th>
        <th>dibayar</th>
        <th>kembalian</th>
        <th>Status</th>
        <th>Tanggal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sales as $index => $s)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $s->invoice_number }}</td>
        <td>{{ $s->user->name }}</td>
        <td>Rp {{ number_format($s->total_amount,2,',','.') }}</td>
        <td>Rp {{ number_format($s->paid_amount,2,',','.') }}</td>    {{-- baru --}}
         <td>Rp {{ number_format($s->change_amount,2,',','.') }}</td>
        <td>{{ ucfirst($s->payment_status) }}</td>
        <td>{{ $s->created_at->format('Y-m-d H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
