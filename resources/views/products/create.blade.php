
@extends(request()->ajax() ? 'layout.blank' : 'layout.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-semibold">➕ Tambah Produk</h2>

    {{-- Alert Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Ups!</strong> Ada beberapa masalah dengan input kamu:
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Produk --}}
    <div class="card shadow-sm border-0 rounded-4 p-4">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nama Produk</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <!-- <div class="mb-3">
                <label for="barcode" class="form-label">Barcode (opsional)</label>
                <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
            </div> -->

            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="stock_quantity" class="form-label">Stok</label>
                    <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="image" class="form-label">Gambar Produk (opsional)</label>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">← Batal</a>
                <button type="submit" class="btn btn-success px-4">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
