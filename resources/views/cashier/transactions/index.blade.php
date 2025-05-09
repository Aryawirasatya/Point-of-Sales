@extends('layout.app')

@section('content')
<div class="container py-4">
  <h2 class="mb-4">Riwayat Transaksi</h2>

  <div class="card">
    <div class="card-body p-0">
      <table class="table mb-0">
        <thead class="table-light">
          <tr>
            <th>Invoice</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($sales as $sale)
          <tr>
            <td>{{ $sale->invoice_number }}</td>
            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
            <td>Rp{{ number_format($sale->total_amount,0,',','.') }}</td>
            <td>{{ ucfirst($sale->payment_status) }}</td>
            <td>
              <a href="{{ route('cashier.transactions.show', $sale->id) }}"
                 class="btn btn-sm btn-primary">
                detail
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted">
              Belum ada transaksi
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    {{ $sales->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
