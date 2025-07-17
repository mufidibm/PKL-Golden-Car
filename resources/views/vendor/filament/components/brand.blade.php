@php
    $setting = \App\Models\Setting::first();
@endphp

<div class="flex items-center space-x-3">
    @if ($setting && $setting->logo)
        <img src="{{ $setting->getFirstMediaUrl('logo') }}" class="h-8 w-8 rounded-full object-cover" alt="Logo">
    @endif

    <span class="text-base font-bold tracking-tight">
        {{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}
    </span>
</div>
