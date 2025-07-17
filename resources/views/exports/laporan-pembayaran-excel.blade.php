@php
    $setting = \App\Models\Setting::first();
@endphp

@if ($setting)
    <table>
        <tr>
            <td colspan="5" style="font-size: 16px; font-weight: bold;">{{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}</td>
        </tr>
        <tr>
            <td colspan="5">{{ $setting->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="5">Telp: {{ $setting->telepon ?? '-' }}</td>
        </tr>
    </table>
    <br>
@endif

<table>
    <tr><td colspan="5" style="font-size: 18px; font-weight: bold;">Laporan Pembayaran</td></tr>
    <tr>
        <td><strong>Periode:</strong></td>
        <td>{{ isset($tanggalMulai) && isset($tanggalSelesai) ? date('d-m-Y', strtotime($tanggalMulai)) . ' s/d ' . date('d-m-Y', strtotime($tanggalSelesai)) : '-' }}</td>
    </tr>
    <tr>
        <td><strong>Jumlah Transaksi:</strong></td>
        <td>{{ $jumlahTransaksi }}</td>
    </tr>
</table>

<br>

<table border="1">
    <thead>
        <tr>
            <th style="background: #f0f0f0;">Tanggal</th>
            <th style="background: #f0f0f0;">Customer</th>
            <th style="background: #f0f0f0;">No Polisi</th>
            <th style="background: #f0f0f0;">Metode</th>
            <th style="background: #f0f0f0;">Total Bayar</th>
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

<br>

<table>
    <tr>
        <td><strong>Total Pembayaran:</strong></td>
        <td>Rp {{ number_format($totalPembayaran) }}</td>
    </tr>
</table>
