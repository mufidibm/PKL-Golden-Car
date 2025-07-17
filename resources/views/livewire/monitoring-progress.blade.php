<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mb-4">
            <div class="text-lg font-bold mb-2">Monitoring Progress</div>
            <div class="overflow-x-auto">
                <table class="min-w-full w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">No Polisi</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Pembayaran</th>
                            <th class="px-4 py-2 text-left">Masuk</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $row)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $row->kendaraan->customer->nama ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $row->kendaraan->no_polisi ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @php
                                        $statusColor = [
                                            'waiting' => 'bg-gray-200 text-gray-700',
                                            'sedang dikerjakan' => 'bg-blue-100 text-blue-700',
                                            'menunggu sparepart' => 'bg-yellow-100 text-yellow-700',
                                            'pemeriksaan akhir' => 'bg-purple-100 text-purple-700',
                                            'selesai' => 'bg-green-100 text-green-700',
                                        ][strtolower($row->status)] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    @php
                                        $pembayaran = $row->pembayaran;
                                    @endphp
                                    @if($pembayaran && $pembayaran->dibayar >= $pembayaran->total_bayar)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Sudah Bayar</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-200 text-gray-700">Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $row->created_at ? $row->created_at->diffForHumans() : '-' }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('filament.admin.resources.transaksi-masuks.edit', $row->id) }}" class="px-4 py-1 rounded bg-orange-100 text-orange-700 hover:bg-orange-200 text-xs">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
