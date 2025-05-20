{{-- resources/views/cashier/transactions/create.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container py-4">

  {{-- Alert Error --}}
  @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      {{ $errors->first() }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <h2 class="mb-4"><i class="fas fa-cash-register text-primary me-2"></i>Buat Transaksi</h2>

  <div class="row gy-4">
    {{-- Daftar Produk --}}
    <div class="col-lg-8">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white d-flex align-items-center">
          <i class="fas fa-boxes-stacked text-secondary me-2"></i>
          <h5 class="mb-0 me-auto">Pilih Produk</h5>
          <input id="search" type="text" class="form-control form-control-sm w-50" placeholder="🔍 Cari produk...">
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table id="products-table" class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th>Nama</th>
                  <th>Kategori</th>
                  <th class="w-25">Deskripsi</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($products as $p)
                <tr class="product-row">
                  <td>{{ $p->name }}</td>
                  <td>{{ $p->category->name }}</td>
                  <td class="text-truncate">{{ Str::limit($p->description, 50) }}</td>
                  <td>Rp{{ number_format($p->price,0,',','.') }}</td>
                  <td>{{ $p->stock_quantity }}</td>
                  <td class="text-center">
                    <button
                      class="btn btn-sm btn-primary"
                      data-id="{{ $p->id }}"
                      data-name="{{ $p->name }}"
                      data-price="{{ $p->price }}"
                      @if($p->stock_quantity === 0) disabled @endif
                      onclick="addFromBtn(this)"
                    >
                      <i class="fas fa-cart-plus"></i>
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

    {{-- Keranjang & Pembayaran --}}
    <div class="col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-header bg-white d-flex align-items-center">
          <i class="fas fa-shopping-cart text-secondary me-2"></i>
          <h5 class="mb-0">Keranjang</h5>
        </div>
        <div class="card-body d-flex flex-column">

          <form id="checkout-form" method="POST" action="{{ route('cashier.transactions.store') }}">
            @csrf
            <input type="hidden" name="items" id="cart-items">

            <ul id="cart-body" class="list-group flex-grow-1 overflow-auto mb-3" style="max-height: 300px;">
              <li class="list-group-item text-center text-muted">Keranjang kosong</li>
            </ul>

            <div class="d-flex justify-content-between mb-3">
              <strong>Total:</strong>
              <span>Rp <span id="cart-total">0</span></span>
            </div>

            <div class="mb-3">
              <label for="paid" class="form-label">Bayar (Rp)</label>
              <input type="number" id="paid" name="paid"
                     class="form-control form-control-sm" min="0" required>
            </div>

            <div class="d-flex justify-content-between mb-4">
              <strong>Kembalian:</strong>
              <span>Rp <span id="change">0</span></span>
            </div>

            <button id="btn-checkout" type="submit" class="btn btn-success w-100" disabled>
              <i class="fas fa-check-circle me-1"></i>Checkout
            </button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Sukses --}}
@if(session('success'))
<div class="modal fade" id="successModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-sm">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-check"></i> Transaksi Berhasil</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Invoice: <strong>{{ session('invoice_id') }}</strong>
      </div>
      <div class="modal-footer">
        <a href="{{ route('cashier.transactions.create') }}" class="btn btn-outline-secondary">Transaksi Baru</a>
        <a href="{{ route('cashier.transactions.show', session('invoice_id')) }}" class="btn btn-primary">Cetak Invoice</a>
      </div>
    </div>
  </div>
</div>
@endif

{{-- Modal: Error --}}
<div class="modal fade" id="errorModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow-sm">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Pembayaran Kurang</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Jumlah bayar kurang dari total. Mohon periksa kembali.
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Pencarian produk
  document.getElementById('search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('#products-table tbody tr').forEach(tr => {
      tr.hidden = !tr.cells[0].textContent.toLowerCase().includes(q);
    });
  });

  // Keranjang data
  let cart = [];

  function addFromBtn(btn) {
    const id = +btn.dataset.id,
          name = btn.dataset.name,
          price = +btn.dataset.price;
    const item = cart.find(i => i.id === id);
    if (item) item.qty++;
    else cart.push({ id, name, price, qty: 1 });
    renderCart();
  }

  function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
  }

  function renderCart() {
    const list = document.getElementById('cart-body'),
          totalEl = document.getElementById('cart-total'),
          itemsInput = document.getElementById('cart-items');
    list.innerHTML = '';
    let total = 0;

    if (!cart.length) {
      list.innerHTML = `<li class="list-group-item text-center text-muted">Keranjang kosong</li>`;
      document.getElementById('btn-checkout').disabled = true;
    } else {
      cart.forEach(i => {
        const sub = i.price * i.qty;
        total += sub;
        list.insertAdjacentHTML('beforeend', `
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <strong>${i.name}</strong><br>
              <small>Rp${i.price.toLocaleString()}</small> × 
              <span class="badge bg-secondary">${i.qty}</span> = 
              <strong>Rp${sub.toLocaleString()}</strong>
            </div>
            <div class="btn-group btn-group-sm">
              <button class="btn btn-outline-secondary" onclick="changeQty(${i.id},-1)">−</button>
              <button class="btn btn-outline-secondary" onclick="changeQty(${i.id},1)">＋</button>
              <button class="btn btn-outline-danger" onclick="removeFromCart(${i.id})">×</button>
            </div>
          </li>`);
      });
      document.getElementById('btn-checkout').disabled = false;
    }

    totalEl.textContent = total.toLocaleString();
    itemsInput.value = JSON.stringify(cart);
    calculateChange();
  }

  function calculateChange() {
    const paid = +document.getElementById('paid').value || 0,
          total = cart.reduce((s,i)=> s + i.price * i.qty, 0);
    document.getElementById('change').textContent = Math.max(0, paid - total).toLocaleString();
  }

  document.getElementById('paid').addEventListener('input', calculateChange);

  document.getElementById('checkout-form').addEventListener('submit', e => {
    const paid = +document.getElementById('paid').value || 0,
          total = cart.reduce((s,i)=> s + i.price * i.qty, 0);
    if (paid < total) {
      e.preventDefault();
      new bootstrap.Modal(document.getElementById('errorModal')).show();
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    @if(session('success'))
      new bootstrap.Modal(document.getElementById('successModal')).show();
    @endif
  });
</script>

<style>
  .product-row:hover {
    background-color: rgba(0,0,0,0.05);
  }
  #cart-body {
    scrollbar-width: thin;
  }
  #cart-body::-webkit-scrollbar {
    width: 6px;
  }
  #cart-body::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
  }
</style>
@endpush
