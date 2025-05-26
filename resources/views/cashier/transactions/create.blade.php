{{-- resources/views/cashier/transactions/create.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container-fluid bg-dark min-vh-100 py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      {{-- Alert Error --}}
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
          {{ $errors->first() }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      {{-- Header & Search --}}
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
        <h2 class="text-light"><i class="fas fa-cash-register text-gradient me-2"></i>Buat Transaksi</h2>
      </div>
      
      <div class="row g-4">
        <input id="search" type="search" class="form-control form-control-dark mt-3 mt-md-0 w-100 w-md-50" placeholder="Cari produk...">
        {{-- Produk dalam Kotak Tanpa Gambar, akan dimunculkan di modal --}}
        <div class="col-md-8">
          <div class="row g-3">
            @foreach($products as $p)
            <div class="col-6 col-lg-4">
              <div class="card bg-secondary border-0 rounded-4 transition-card h-100">
                <div class="card-body d-flex flex-column">
                  <h5 class="card-title text-light">{{ $p->name }}</h5>
                  <small class="text-muted mb-2">Kategori: {{ $p->category->name }}</small>
                  <span class="fw-bold text-light mb-2">Rp{{ number_format($p->price,0,',','.') }}</span>
                  <small class="text-light mb-2">Stok: {{ $p->stock_quantity }}</small>
                  <a href="#" class="text-info small mb-3" 
                     onclick="showDescriptionModal(event, '{{ addslashes($p->name) }}', `{!! addslashes(strip_tags($p->description)) !!}`, '{{ $p->image ? asset('storage/' . $p->image) : '' }}')">
                    Lihat Deskripsi
                  </a>
                  <div class="mt-auto d-flex justify-content-end">
                    <button class="btn btn-sm btn-gradient" 
                            data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->price }}" 
                            @if($p->stock_quantity === 0) disabled @endif 
                            onclick="addFromBtn(this)">
                      <i class="fas fa-cart-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div> <!-- .row g-3 -->
        </div> <!-- .col-md-8 -->

        {{-- Keranjang --}}
        <div class="col-md-4">
          <div class="card bg-secondary border-0 shadow-sm position-sticky" style="top: 100px;">
            <div class="card-header bg-gradient text-light border-0">
              <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
            </div>
            <div class="card-body d-flex flex-column">
              <form id="checkout-form" method="POST" action="{{ route('cashier.transactions.store') }}">
                @csrf
                <input type="hidden" name="items" id="cart-items">

                <div id="cart-body" class="flex-grow-1 overflow-auto custom-scroll mb-3">
                  <p class="text-center text-muted">Keranjang kosong</p>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span class="text-light">Total</span>
                  <span class="fw-bold text-light">Rp <span id="cart-total">0</span></span>
                </div>

                <div class="mb-3">
                  <label for="paid" class="form-label text-light">Bayar (Rp)</label>
                  <input type="number" id="paid" name="paid" class="form-control form-control-dark" min="0" required>
                </div>

                <div class="d-flex justify-content-between mb-4">
                  <span class="text-light">Kembalian</span>
                  <span class="fw-bold text-light">Rp <span id="change">0</span></span>
                </div>

                <button id="btn-checkout" type="submit" class="btn btn-gradient w-100" disabled>
                  <i class="fas fa-check-circle me-1"></i>Checkout
                </button>
              </form>
            </div>
          </div>
        </div>
      </div> <!-- .row g-4 -->

      {{-- Modal Deskripsi dengan Gambar --}}
      <div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content rounded-4 shadow bg-secondary">
            <div class="modal-header border-0">
              <h5 class="modal-title text-light" id="descModalTitle">Deskripsi Produk</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-light" id="descModalBody">
              <div id="descImageContainer" class="mb-3 text-center"></div>
              <div id="descText" class="text-muted">Memuat...</div>
            </div>
          </div>
        </div>
      </div>

    </div> <!-- .col-lg-10 -->
  </div> <!-- .row -->
</div> <!-- .container-fluid -->
@endsection

@push('styles')
<style>
:root {
  --bg-dark: #1c1c1c;
  --bg-secondary: #2b2b3d;
  --gradient-start: #4e54c8;
  --gradient-end: #8f94fb;
}
body { background-color: var(--bg-dark); color: #f0f0f0; }
.text-gradient { background: linear-gradient(45deg,var(--gradient-start),var(--gradient-end)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.btn-gradient { background: linear-gradient(45deg,var(--gradient-start),var(--gradient-end)); border:none; color:#fff; }
.form-control-dark { background-color: var(--bg-secondary); color: #ddd; border:1px solid #444; }
.custom-scroll { max-height:250px; scrollbar-width:thin; }
.custom-scroll::-webkit-scrollbar { width:6px; }
.custom-scroll::-webkit-scrollbar-thumb { background-color:#555; border-radius:3px; }
.transition-card { transition: transform .3s ease, box-shadow .3s ease; }
.transition-card:hover { transform: translateY(-5px); box-shadow:0 10px 24px rgba(0,0,0,.5); }
@media(max-width:767px) { #search{width:100%!important;margin-bottom:1rem;} }
</style>
@endpush

@push('scripts')
<script>
  document.getElementById('search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.card.bg-secondary').forEach(card => {
      const name = card.querySelector('.card-title').textContent.toLowerCase();
      card.closest('.col-6').hidden = !name.includes(q);
    });
  });

  let cart = [];
  function addFromBtn(btn) {
    const id = +btn.dataset.id, name = btn.dataset.name, price = +btn.dataset.price;
    const item = cart.find(i => i.id === id);
    item ? item.qty++ : cart.push({ id, name, price, qty: 1 });
    renderCart();
  }
  function changeQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    renderCart();
  }
  function removeFromCart(id) { cart = cart.filter(i => i.id !== id); renderCart(); }
  function renderCart() {
    const body = document.getElementById('cart-body'), totalEl = document.getElementById('cart-total'), itemsEl = document.getElementById('cart-items');
    body.innerHTML = '';
    let total = 0;
    if (!cart.length) {
      body.innerHTML = '<p class="text-center text-muted">Keranjang kosong</p>';
      document.getElementById('btn-checkout').disabled = true;
    } else {
      cart.forEach(i => {
        const sub = i.price * i.qty;
        total += sub;
        body.innerHTML += `
          <div class="d-flex justify-content-between align-items-center mb-2 text-light">
            <div><strong>${i.name}</strong><br><small>Rp${i.price.toLocaleString()} x ${i.qty}</small></div>
            <div>
              <span>Rp${sub.toLocaleString()}</span>
              <div class="btn-group btn-group-sm ms-3">
                <button class="btn btn-outline-light" onclick="changeQty(${i.id},-1)">−</button>
                <button class="btn btn-outline-light" onclick="changeQty(${i.id},1)">＋</button>
                <button class="btn btn-outline-danger" onclick="removeFromCart(${i.id})">×</button>
              </div>
            </div>
          </div>`;
      });
      document.getElementById('btn-checkout').disabled = false;
    }
    totalEl.textContent = total.toLocaleString();
    itemsEl.value = JSON.stringify(cart);
    calcChange();
  }
  function calcChange() {
    const paid = +document.getElementById('paid').value || 0;
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('change').textContent = Math.max(0, paid - total).toLocaleString();
  }
  document.getElementById('paid').addEventListener('input', calcChange);
  document.getElementById('checkout-form').addEventListener('submit', e => {
    const paid = +document.getElementById('paid').value || 0;
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    if (paid < total) {
      e.preventDefault();
      new bootstrap.Modal(document.getElementById('errorModal')).show();
    }
  });
  function showDescriptionModal(event, title, description, imageUrl) {
    event.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById('descModal'));    
    document.getElementById('descModalTitle').innerText = title;
    const imgContainer = document.getElementById('descImageContainer');
    if (imageUrl) {
      imgContainer.innerHTML = `<img src="${imageUrl}" class="img-fluid rounded mb-3">`;
    } else {
      imgContainer.innerHTML = '';
    }
    document.getElementById('descText').innerHTML = description || '<em class="text-muted">Tidak ada deskripsi.</em>';
    modal.show();
  }
  document.addEventListener('DOMContentLoaded', () => { renderCart(); @if(session('success')) new bootstrap.Modal(document.getElementById('successModal')).show(); @endif });
</script>
@endpush
