<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aqlaya Cake')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Noto+Serif:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen overflow-x-hidden bg-[#f5efe7] antialiased selection:bg-black selection:text-white">
@php
    $cartCount = collect(session(\App\Services\CartService::SESSION_KEY, []))->sum('quantity');
    $storeMapUrl = 'https://www.google.com/maps/place/Aqlaya+Cake/@-6.8706374,109.1358249,17z/data=!3m1!4b1!4m6!3m5!1s0x2e6fb74b4389ec67:0xc4caa2c69961f46!8m2!3d-6.8706374!4d109.1384052!16s%2Fg%2F11qgbqv9jw?entry=ttu&g_ep=EgoyMDI2MDQwNy4wIKXMDSoASAFQAw%3D%3D';
@endphp

<x-navbar :cartCount="$cartCount" />

<main class="flex min-h-screen flex-col {{ request()->routeIs('home') || request()->routeIs('catalog') ? '' : 'pt-24 md:pt-28' }}">
    @if(request()->routeIs('home') || request()->routeIs('catalog'))
        @yield('content')
    @else
        <div class="mx-auto w-full max-w-[1600px] px-5 py-10 sm:px-8 lg:px-12 lg:py-14">
            @include('partials.flash')
            @yield('content')
        </div>
    @endif
</main>

<footer id="visit" class="mt-auto border-t border-black/10 bg-[#e8dfd3]">
    <div class="mx-auto max-w-[1600px] px-5 py-10 sm:px-8 sm:py-12 lg:px-12 lg:py-14">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] uppercase tracking-[0.28em] text-black/45">Aqlaya Cake Studio</p>
                <h2 class="mt-4 font-serif text-3xl font-semibold uppercase leading-tight text-black sm:text-4xl">
                    Curated cakes for intimate celebrations.
                </h2>
                <p class="mt-4 max-w-xl text-sm leading-7 text-black/60">
                    Visual yang rapi, rasa yang tetap hangat, dan proses pemesanan yang dibuat sederhana untuk hadiah maupun momen spesial.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3 lg:min-w-[580px]">
                <a href="{{ route('home') }}#catalog-grid" class="border-b border-black/10 pb-3 text-[11px] uppercase tracking-[0.22em] text-black/60 transition hover:text-black">
                    Catalog
                </a>
                <a href="{{ $storeMapUrl }}" target="_blank" rel="noopener noreferrer" class="border-b border-black/10 pb-3 text-[11px] uppercase tracking-[0.22em] text-black/60 transition hover:text-black">
                    Google Maps
                </a>
                <a href="https://www.instagram.com/aqlayacake" target="_blank" rel="noopener noreferrer" class="border-b border-black/10 pb-3 text-[11px] uppercase tracking-[0.22em] text-black/60 transition hover:text-black">
                    Instagram
                </a>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-2 border-t border-black/10 pt-4 text-[11px] uppercase tracking-[0.18em] text-black/45 sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} Aqlaya Cake Studio</p>
            <p>Pekalongan, Jawa Tengah</p>
            <p>Custom cakes, dessert gifting, and soft celebrations.</p>
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
