
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Aqlaya Cake')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Shalimar&display=swap"
        rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen overflow-x-hidden bg-mono-50 antialiased selection:bg-pink-600 selection:text-white">
    @php
        $cartCount = collect(session(\App\Services\CartService::SESSION_KEY, []))->sum('quantity');
        $storeMapUrl = 'https://www.google.com/maps/place/Aqlaya+Cake/@-6.8706374,109.1358249,17z/data=!3m1!4b1!4m6!3m5!1s0x2e6fb74b4389ec67:0xc4caa2c69961f46!8m2!3d-6.8706374!4d109.1384052!16s%2Fg%2F11qgbqv9jw?entry=ttu&g_ep=EgoyMDI2MDQwNy4wIKXMDSoASAFQAw%3D%3D';
    @endphp

    <x-navbar :cartCount="$cartCount" />

    <main
        class="flex min-h-screen flex-col {{ request()->routeIs('home') || request()->routeIs('catalog') ? '' : 'pt-24 md:pt-28' }}">
        @if(request()->routeIs('home') || request()->routeIs('catalog'))
            @yield('content')
        @else
            <div class="mx-auto w-full max-w-[1600px] px-5 py-10 sm:px-8 lg:px-12 lg:py-14">
                @include('partials.flash')
                @yield('content')
            </div>
        @endif
    </main>

    <x-footer />

    @stack('scripts')
</body>

</html>