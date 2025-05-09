{{-- resources/views/cashier/transactions/create.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">➕ Buat Transaksi</h2>

  {{-- Search Produk --}}
  <div class="row mb-3">
    <div class="col-md-6">
      <input id="search" type="text" class="form-control" placeholder="Cari produk...">
    </div>
  </div>

  {{-- Baris utama: Tabel & Keranjang --}}
  <div class="row">
    {{-- Tabel Produk (8 kolom) --}}
    <div class="col-md-8 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0" id="products-table">
              <thead class="table-light">
                <tr>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th>Deskripsi</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $p)
                <tr>
                  <td>{{ $p->name }}</td>
                  <td>{{ $p->category->name }}</td>
                  <td class="text-truncate" style="max-width:200px;">
                    {{ Str::limit($p->description, 50) }}
                  </td>
                  <td>Rp{{ number_format($p->price,0,',','.') }}</td>
                  <td>{{ $p->stock_quantity }}</td>
                  <td>
                    <button class="btn btn-sm btn-primary"
                            data-id   ="{{ $p->id }}"
                            data-name ="{{ $p->name }}"
                            data-price="{{ $p->price }}"
                            @if($p->stock_quantity===0) disabled @endif
                            onclick="addFromBtn(this)">
                      + Keranjang
                    </button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Keranjang & Pembayaran (4 kolom) --}}
    <div class="col-md-4 mb-4">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Keranjang</h5>

          <form id="checkout-form" method="POST"
                action="{{ route('cashier.transactions.store') }}">
            @csrf
            <input type="hidden" name="items" id="cart-items">

            <ul class="list-group mb-2 flex-grow-1 overflow-auto"
                id="cart-body" style="max-height:300px;">
              <li class="list-group-item text-center text-muted">Belum ada item</li>
            </ul>

            <div class="mb-2">
              <strong>Total:</strong> Rp<span id="cart-total">0</span>
            </div>
            <div class="mb-3">
              <label for="paid" class="form-label">Bayar (Rp)</label>
              <input type="number" class="form-control" id="paid"
                     name="paid" required min="0">
            </div>
            <div class="mb-3">
              <strong>Kembalian:</strong> Rp<span id="change">0</span>
            </div>

            <button type="submit" class="btn btn-success w-100 mt-auto"
                    id="btn-checkout" disabled>
              Checkout
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

 
{{-- Modal Success --}}
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title">✅ Transaksi Berhasil</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          Transaksi berhasil dicatat.
        </div>
        <div class="modal-footer">
          <a href="{{ route('cashier.transactions.create') }}" class="btn btn-secondary">
            Transaksi Baru
          </a>
          <a href="{{ route('cashier.transactions.show', session('invoice_id')) }}"
            class="btn btn-primary">
            Cetak Invoice
          </a>
        </div>
      </div>
    </div>
  </div>
  @endif



{{-- Modal Error --}}
<div class="modal fade" id="errorModal" tabindex="-1"
     aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">❌ Pembayaran Kurang</h5>
        <button type="button" class="btn-close"
                data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Uang yang Anda masukkan tidak mencukupi. Mohon masukkan jumlah yang sesuai.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Filter produk lewat search
  document.getElementById('search').addEventListener('input', function(){
    const q = this.value.toLowerCase();
    document.querySelectorAll('#products-table tbody tr').forEach(row => {
      row.style.display = row.children[0].textContent
        .toLowerCase().includes(q) ? '' : 'none';
    });
  });

  // Keranjang: array of {id,name,price,qty}
  let cart = [];

  function addFromBtn(btn) {
    const id    = +btn.dataset.id;
    const name  = btn.dataset.name;
    const price = +btn.dataset.price;
    addToCart(id, name, price);
  }

  function addToCart(id, name, price) {
    let item = cart.find(i => i.id === id);
    if (item) {
      item.qty++;
    } else {
      cart.push({ id, name, price, qty: 1 });
    }
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
  }

  function changeQty(id, delta) {
    let item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    renderCart();
  }

  function renderCart() {
    const list       = document.getElementById('cart-body');
    const totalEl    = document.getElementById('cart-total');
    const itemsInput = document.getElementById('cart-items');
    list.innerHTML   = '';
    let total = 0;

    if (cart.length === 0) {
      list.innerHTML = `
        <li class="list-group-item text-center text-muted">
          Belum ada item
        </li>`;
      document.getElementById('btn-checkout').disabled = true;
    } else {
      cart.forEach(i => {
        const sub = i.price * i.qty;
        total += sub;
        list.insertAdjacentHTML('beforeend', `
          <li class="list-group-item">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <strong>${i.name}</strong><br>
                <small>Harga: Rp${i.price.toLocaleString()}</small><br>
                <small>Qty: ${i.qty}</small><br>
                <small>Subtotal: Rp${sub.toLocaleString()}</small>
              </div>
              <div class="btn-group btn-group-sm">
                <button class="btn btn-outline-secondary me-2"
                        onclick="changeQty(${i.id},-1)">–</button>
                <button class="btn btn-outline-secondary me-2"
                        onclick="changeQty(${i.id},1)">+</button>
                <button class="btn btn-danger"
                        onclick="removeFromCart(${i.id})">×</button>
              </div>
            </div>
          </li>`);
      });
      document.getElementById('btn-checkout').disabled = false;
    }

    totalEl.textContent = total.toLocaleString();
    itemsInput.value   = JSON.stringify(cart);
    calculateChange();
  }

  function calculateChange() {
    const paid  = parseInt(document.getElementById('paid').value) || 0;
    const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
    document.getElementById('change').textContent =
      Math.max(0, paid - total).toLocaleString();
  }

  // Validasi sebelum submit
  document.getElementById('checkout-form')
    .addEventListener('submit', function(e){
      const paid  = parseInt(document.getElementById('paid').value) || 0;
      const total = cart.reduce((s,i) => s + i.price * i.qty, 0);
      if (paid < total) {
        e.preventDefault();
        new bootstrap.Modal(
          document.getElementById('errorModal')
        ).show();
      }
    });

  // Event bayar input
  document.getElementById('paid')
    .addEventListener('input', calculateChange);

  // Initial render
  renderCart();

  // Jika ada session success, tampilkan modal sukses
  document.addEventListener('DOMContentLoaded', ()=>{
        new bootstrap.Modal(document.getElementById('successModal')).show();
      });

</script>
@endpush
