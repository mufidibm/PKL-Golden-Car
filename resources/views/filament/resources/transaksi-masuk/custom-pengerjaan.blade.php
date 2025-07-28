<!-- Load Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

@php
    $Pembayaran = $transaksi->pembayaran;
@endphp

@foreach ($pengerjaanList as $pengerjaan)
    {{-- SPAREPART DIPAKAI --}}
    @if ($pengerjaan->spareparts->count())
        <div class="mt-4">
            <h3 class="text-lg font-semibold mb-2">Sparepart Dipakai</h3>
            <table class="w-full text-left border border-gray-300 rounded-md overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-b">Sparepart</th>
                        <th class="p-3 border-b text-center">Qty</th>
                        <th class="p-3 border-b text-right">Harga</th>
                        <th class="p-3 border-b text-right">Subtotal</th>
                        <th class="p-3 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalSparepart = 0; @endphp
                    @foreach ($pengerjaan->spareparts as $sparepart)
                        @php $totalSparepart += $sparepart->subtotal; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">{{ $sparepart->barang->nama_barang ?? '-' }}</td>
                            <td class="p-3 border-b text-center">{{ $sparepart->qty }}</td>
                            <td class="p-3 border-b text-right">IDR {{ number_format($sparepart->harga, 0, ',', '.') }}</td>
                            <td class="p-3 border-b text-right">IDR {{ number_format($sparepart->subtotal, 0, ',', '.') }}</td>
                            <td class="p-3 border-b text-center">
                                <form method="POST" action="{{ route('sparepart.delete', $sparepart->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-gray-100">
                        <td colspan="3" class="p-3 text-right">Total Biaya Sparepart</td>
                        <td class="p-3 text-right">IDR {{ number_format($totalSparepart, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    {{-- JASA DIPAKAI --}}
    @if ($pengerjaan->jasas->count())
        <div class="mt-5">
            <h3 class="text-lg font-semibold mb-2">Jasa Digunakan</h3>
            <table class="w-full text-left border border-gray-300 rounded-md overflow-hidden">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 border-b">Jasa</th>
                        <th class="p-3 border-b text-right">Harga</th>
                        <th class="p-3 border-b text-right">Subtotal</th>
                        <th class="p-3 border-b text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalJasa = 0; @endphp
                    @foreach ($pengerjaan->jasas as $jasa)
                        @php $totalJasa += $jasa->subtotal; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">{{ $jasa->jasa->nama_jasa ?? '-' }}</td>
                            <td class="p-3 border-b text-right">IDR {{ number_format($jasa->harga, 0, ',', '.') }}</td>
                            <td class="p-3 border-b text-right">IDR {{ number_format($jasa->subtotal, 0, ',', '.') }}</td>
                            <td class="p-3 border-b text-center">
                                <form method="POST" action="{{ route('jasa.delete', $jasa->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus jasa ini?')">
                                        🗑 Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="font-bold bg-gray-100">
                        <td colspan="2" class="p-3 text-right">Total Biaya Jasa</td>
                        <td class="p-3 text-right">IDR {{ number_format($totalJasa, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    {{-- TOMBOL PEMBAYARAN --}}
    <div class="text-right mt-6">
        @if ($Pembayaran)
            <a href="{{ route('invoice.cetak', $Pembayaran->id) }}" target="_blank" class="btn btn-secondary">
                🧾 Cetak Nota
            </a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBatalBayar">
                ❌ Batal Bayar
            </button>
        @else
            <form action="{{ route('pembayaran.dari-transaksi', $transaksi->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full btn btn-warning">💰 Proses Pembayaran</button>
            </form>
        @endif
    </div>
@endforeach

{{-- MODAL KONFIRMASI --}}
@if ($Pembayaran)
    <div class="modal fade" id="modalBatalBayar" tabindex="-1" aria-labelledby="modalBatalBayarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pembayaran.destroy', $Pembayaran->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBatalBayarLabel">Konfirmasi Pembatalan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin <strong>membatalkan pembayaran</strong> ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

<!-- Load Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
