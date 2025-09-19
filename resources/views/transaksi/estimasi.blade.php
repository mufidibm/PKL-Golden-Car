<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Estimasi Biaya Servis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        Carbon::setLocale('id');
        $tanggalFormatted = 'Bekasi, ' . Carbon::parse($transaksi->waktu_masuk)->translatedFormat('d F Y');
        $ppnRate = 0.11; // Asumsi PPN 11%

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
                <h1 style="font-size: 20px; font-weight: bold; margin: 0;">ESTIMASI BIAYA SERVIS</h1>
<h1 style="font-size: 20px; font-weight: bold; margin: 0;">
    {{ $transaksi->kode_estimasi }}/GC/EST/{{ Carbon::parse($transaksi->waktu_masuk)->format('m/Y') }}
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
    <table style="width: 70%; border-collapse: collapse; border: none; font-size: 13px; font-weight: normal;">
        <tr>
            <td style="padding: 4px; border: none;">Nama Asuransi</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->asuransi->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Nama Pemilik</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->kendaraan->customer->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Merk / Tipe Kendaraan</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->kendaraan->merek ?? '-' }} /
                {{ $transaksi->kendaraan->tipe ?? '-' }}
            </td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Warna</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->kendaraan->warna ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">No. Polisi</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->kendaraan->no_polisi ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 4px; border: none;">Keluhan</td>
            <td style="padding: 4px; border: none;">:&nbsp;{{ $transaksi->keluhan ?? '-' }}</td>
        </tr>
    </table>
    <p style="font-size: 13px; margin-bottom: 3px;"><b>Terlampir Rincian Biaya Jasa :</b></p>
    <table style="font-size: 13px;">
        <colgroup>
            <col style="width: 2%;">
            <col>
            <col>
        </colgroup>
        <thead>
            <tr>
                <th>NO.</th>
                <th>JENIS JASA</th>
                <th colspan="2">HARGA</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 1;
            $totalJasa = 0; @endphp
        @foreach ($transaksi->pengerjaanServis->flatMap->pengerjaanJasa as $item)
            @php $totalJasa += $item->harga; @endphp
            <tr>
                <td style="text-align: center;">{{ $i++ }}.</td>
                <td>{{ $item->jasa->nama_jasa }}</td>
                <td colspan="2"
                    class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($item->harga, 0, ',', '.') }}
                </td>
                </tr>
        @endforeach
        @if ($transaksi->pengerjaanServis->flatMap->pengerjaanJasa->isEmpty())
            <tr>
                <td colspan="4"
                    style="text-align: center;">Belum ada jasa yang dipilih</td>
                </tr>
        @endif
            <tr>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">TOTAL</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($totalJasa, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 13px; margin-bottom: 3px;"><b>Terlampir Rincian Biaya Sparepart :</b></p>
    <table style="font-size: 13px;">
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
            @php $i = 1;
            $totalSparepart = 0; @endphp
        @foreach ($transaksi->pengerjaanServis->flatMap->pengerjaanSparepart as $item)
            @php $qty = $item->qty;
                $subtotal = $item->harga * $qty;
            $totalSparepart += $subtotal; @endphp
            <tr>
                <td style="text-align: center;">{{ $i++ }}.</td>
                <td>{{ $item->barang->nama_barang }}</td>
                <td>{{ $qty }}</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($item->harga, 0, ',', '.') }}
                </td>
                    <td class="text-right">
                        <span style="float: left;">Rp</span> {{ number_format($subtotal, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
        @if ($transaksi->pengerjaanServis->flatMap->pengerjaanSparepart->isEmpty())
            <tr>
                <td colspan="5"
                    style="text-align: center;">Belum ada sparepart yang dipilih</td>
            </tr>
        @endif
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="2"
                    style="font-weight: bold;">TOTAL</td>
                <td class="text-right">
                    <span style="float: left;">Rp</span> {{ number_format($totalSparepart, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="border: none;"></td>
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
                    style="font-weight: bold;">GRAND TOTAL</td>
                <td class="text-right">
                    @php $grandTotal = $totalJasa + $totalSparepart; @endphp
                    <span style="float: left;">Rp</span> {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>
    <p style="font-size: 13px;">
        Demikian estimasi ini kami sampaikan atas perhatian dan kerjasamanya kami ucapkan, <br>terimakasih.
    </p>
    <div class="pengesahan">
        <div class="sah-kiri">
            <p style="margin: 0;">{{ $tanggalFormatted }} <br>
                Hormat kami
                <br><br><br><br><br>
                Endang Sandirosa <br>
                Service Advisor
            </p>
        </div>
        <div class="sah-kanan">
            <br>
            <p style="margin: 0;">Mengetahui
                <br><br><br><br><br>
                Ramma Krismawandi <br>
                Kepala Bengkel
            </p>
        </div>
    </div>
    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>

</html>