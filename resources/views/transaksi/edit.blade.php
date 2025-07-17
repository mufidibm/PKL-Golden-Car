<hr>

<form action="{{ route('pembayaran.dari-transaksi', ['transaksi' => $transaksi->id]) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-cash-register"></i> Proses Pembayaran
    </button>
</form>
