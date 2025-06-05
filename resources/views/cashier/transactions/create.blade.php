<!-- resources/views/cashier/transactions/create.blade.php -->
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

      {{-- Header --}}
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
        <h2 class="text-light">
          <i class="fas fa-cash-register text-gradient me-2"></i>Buat Transaksi
        </h2>
      </div>
      
      <div class="row g-4">
        <div class="w-100 w-md-50 mt-3 mt-md-0">
          <input id="search" type="search" class="form-control form-control-dark" placeholder="Cari produk...">
        </div>
        {{-- Produk --}}
        <div class="col-md-8">
          <div class="row g-3" id="product-list">
            @foreach($products as $p)
              <div class="col-6 col-lg-4 product-item">
                <div class="card custom-card border-0 rounded-4 transition-card h-100">
                  <div class="card-body d-flex flex-column">
                    <h5 class="card-title text-light">{{ $p->name }}</h5>
                    <small class="text-light mb-2">Kategori: {{ $p->category->name }}</small>
                    <span class="fw-bold text-light mb-2">Rp{{ number_format($p->price,0,',','.') }}</span>
                    <small class="text-light mb-2">Stok: {{ $p->stock_quantity }}</small>
                    <a href="#" class="text-info small mb-3"
                       onclick="showDescriptionModal(event, '{{ addslashes($p->name) }}', `{!! addslashes(strip_tags($p->description)) !!}`, '{{ $p->image ? asset('storage/' . $p->image) : '' }}')">
                      Lihat Deskripsi
                    </a>
                    <div class="mt-auto d-flex justify-content-center ">
                      <button class="btn btn-sm btn-gradient width-100"
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
          </div>
        </div>

        {{-- Keranjang --}}
        <div class="col-md-4">
          <div class="card border-0 shadow-sm position-sticky" style="top:100px; background-color:#31363F;">
            <div class="card-header text-light border-0">
              <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Keranjang</h5>
            </div>
            <div class="card-body d-flex flex-column">
              <form id="checkout-form" method="POST" action="{{ route('cashier.transactions.store') }}">
                @csrf
                <input type="hidden" name="items" id="cart-items">
                <!-- Hidden raw paid value for backend -->
                <input type="hidden" name="paid" id="paid_raw" value="0">

                <div id="cart-body" class="flex-grow-1 overflow-auto custom-scroll mb-3">
                  <p class="text-center text-light">Keranjang kosong</p>
                </div>

                <div class="d-flex justify-content-between mb-2">
                  <span class="text-light">Total</span>
                  <span class="fw-bold text-light">Rp <span id="cart-total">0</span></span>
                </div>

                <div class="mb-3">
                  <label for="paid_disp" class="form-label text-light">Bayar (Rp)</label>
                  <input
                    type="text"
                    id="paid_disp"
                    class="form-control form-control-dark"
                    inputmode="numeric"
                    required
                  >
                </div>

                <div class="d-flex justify-content-between mb-4">
                  <span class="text-light">Kembalian</span>
                  <span class="fw-bold text-light">Rp <span id="change">0</span></span>
                </div>

                <button id="btn-checkout" type="submit" class="btn btn-cart w-100" disabled>
                  <i class="fas fa-check-circle me-1"></i>Checkout
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>

      {{-- Modal Deskripsi --}}
      <div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
          <div class="modal-content p-4 rounded-4 shadow bg-dark">
            <div class="modal-header border-0">
              <h5 class="modal-title text-light fs-4" id="descModalTitle">Deskripsi Produk</h5>
              <button type="button" class="btn-close btn-close-white border border-light" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-light">
              <div id="descImageContainer" class="mb-3 text-center"></div>
              <div id="descText" class="text-light fst-italic">Memuat...</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
:root {
  --bg-dark: #1c1c1c;
  --bg-secondary: #2b2b3d;
  --gradient-start: #4e54c8;
  --gradient-end:rgba(53, 107, 255, 0.4);
  --gradient-g: rgb(44, 46, 87);
}

.custom-card {
  background: linear-gradient(145deg ,rgb(47, 49, 58),rgb(25, 38, 60));
  color: #fff;
}

body {
  background-color: var(--bg-dark);
  color: #fff;
}

.btn-gradient {
  background: linear-gradient(45deg, var(--gradient-g), var(--gradient-g));
  border: 2px solid;
  width: 100%;
  color: #fff;
}

.btn-cart {
  background-color:  rgb(13, 65, 114);
  border: 2px solid;
  width: 100%;
  color: #fff;
}

.custom-scroll {
  max-height: 250px;
  scrollbar-width: thin;
}

.custom-scroll::-webkit-scrollbar {
  width: 6px;
}

.custom-scroll::-webkit-scrollbar-thumb {
  background-color: #555;
  border-radius: 3px;
}

.transition-card {
  transition: transform .3s ease, box-shadow .3s ease;
}

/* Hilangkan efek hover pada .btn-gradient dan .btn-cart */
/* Tombol “Add” produk */
.btn-gradient:hover {
  /* Kembalikan gradien asli */
  background: linear-gradient(45deg, var(--gradient-g), var(--gradient-g)) !important;
  /* Pastikan teks tetap putih */
  color: #fff !important;
  /* Hilangkan bayangan/transisi lain jika ada */
  box-shadow: none !important;
  transform: none !important;
}

/* Tombol “Checkout” */
.btn-cart:hover {
  /* Kembalikan warna ungu biru aslinya */
  background-color:rgb(78, 119, 200) !important;
  /* Pastikan teks tetap putih */
  color: #fff !important;
  box-shadow: none !important;
  transform: none !important;
}


@media(max-width:767px) {
  #search {
    width: 100% !important;
    margin-bottom: 1rem;
  }
}
</style>
@endpush

@push('scripts')
<script>
  // Ambil elemen penting
  const searchInput = document.getElementById('search');
  const paidDispInput = document.getElementById('paid_disp');
  const paidRawInput = document.getElementById('paid_raw');
  let cart = [];

  // Filter produk berdasarkan pencarian
  searchInput.addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
      const name = item.querySelector('.card-title').textContent.toLowerCase();
      item.hidden = !name.includes(q);
    });
  });

  // Format dan validasi input Bayar
  paidDispInput.addEventListener('input', e => {
    let digits = e.target.value.replace(/\D/g, '');
    paidRawInput.value = digits || 0;
    let formatted = new Intl.NumberFormat('id-ID').format(digits);
    e.target.value = formatted ? `Rp ${formatted}` : '';
    calcChange();
  });

  // Tambah ke keranjang
  function addFromBtn(btn) {
    const id = +btn.dataset.id;
    const name = btn.dataset.name;
    const price = +btn.dataset.price;
    const item = cart.find(i => i.id === id);
    if (item) item.qty++;
    else cart.push({ id, name, price, qty: 1 });
    renderCart();
  }

  // Ubah jumlah dan hapus
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

  // Render ulang keranjang
  function renderCart() {
    const body = document.getElementById('cart-body');
    const totalEl = document.getElementById('cart-total');
    const itemsEl = document.getElementById('cart-items');
    body.innerHTML = '';
    let total = 0;

    if (cart.length === 0) {
      body.innerHTML = '<p class="text-center text-light">Keranjang kosong</p>';
      document.getElementById('btn-checkout').disabled = true;
    } else {
      cart.forEach(i => {
        const sub = i.price * i.qty;
        total += sub;
        body.innerHTML += `
          <div class="d-flex justify-content-between align-items-center mb-2 text-light">
            <div>
              <strong>${i.name}</strong><br>
              <small>RP${i.price.toLocaleString('id-ID')} x ${i.qty}</small>
            </div>
            <div>
              <span>Rp${sub.toLocaleString('id-ID')}</span>
              <div class="btn-group btn-group-sm ms-3 gap-1">
                <button class="btn btn-outline-light" onclick="changeQty(${i.id}, -1)">−</button>
                <button class="btn btn-outline-light" onclick="changeQty(${i.id}, 1)">＋</button>
                <button class="btn btn-outline-danger" onclick="removeFromCart(${i.id})">×</button>
              </div>
            </div>
          </div>`;
      });
      document.getElementById('btn-checkout').disabled = false;
    }

    totalEl.textContent = total.toLocaleString('id-ID');
    itemsEl.value = JSON.stringify(cart);
    calcChange();
  }

  // Hitung kembalian
  function calcChange() {
    const paid = +paidRawInput.value || 0;
    const total = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    document.getElementById('change').textContent = Math.max(0, paid - total).toLocaleString('id-ID');
  }

  // Validasi checkout
  document.getElementById('checkout-form').addEventListener('submit', e => {
    const paid = +paidRawInput.value || 0;
    const total = cart.reduce((sum, i) => sum + i.price * i.qty, 0);
    if (paid < total) {
      e.preventDefault();
      new bootstrap.Modal(document.getElementById('errorModal')).show();
    }
  });

  // Modal deskripsi produk
  function showDescriptionModal(event, title, description, imageUrl) {
    event.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById('descModal'));
    document.getElementById('descModalTitle').innerText = title;
    document.getElementById('descImageContainer').innerHTML =
      imageUrl ? `<img src="${imageUrl}" class="img-fluid rounded mb-3">` : '';
    document.getElementById('descText').innerHTML = description || '<em class="text-muted">Tidak ada deskripsi.</em>';
    modal.show();
  }

  // Inisialisasi
  document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    @if(session('success'))
      new bootstrap.Modal(document.getElementById('successModal')).show();
    @endif
  });
</script>
@endpush
