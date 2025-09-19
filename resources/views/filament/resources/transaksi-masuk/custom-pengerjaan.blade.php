{{-- resources/views/filament/resources/transaksi-masuk/custom-pengerjaan.blade.php --}}

@php
    $Pembayaran = $transaksi->pembayaran;
@endphp

<div 
    class="fi-section-content-ctn"
    x-data="{ 
        refreshing: false,
        async handleRefresh() {
            this.refreshing = true;
            // Tunggu sebentar untuk animasi
            await new Promise(resolve => setTimeout(resolve, 500));
            window.location.reload();
        }
    }"
    x-on:refresh.window="handleRefresh()"
>
    <!-- Loading indicator -->
    <div x-show="refreshing" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-4 rounded-lg shadow-lg">
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span>Memuat data terbaru...</span>
            </div>
        </div>
    </div>

    @foreach ($pengerjaanList as $pengerjaan)
        {{-- SPAREPART DIPAKAI --}}
        @if ($pengerjaan->spareparts->count())
            <div style="margin-bottom: 30px" class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-6">
                <div class="fi-section-header flex flex-col gap-3 px-6 py-4">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Sparepart Dipakai
                    </h3>
                </div>
                
                <div class="fi-section-content px-6 pb-6">
                    <div class="overflow-x-auto">
                        <table class="fi-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                            <thead class="divide-y divide-gray-200 dark:divide-white/5">
                                <tr class="bg-gray-50 dark:bg-white/5">
                                    <th class="fi-table-header-cell px-3 py-3.5 text-start text-sm font-medium text-gray-950 dark:text-white">
                                        Sparepart
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-center text-sm font-medium text-gray-950 dark:text-white">
                                        Qty
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-end text-sm font-medium text-gray-950 dark:text-white">
                                        Harga
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-end text-sm font-medium text-gray-950 dark:text-white">
                                        Subtotal
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-center text-sm font-medium text-gray-950 dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                                @php $totalSparepart = 0; @endphp
                                @foreach ($pengerjaan->spareparts as $sparepart)
                                    @php $totalSparepart += $sparepart->subtotal; @endphp
                                    <tr class="fi-table-row hover:bg-gray-50 dark:hover:bg-white/5">
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    {{ $sparepart->barang->nama_barang ?? '-' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-center">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    {{ $sparepart->qty }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-end">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    IDR {{ number_format($sparepart->harga, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-end">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    IDR {{ number_format($sparepart->subtotal, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-center">
                                                <form method="POST" action="{{ route('sparepart.delete', $sparepart->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="fi-link fi-link-size-sm relative inline-flex items-center justify-center font-semibold outline-none transition duration-75 hover:underline focus:underline focus:outline-none focus:outline-2 focus:outline-offset-2 text-danger-600 hover:text-danger-500 dark:text-danger-400 dark:hover:text-danger-300 focus:outline-danger-600 dark:focus:outline-danger-400"
                                                        onclick="return confirm('Yakin hapus sparepart ini?')"
                                                    >
                                                        <span class="fi-link-label">
                                                            Delete
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 dark:bg-white/5 font-semibold">
                                    <td colspan="3" class="fi-table-cell p-0">
                                        <div class="fi-table-cell-content px-3 py-4 text-end">
                                            <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                Total Biaya Sparepart
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-table-cell p-0">
                                        <div class="fi-table-cell-content px-3 py-4 text-end">
                                            <div class="text-sm leading-6 text-gray-950 dark:text-white font-bold">
                                                IDR {{ number_format($totalSparepart, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-table-cell p-0"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- JASA DIPAKAI --}}
        @if ($pengerjaan->jasas->count())
            <div style="margin-bottom: 30px" class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 mb-6">
                <div class="fi-section-header flex flex-col gap-3 px-6 py-4">
                    <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Jasa Digunakan
                    </h3>
                </div>
                
                <div class="fi-section-content px-6 pb-6">
                    <div class="overflow-x-auto">
                        <table class="fi-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                            <thead class="divide-y divide-gray-200 dark:divide-white/5">
                                <tr class="bg-gray-50 dark:bg-white/5">
                                    <th class="fi-table-header-cell px-3 py-3.5 text-start text-sm font-medium text-gray-950 dark:text-white">
                                        Jasa
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-end text-sm font-medium text-gray-950 dark:text-white">
                                        Harga
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-end text-sm font-medium text-gray-950 dark:text-white">
                                        Subtotal
                                    </th>
                                    <th class="fi-table-header-cell px-3 py-3.5 text-center text-sm font-medium text-gray-950 dark:text-white">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                                @php $totalJasa = 0; @endphp
                                @foreach ($pengerjaan->jasas as $jasa)
                                    @php $totalJasa += $jasa->subtotal; @endphp
                                    <tr class="fi-table-row hover:bg-gray-50 dark:hover:bg-white/5">
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    {{ $jasa->jasa->nama_jasa ?? '-' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-end">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    IDR {{ number_format($jasa->harga, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-end">
                                                <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    IDR {{ number_format($jasa->subtotal, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fi-table-cell p-0">
                                            <div class="fi-table-cell-content px-3 py-4 text-center">
                                                <form method="POST" action="{{ route('jasa.delete', $jasa->id) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button 
                                                        type="submit" 
                                                        class="fi-link fi-link-size-sm relative inline-flex items-center justify-center font-semibold outline-none transition duration-75 hover:underline focus:underline focus:outline-none focus:outline-2 focus:outline-offset-2 text-danger-600 hover:text-danger-500 dark:text-danger-400 dark:hover:text-danger-300 focus:outline-danger-600 dark:focus:outline-danger-400"
                                                        onclick="return confirm('Yakin hapus jasa ini?')"
                                                    >
                                                        <span class="fi-link-label">
                                                            Delete
                                                        </span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-100 dark:bg-white/5 font-semibold">
                                    <td class="fi-table-cell p-0"></td>
                                    <td class="fi-table-cell p-0">
                                        <div class="fi-table-cell-content px-3 py-4 text-end">
                                            <div class="text-sm leading-6 text-gray-950 dark:text-white">
                                                Total Biaya Jasa
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-table-cell p-0">
                                        <div class="fi-table-cell-content px-3 py-4 text-end">
                                            <div class="text-sm leading-6 text-gray-950 dark:text-white font-bold">
                                                IDR {{ number_format($totalJasa, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fi-table-cell p-0"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- SECTION PEMBAYARAN --}}
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-header flex flex-col gap-3 px-6 py-4">
            <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                Pembayaran
            </h3>
        </div>
        
        <div class="fi-section-content px-6 pb-6">
            <div class="flex justify-end gap-3">
                @if ($Pembayaran)
                    <a 
                        href="{{ route('invoice.cetak', $Pembayaran->id) }}" 
                        target="_blank" 
                        style="display:inline-block; padding:8px 12px; background-color:#f3f4f6; color:#111827; font-weight:600; border:1px solid #d1d5db; border-radius:6px; text-decoration:none; margin-right:6px;"
                    >
                        🧾 Cetak Nota
                    </a>

                    <button 
                        type="button" 
                        style="padding:8px 12px; background-color:#dc2626; color:#fff; font-weight:600; border:none; border-radius:6px; cursor:pointer;"
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', {id: 'batal-bayar-modal'})"
                    >
                        ❌ Batal Bayar
                    </button>
                @else
                    <form action="{{ route('pembayaran.dari-transaksi', $transaksi->id) }}" method="POST" class="w-full">
                        @csrf
                        <button 
                            type="submit" 
                            style="width:100%; padding:8px 12px; background-color:#6b7280bb; color:#fff; font-weight:600; border:none; border-radius:6px; box-shadow:0 1px 2px rgba(0,0,0,0.1); cursor:pointer;"
                        >
                            💰 Proses Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI MENGGUNAKAN ALPINE.JS & TAILWIND --}}
@if ($Pembayaran)
    <div 
        x-data="{ open: false }" 
        x-on:open-modal.window="if ($event.detail.id === 'batal-bayar-modal') open = true"
        x-show="open" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        {{-- Backdrop --}}
        <div 
            class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
            x-on:click="open = false"
        ></div>

        {{-- Modal --}}
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                class="relative transform overflow-hidden rounded-lg bg-white shadow-xl transition-all dark:bg-gray-900 sm:my-8 sm:w-full sm:max-w-md"
                x-on:click.stop
            >
                {{-- Header --}}
                <div style="padding:16px 24px;">
                    <h3 style="font-size:18px; font-weight:600; color:#111827; margin:0;" class="dark:text-white">
                        Konfirmasi Pembatalan
                    </h3>
                </div>

                {{-- Content --}}
                <form action="{{ route('pembayaran.destroy', $Pembayaran->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div style="padding:0 24px 16px;">
                        <p style="font-size:14px; color:#4b5563;" class="dark:text-gray-400">
                            Apakah Anda yakin ingin <strong>membatalkan pembayaran</strong> ini?
                        </p>
                    </div>

                    {{-- Footer --}}
                    <div style="display:flex; justify-content:flex-end; gap:12px; padding:16px 24px; background-color:#f9fafb;" class="dark:bg-gray-800">
                        <button 
                            type="button" 
                            x-on:click="open = false"
                            style="padding:8px 14px; background-color:#f3f4f6; color:#111827; font-weight:600; border:1px solid #d1d5db; border-radius:6px; cursor:pointer; font-size:14px; box-shadow:0 1px 2px rgba(0,0,0,0.05);"
                        >
                            Tidak
                        </button>
                        
                        <button 
                            type="submit" 
                            style="padding:8px 14px; background-color:#dc2626; color:#fff; font-weight:600; border:none; border-radius:6px; cursor:pointer; font-size:14px; box-shadow:0 1px 2px rgba(0,0,0,0.05);"
                        >
                            Ya, Batalkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif