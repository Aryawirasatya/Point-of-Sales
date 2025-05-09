{{-- resources/views/categories/index.blade.php --}}
@extends('layout.app')

@section('content')
<div class="container py-5">
  {{-- Header dengan tombol Tambah --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">
      <i class="bi bi-tags-fill text-primary me-2"></i>
      Kategori
    </h2>
    <button 
      class="btn btn-primary rounded-pill px-4 py-2 shadow-sm"
      onclick="showModal(event, this)" 
      data-href="{{ route('categories.create') }}"
    >
      <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
    </button>
  </div>

  {{-- Alert sukses --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Tabel dalam Card --}}
  <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
          <tr>
            <th class="py-3 ps-4">No</th>
            <th class="py-3">Nama Kategori</th>
            <th class="py-3">Status</th>
            <th class="py-3 pe-4 text-end">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $i => $cat)
          <tr>
            <td class="ps-4">{{ $i + 1 + ($categories->currentPage()-1)*$categories->perPage() }}</td>
            <td>{{ $cat->name }}</td>
            <td>
              <span class="badge {{ $cat->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                {{ ucfirst($cat->status) }}
              </span>
            </td>
            <td class="pe-4 text-end">
              {{-- EDIT --}}
              <button
                type="button"
                class="btn btn-sm btn-outline-warning rounded-pill px-3 me-2"
                onclick="showModal(event, this)"
                data-href="{{ route('categories.edit', $cat->id) }}"
              >
                Edit
              </button>
              {{-- DELETE --}}
              <form 
                action="{{ route('categories.destroy', $cat->id) }}" 
                method="POST" 
                class="d-inline form-delete-user"
              >
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                  Hapus
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="card-footer bg-white border-0">
        <nav class="d-flex justify-content-end mb-0 gap-20" aria-label="Pagination">
            {{ $categories->links('pagination::bootstrap-5') }}
        </nav>
</div>

  </div>
</div>

{{-- Modal Form Kategori --}}
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title">Form Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="categoryModalBody">
        <div class="text-center py-5">Memuat...</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function showModal(e, btn) {
  e.preventDefault();
  const url = btn.getAttribute('data-href');
  const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
  const body  = document.getElementById('categoryModalBody');

  body.innerHTML = '<div class="text-center py-5">Memuat...</div>';

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.text())
    .then(html => {
      body.innerHTML = html;
      modal.show();
    })
    .catch(() => {
      body.innerHTML = '<div class="text-danger text-center">Gagal memuat data.</div>';
    });
}

document.addEventListener('DOMContentLoaded', function() {
  // pilih semua form hapus
  document.querySelectorAll('.form-delete-user').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault(); // cegah submit langsung

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
          // kalau user tekan Ya, submit form
          form.submit();
        }
      });
    });
  });
});
</script>
@endpush

{{-- Optional CSS overrides --}}
<style>
.table-hover tbody tr:hover {
  background-color: #f8f9fa;
}
.pagination {
  margin: 0;
  gap: .30rem;
}
</style>
