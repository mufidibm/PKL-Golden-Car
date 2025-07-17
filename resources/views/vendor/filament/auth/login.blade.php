@php
    $setting = \App\Models\Setting::first();
    $logoUrl = $setting?->getFirstMediaUrl('logo');
@endphp

<div class="flex flex-col items-center justify-center mb-6">
    @if ($logoUrl)
        <img src="{{ $logoUrl }}" alt="Logo" style="height: 80px; width: 80px; object-fit: cover; border-radius: 12px; margin-bottom: 16px;">
    @endif
    <h1 class="text-2xl font-bold">{{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}</h1>
</div>

<div>DEBUG: {{ $logoUrl }}</div>

<img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo" class="mb-4 w-32 mx-auto" />

{{ $slot }} 