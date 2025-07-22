<x-filament-panels::page>
    <div class="mb-6">
        <form method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" name="tanggalMulai" value="{{ request('tanggalMulai', $tanggalMulai) }}" class="border rounded px-2 py-1 w-full" />
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium">Tanggal Selesai</label>
                    <input type="date" name="tanggalSelesai" value="{{ request('tanggalSelesai', $tanggalSelesai) }}" class="border rounded px-2 py-1 w-full" />
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium">Customer</label>
                    <select name="customerId" class="border rounded px-2 py-1 w-full">
                        <option value="">Semua Customer</option>
                        @foreach($customers as $id => $nama)
                            <option value="{{ $id }}" {{ request('customerId', $customerId) == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium">Metode Pembayaran</label>
                    <select name="metodePembayaranId" class="border rounded px-2 py-1 w-full">
                        <option value="">Semua Metode</option>
                        @foreach($metodePembayaranList as $id => $nama)
                            <option value="{{ $id }}" {{ request('metodePembayaranId', $metodePembayaranId) == $id ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit"
                        style="background:#198754;color:#fff;font-weight:bold;padding:0.5rem 1.5rem;border-radius:0.375rem;border:none;min-width:90px;">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

<div class="flex gap-2 mb-4">
    <a href="{{ route('laporan-pembayaran.export-pdf', request()->query()) }}"
       style="background:#dc3545;color:#fff;font-weight:bold;padding:0.5rem 1.5rem;border-radius:0.375rem;border:none;min-width:90px;display:inline-block;text-align:center;text-decoration:none;"
       target="_blank">
        Export PDF
    </a>

    <a href="{{ route('laporan-pembayaran.export-excel', request()->query()) }}"
       style="background:#0d6efd;color:#fff;font-weight:bold;padding:0.5rem 1.5rem;border-radius:0.375rem;border:none;min-width:90px;display:inline-block;text-align:center;text-decoration:none;"
       target="_blank">
        Export Excel
    </a>
</div>


    {{-- Ringkasan --}}
    @php $ringkasan = $this->getRingkasanDanChart(); @endphp
    <div class="flex flex-wrap gap-8 mb-2">
        <div style="margin-right: 40px">
            <div class="text-sm text-gray-500">Total Pembayaran</div>
            <div class="text-2xl font-bold">Rp {{ number_format($ringkasan['totalPembayaran']) }}</div>
        </div>
        <div>
            <div class="text-sm text-gray-500">Jumlah Transaksi</div>
            <div class="text-2xl font-bold">{{ $ringkasan['jumlahTransaksi'] }}</div>
        </div>
    </div>

    <div class="overflow-x-auto mb-6">
        <table class="min-w-full w-full bg-white border border-gray-200 rounded-lg">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Customer</th>
                    <th class="px-4 py-2 text-left">No Polisi</th>
                    <th class="px-4 py-2 text-left">Metode</th>
                    <th class="px-4 py-2 text-left">Total Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->getDataPembayaran() as $row)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $row->created_at->format('d-m-Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $row->transaksiMasuk->kendaraan->customer->nama ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $row->transaksiMasuk->kendaraan->no_polisi ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $row->metodePembayaran->nama_metode ?? '-' }}</td>
                        <td class="px-4 py-2 text-right">Rp {{ number_format($row->total_bayar) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
