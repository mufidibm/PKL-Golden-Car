<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Bengkel')</title>

    {{-- Tailwind --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-gray-100">

    <nav class="bg-white border-b shadow p-4 mb-6">
        <div class="container mx-auto flex items-center space-x-4">
            @php
                $setting = \App\Models\Setting::first();
            @endphp

            @if ($setting && $setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}"
                     alt="Logo"
                     class="fixed top-0 bottom-0 h-10 w-10 rounded-full object-cover">
            @endif

            <h1 class="text-xl font-semibold text-gray-800">
                {{ $setting->nama_bengkel ?? 'Aplikasi Bengkel' }}
            </h1>
        </div>
    </nav>

    <main class="container mx-auto px-4">
        <div class="bg-white m-5 p-5 rounded shadow p-6">
            @yield('content')
        </div>
    </main>

    {{-- JS Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>