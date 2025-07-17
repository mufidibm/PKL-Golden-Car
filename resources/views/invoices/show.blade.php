<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $Pembayaran->id }}</title>
    <style>
        body { font-family: sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #eee; }
        .total { font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    @if ($setting)
        <div style="display: flex; align-items: center; margin-bottom: 24px; border-bottom: 2px solid #333; padding-bottom: 16px;">
            @if ($setting->getFirstMediaUrl('logo'))
                <img src="{{ $setting->getFirstMediaUrl('logo') }}" alt="Logo" style="height: 60px; width: 60px; object-fit: cover; border-radius: 8px; margin-right: 20px;">
            @endif
            <div>
                <div style="font-size: 1.5em; font-weight: bold;">{{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}</div>
                <div style="font-size: 1em;">{{ $setting->alamat ?? '-' }}</div>
                <div style="font-size: 1em;">Telp: {{ $setting->telepon ?? '-' }}</div>
            </div>
        </div>
    @endif
    <h2>Invoice Pembayaran #{{ $Pembayaran->id }}</h2>
    <p><strong>Tanggal:</strong> {{ $Pembayaran->created_at->format('d-m-Y H:i') }}</p>
    <p><strong>Customer:</strong> {{ $Pembayaran->transaksiMasuk->kendaraan->customer->nama ?? '-' }}</p>
    <p><strong>No. Polisi:</strong> {{ $Pembayaran->transaksiMasuk->kendaraan->no_polisi }}</p>
    <p><strong>Metode Pembayaran:</strong> {{ $Pembayaran->metodePembayaran->nama_metode }}</p>

    <h3>Rincian Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Pembayaran->detail as $item)
            <tr>
                
                <td>{{ $item->nama_item }}</td>
                <td>{{ $item->qty }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan) }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total">Total: Rp {{ number_format($Pembayaran->total_bayar) }}</p>
    <p class="total">Dibayar: Rp {{ number_format($Pembayaran->dibayar) }}</p>
    <p><strong>Kembalian:</strong> Rp {{ number_format($Pembayaran->dibayar - $Pembayaran->total_bayar, 0, ',', '.') }}</p>


    <button onclick="window.print()">Cetak Invoice</button>
</body>
</html>

<script>
    window.onload = function() {
        window.print();
    };
</script>
