<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Produk</title>
  <style>
    body { font-family: sans-serif; }
    h3 { text-align: center; }
    table { width:100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border:1px solid #000; padding: 6px; }
    th { background: #f0f0f0; }
  </style>
</head>
<body>
  <h3>Laporan Produk</h3>
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Barcode</th>
        <th>Harga</th>
        <th>Stok</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $index => $p)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $p->name }}</td>
        <td>{{ $p->category->name ?? '-' }}</td>
        <td>{{ $p->barcode }}</td>
        <td>Rp {{ number_format($p->price,2,',','.') }}</td>
        <td>{{ $p->stock_quantity }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
