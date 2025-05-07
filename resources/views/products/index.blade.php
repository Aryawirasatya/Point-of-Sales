@extends('layout.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-semibold">📦 Daftar Produk</h2>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-4 rounded-pill shadow-sm px-4 py-2" onclick="showModal(event, this)">+ Tambah Produk</a>

    {{-- Alert sukses --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

    {{-- Kartu Produk --}}
    <div class="row">
        @forelse ($products as $product)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-80 transition-card" style="transition: transform 0.3s ease; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 250px;">
                            <span>Tidak ada gambar</span>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $product->name }}</h5>
                        <small class="text-muted d-block mb-1">Barcode: {{ $product->barcode ?? '-' }}</small>
                        <small class="text-muted d-block mb-1">Kategori: {{ $product->category->name }}</small>
                        <small class="text-muted d-block mb-1">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                        <small class="text-muted d-block mb-2">Stok: {{ $product->stock_quantity }}</small>
                        <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                            {{ Str::limit($product->description, 100) ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <a href="#" class="text-primary small" onclick="showDescriptionModal(event, '{{ addslashes($product->name) }}', `{{ addslashes(strip_tags($product->description)) }}`)">Lihat Deskripsi</a>

                    </div>

                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between px-3 pb-3">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="showModal(event, this)">Edit</a>

                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">Belum ada produk.</p>
        @endforelse
    </div>
</div>

{{-- Modal --}}
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Form Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <div class="text-center py-5">Memuat...</div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Deskripsi Produk -->
<div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content roundefd-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="descModalTitle">Deskripsi Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="descModalBody">
                <div class="text-muted">Memuat...</div>
            </div>
        </div>
    </div>
</div>


{{-- Tambahan CSS --}}
<style>
    .transition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .btn-close:hover {
        opacity: 1;
    }

    .btn {
        transition: all 0.2s ease-in-out;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .card-title {
        font-weight: 600;
    }
</style>

<script>
function showModal(event, element) {
    event.preventDefault();
    const url = element.getAttribute('href');
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    const modalBody = document.getElementById('modalBody');

    modalBody.innerHTML = '<div class="text-center py-5">Memuat...</div>';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        modalBody.innerHTML = html;
        modal.show();
    })
    .catch(() => {
        modalBody.innerHTML = '<div class="text-danger text-center">Gagal memuat data.</div>';
    });
}

function showDescriptionModal(event, title, description) {
    event.preventDefault();
    const modal = new bootstrap.Modal(document.getElementById('descModal'));
    document.getElementById('descModalTitle').innerText = title;
    document.getElementById('descModalBody').innerHTML = description || '<em class="text-muted">Tidak ada deskripsi.</em>';
    modal.show();
}
</script>
@endsection
