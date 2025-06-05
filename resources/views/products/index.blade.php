@extends('layout.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-semibold">📦 Daftar Produk</h2>
    
    {{-- Tombol tambah dan pencarian --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-2">
        <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill shadow-sm px-4 py-2" onclick="showModal(event, this)">
            + Tambah Produk
        </a>
        <div class="flex-grow-1" style="max-width: 300px;">
            <input id="search" type="search" class="form-control form-control-dark rounded-pill px-3 py-2 placeholder-white " placeholder="🔍 Cari produk...">
        </div>
    </div>

    {{-- Alert sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Daftar Produk --}}
    <div class="row" id="product-list">
        @forelse ($products as $product)
        <div class="col-6 col-md-4 col-lg-3 mb-4 product-card" data-name="{{ strtolower($product->name) }}">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden h-80 transition-card dark-card">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" style="height: 250px; object-fit: cover;">
                @else
                <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-muted" style="height: 250px;">
                    <span>Tidak ada gambar</span>
                </div>
                @endif

                <div class="card-body d-flex flex-column" style="background-color: #31363F;">
                    <h5 class="card-title mb-1">{{ $product->name }}</h5>
                    <small class="text-light d-block mb-1">Barcode: {{ $product->barcode ?? '-' }}</small>
                    <small class="text-light d-block mb-1">Kategori: {{ $product->category->name }}</small>
                    <small class="text-light d-block mb-1">Harga: Rp{{ number_format($product->price, 0, ',', '.') }}</small>
                    <small class="text-light d-block mb-2">Stok: {{ $product->stock_quantity }}</small>
                    <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                        {{ Str::limit($product->description, 100) ?? 'Tidak ada deskripsi.' }}
                    </p>
                    <a href="#" class="text-info small" onclick="showDescriptionModal(event, '{{ addslashes($product->name) }}', `{{ addslashes(strip_tags($product->description)) }}`)">Lihat Deskripsi</a>
                </div>

                <div class="card-footer d-flex justify-content-between px-3 pb-3 dark-footer" style="background-color:#222831 ;">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-light rounded-pill px-3" onclick="showModal(event, this)">Edit</a>

                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="form-delete-user">
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

{{-- Modal Produk --}}
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

{{-- Modal Deskripsi --}}
<div class="modal fade" id="descModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
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

<style>
    body {
        background-color: #1c1c1c;
        color: #f0f0f0;
    }

    .container h2 {
        color: #f8f9fa;
    }

    .transition-card {
        transition: transform 0.3s ease;
    }

    .transition-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 24px rgba(255, 255, 255, 0.05);
    }

    .dark-card {
        background-color: #2b2b2b;
        color: #e0e0e0;
    }

    .dark-footer {
        background-color: #2b2b2b;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .btn-outline-light:hover {
        background-color: #f8f9fa;
        color: #000;
    }

    .modal-content {
        border-radius: 1rem;
        background-color: #2c2c2c;
        color: #f8f9fa;
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

    #search {
        background-color: #2b2b2b;
        border: 1px solid #555;
        color: #fff;
    }

    #search::placeholder {
    color: #ffffff !important;
    opacity: 0.8; /* opsional, bisa disesuaikan */
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

document.addEventListener('DOMContentLoaded', function () {
    // Delete konfirmasi
    document.querySelectorAll('.form-delete-user').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Live Search Produk
    const searchInput = document.getElementById('search');
    const productCards = document.querySelectorAll('.product-card');

    searchInput.addEventListener('input', function () {
        const keyword = this.value.trim().toLowerCase();

        productCards.forEach(card => {
            const name = card.getAttribute('data-name');
            card.style.display = name.includes(keyword) ? 'block' : 'none';
        });
    });
});
</script>
@endsection
