<x-filament-widgets::widget>
    <x-filament::section>
        <div class="mb-4">
            <div class="text-lg font-bold mb-2">Status Kendaraan</div>
            @php
                $total = $total > 0 ? $total : 1;
            @endphp
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>Waiting</span>
                    <span>{{ $waiting }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gray-400 h-3 rounded-full" style="width: {{ ($waiting / $total) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>Sedang Dikerjakan</span>
                    <span>{{ $sedangDikerjakan }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-blue-500 h-3 rounded-full" style="width: {{ ($sedangDikerjakan / $total) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>Menunggu Sparepart</span>
                    <span>{{ $menungguSparepart }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-yellow-400 h-3 rounded-full" style="width: {{ ($menungguSparepart / $total) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>Pemeriksaan Akhir</span>
                    <span>{{ $pemeriksaanAkhir }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-purple-400 h-3 rounded-full" style="width: {{ ($pemeriksaanAkhir / $total) * 100 }}%"></div>
                </div>
            </div>
            <div class="mb-2">
                <div class="flex justify-between mb-1">
                    <span>Selesai</span>
                    <span>{{ $selesai }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ ($selesai / $total) * 100 }}%"></div>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
