@php
    $setting = \App\Models\Setting::first();

@endphp
@if ($setting)
    <div style="display: flex; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #333; padding-bottom: 16px;">

        <div>
            <div style="font-size: 1.5em; font-weight: bold;">{{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}</div>
            <div style="font-size: 1em;">{{ $setting->alamat ?? '-' }}</div>
            <div style="font-size: 1em;">Telp: {{ $setting->telepon ?? '-' }}</div>
        </div>
    </div>
@endif
<h2>Laporan Pembayaran</h2>
<p><strong>Periode:</strong> {{ isset($tanggalMulai) && isset($tanggalSelesai) ? date('d-m-Y', strtotime($tanggalMulai)) . ' s/d ' . date('d-m-Y', strtotime($tanggalSelesai)) : '-' }}</p>
<p><strong>Jumlah Transaksi:</strong> {{ $jumlahTransaksi }}</p>
<table border="1" cellspacing="0" cellpadding="4" width="100%">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>No Polisi</th>
            <th>Metode</th>
            <th>Total Bayar</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ $row->transaksiMasuk->kendaraan->customer->nama ?? '-' }}</td>
                <td>{{ $row->transaksiMasuk->kendaraan->no_polisi ?? '-' }}</td>
                <td>{{ $row->metodePembayaran->nama_metode ?? '-' }}</td>
                <td>{{ $row->total_bayar }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<p><strong>Total Pembayaran:</strong> Rp {{ number_format($totalPembayaran) }}</p> 