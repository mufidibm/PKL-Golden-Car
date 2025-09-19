<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: arial;
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }

        th,
        td {
            border: 2.5px solid #000;
            padding: 4px;
            text-align: left;
        }

        .total {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .pengesahan {
            position: relative;
            top: 30px;
            display: flex;
            justify-content: space-between;
            align-content: center;
            width: 100%;
            margin: auto;
            font-size: 13px;
            padding-top: 40px;
        }

        @media print {
            .pengesahan {
                page-break-inside: avoid;
                break-inside: avoid;
                margin-top: 30px;
            }
        }
    </style>
</head>

<body>
    @php
        use Carbon\Carbon;
        use App\Models\Asuransi;

        // Atur locale ke bahasa Indonesia
        Carbon::setLocale('id');

        $jasa = $Pembayaran->detail->where('jenis_item', 'jasa');
        $sparepart = $Pembayaran->detail->where('jenis_item', 'sparepart');

        // Hitung PPN secara manual
        $ppnJasa = 0;
        $ppnSparepart = 0;
        $totalJasa = 0;
        $totalSparepart = 0;

        foreach ($jasa as $item) {
            $totalJasa += $item->subtotal;
            if (session('gunakan_ppn_jasa', false)) {
                $ppnJasa += 0.11 * $item->subtotal;
            }
        }
        foreach ($sparepart as $item) {
            $totalSparepart += $item->subtotal;
            if (session('gunakan_ppn_sparepart', false)) {
                $ppnSparepart += 0.11 * $item->subtotal;
            }
        }

        $tanggalFormatted = 'Bekasi, ' . Carbon::parse($Pembayaran->created_at)->translatedFormat('d F Y');
        $setting = \App\Models\Setting::first();
    @endphp
    @if ($setting)
        <div style="display: flex; align-items: center; margin: 0; padding: 0 10px;">
            <div style="max-width: 180px;">
                @if ($setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}"
                         alt="Logo"
                         style="height: 60px; border-radius: 8px; margin-right: 20px; position: relative; bottom: -8px">
                @endif
                <div style="font-size: 13px; margin: 0;">{{ $setting->alamat ?? '-' }}</div>
            </div>
            <div style="position: absolute; width: 100%; text-align: center; justify-self: center;">
                <h1 style="font-size: 20px; font-weight: bold; margin: 0;">INVOICE KENDARAAN</h1>
                <h1 style="font-size: 20px; font-weight: bold; margin: 0;">
                    {{ $Pembayaran->kode_invoice }}/GC/INV/{{ Carbon::parse($Pembayaran->created_at)->format('m/Y') }}
                </h1>
            </div>
        </div>
        <div
             style="display: flex; justify-content: space-between; font-size: 13px; position: relative; bottom: -4px; padding: 0 10px; margin: 0;">
            <p style="margin: 0;">{{ $setting->telepon ?? '-' }}</p>

            <p style="margin: 0;">{{ $tanggalFormatted }}</p>
        </div>
        <hr style="border: 0.6px solid black; margin-bottom: 1.3px;">
        <hr style="border: 0.6px solid black; margin-top: 0;">
    @endif
    <table style="width: 70%; border-collapse: collapse; border: none; font-size: 13px; font-weight: normal">
        <tr>
            <td style="padding: 4px; border: none;">Nama Asuransi</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->transaksiMasuk->asuransi->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Nama Pemilik</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->transaksiMasuk->kendaraan->customer->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Merk / Tipe Kendaraan</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->transaksiMasuk->kendaraan->merek ?? '-' }} /
                {{ $Pembayaran->transaksiMasuk->kendaraan->tipe ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Warna</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->transaksiMasuk->kendaraan->warna ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">No. Polisi</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->transaksiMasuk->kendaraan->no_polisi ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Metode Pembayaran</td>
            <td style="padding: 4px; border: none;">
                :&nbsp;{{ $Pembayaran->metodePembayaran->nama_metode ?? '-' }}</td>
        </tr>
    </table>
    <p style="font-size: 13px; margin-bottom: 3px"><b>Terlampir Rincian Biaya Jasa :</b></p>
    <table style="font-size: 13px">
        <colgroup>
            <col style="width: 2%;">
            <col>
            <col style="width: 15%;">
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>NO.</th>
                <th>JENIS JASA</th>
                <th>JENIS ASURANSI</th>
                <th>HARGA</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($jasa as $item)
                <tr>
                    <td style="text-align: center">{{ $i++ }}.</td>
                    <td>{{ $item->nama_item }}</td>
                    <td>{{ App\Models\Asuransi::find($item->qty)->nama ?? 'Tidak ada asuransi' }}</td>
                    <td class="text-right">
                        <span style="float: left;">Rp</span> {{ number_format($item->subtotal) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">TOTAL</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($totalJasa) }}
                </td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 13px; margin-bottom: 3px"><b>Terlampir Rincian Biaya Sparepart :</b></p>
    <table style="font-size: 13px">
        <colgroup>
            <col style="width: 2%;">
            <col>
            <col style="width: 7%;">
            <col>
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>NO.</th>
                <th>JENIS SPAREPART</th>
                <th>JUMLAH</th>
                <th>HARGA</th>
                <th>SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @foreach ($sparepart as $item)
                <tr>
                    <td style="text-align: center">{{ $i++ }}.</td>
                    <td>{{ $item->nama_item }}</td>
                    <td>{{ $item->qty }}</td>
                    <td class="text-right">
                        <span style="float: left;">Rp</span> {{ number_format($item->harga_satuan) }}
                    </td>
                    <td class="text-right">
                        <span style="float: left;">Rp</span> {{ number_format($item->subtotal) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">TOTAL</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($totalSparepart) }}
                </td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">PPN JASA</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($ppnJasa) }}
                </td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">PPN SPAREPART</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($ppnSparepart) }}
                </td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">TOTAL BAYAR</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($Pembayaran->total_bayar) }}
                </td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">DIBAYAR</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($Pembayaran->dibayar) }}
                </td>
            </tr>
            {{-- <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">KEMBALIAN</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($Pembayaran->dibayar -
                    $Pembayaran->total_bayar) }}
                </td>
            </tr> --}}
        </tbody>
    </table>
    <p style="font-size: 13px">Demikian invoice ini kami sampaikan atas perhatian dan kerjasamanya kami ucapkan,
        <br>terimakasih.
    </p>
    <p style="margin: 0; font-size:13px">No Rekening Kami: {{ $setting->rekening ?? '-' }}</p>

    <div class="pengesahan">
        <div class="sah-kiri">
            <p style="margin: 0;">{{ $tanggalFormatted }} <br>
                Hormat kami
                <br><br><br><br><br>
                <br>
                Finance
            </p>
        </div>
        <div class="sah-kanan">
        </div>
    </div>
</body>

</html>
<script>
    window.onload = function () {
        window.print();
    };
</script>