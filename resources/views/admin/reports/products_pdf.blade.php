<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Produk</title>
  <style>
    body { font-family: sans-serif; }
    h3, h4 { text-align: center; margin-bottom: 5px; }
    .subtitle { margin-top: 0; font-size: 0.9rem; color: #555; }
    table { width:100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border:1px solid #000; padding: 6px; font-size: 0.85rem; }
    th { background: #f0f0f0; }
    .section { margin-bottom: 20px; }
  </style>
</head>
<body>
  {{-- Judul Utama --}}
  <h3>Laporan Produk</h3>

  {{-- Bagian 1: Produk Terlaris --}}
  <div class="section">
    <h4>Produk Terlaris (Top 5)</h4>
    <p class="subtitle">Berdasarkan total penjualan terbanyak</p>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Produk</th>
          <th>Total Terjual</th>
        </tr>
      </thead>
      <tbody>
        @foreach($produkTerlaris as $index => $p)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->total_terjual }}</td>
          </tr>
        @endforeach

        @if($produkTerlaris->isEmpty())
          <tr>
            <td colspan="3" style="text-align:center; color: #777;">Tidak ada data produk terlaris.</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  {{-- Bagian 2: Produk Stok Rendah --}}
  <div class="section">
    <h4>Produk Stok Rendah (Stok &lt; 10)</h4>
    <p class="subtitle">Menampilkan 5 produk dengan stok tersisa paling sedikit</p>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nama Produk</th>
          <th>Stok Tersisa</th>
        </tr>
      </thead>
      <tbody>
        @foreach($produkStokRendah as $index => $p)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $p->name }}</td>
            <td>{{ $p->stock_quantity }}</td>
          </tr>
        @endforeach

        @if($produkStokRendah->isEmpty())
          <tr>
            <td colspan="3" style="text-align:center; color: #777;">Semua stok aman.</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>

  {{-- Bagian 3: Semua Produk --}}
  <div class="section">
    <h4>Daftar Semua Produk</h4>
    <p class="subtitle">Urutkan A–Z berdasarkan nama produk</p>
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

        @if($items->isEmpty())
          <tr>
            <td colspan="6" style="text-align:center; color: #777;">Tidak ada data produk.</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</body>
</html>
