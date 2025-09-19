@php
    $setting = \App\Models\Setting::first();
@endphp

@if ($setting && $setting->logo)
    <div class="flex relative justify-center items-center h-full max-h-12">
        <img src="{{ asset('storage/' . $setting->logo) }}"
             alt="Logo"
             class="h-full w-auto max-h-full object-contain" />
    </div>
@endif