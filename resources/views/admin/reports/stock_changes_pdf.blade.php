<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Perubahan Stok</title>
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
  <h3>Laporan Perubahan Stok Produk</h3>
  @if(isset($r))
    <div class="periode">
      Periode: {{ $r->start_date ?? '-' }} s/d {{ $r->end_date ?? '-' }}
    </div>
  @endif
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Produk</th>
        <th>Stok</th>
        <th>Terakhir Diubah</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $index => $p)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->stock_quantity }}</td>
        <td>{{ $p->updated_at->format('Y-m-d H:i') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
