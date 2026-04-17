@extends('layouts.app')

@section('title', 'Aqlaya Cake | Curated Patisserie Landing')

@section('content')
    @php
        $heroProduct = $bestSellers->first() ?? $products->first();
        $minimumOrderLabel = \Illuminate\Support\Carbon::parse($minimumOrderDate)->locale('id')->translatedFormat('d M Y');
        $signatureProducts = $bestSellers
            ->concat($products->getCollection())
            ->unique('id')
            ->take(6)
            ->values();

        // Static banners with metadata
        $staticBanners = [
            [
                'type' => 'static_grid',
                'title' => 'Aqlaya Cake',
                'left_image' => 'images/hero1.png',
                'right_image' => 'images/hero2.png',
            ],
            [
                'type' => 'static_list',
                'title' => 'List Cakes',
                'background_image' => 'images/hero2.png',
                'categories' => [
                    [
                        'name' => 'Cakes',
                        'items' => ['Butter Cake', 'Fruit Cake', 'Birthday Cake Custom', 'Vanilla Cake', 'Tiramisu Cake']
                    ],
                    [
                        'name' => 'Bread',
                        'items' => ['Roti Coklat', 'Donat', 'Pizza', 'Roti Sisir', 'Roti Abon']
                    ],
                    [
                        'name' => 'Pastry',
                        'items' => ['Croissant', 'Danish Strawberry', 'Danish Chocolate', 'Cheese Puff']
                    ],
                    [
                        'name' => 'Dessert',
                        'items' => ['Cheesecake', 'Tiramisu Cup', 'Pudding Cokelat', 'Panna Cotta', 'Dessert Box (cokelat/oreo)', 'Banana Dessert']
                    ],
                ],
            ],
        ];

        // Combine static and dynamic banners
        $allBanners = collect($staticBanners)->concat($banners);
        $totalBanners = $allBanners->count();

        // Best sellers data - dynamic from database, chunked into slides of 3
        $bestSellersSlides = $bestSellerCarousel->chunk(3)->values();
    @endphp

    <section>
        <div class="mx-auto w-full">
            @include('partials.flash')

            <!-- Combined Banner Carousel -->
            <div class="relative overflow-hidden h-[70vh] lg:h-[80vh] bg-white" x-data="{
                                                                    currentSlide: 0,
                                                                    totalSlides: {{ $totalBanners }},
                                                                    autoplayInterval: null,
                                                                    startAutoplay() {
                                                                        this.autoplayInterval = setInterval(() => {
                                                                            this.currentSlide = this.currentSlide === this.totalSlides - 1 ? 0 : this.currentSlide + 1;
                                                                        }, 5000);
                                                                    },
                                                                    stopAutoplay() {
                                                                        if (this.autoplayInterval) {
                                                                            clearInterval(this.autoplayInterval);
                                                                            this.autoplayInterval = null;
                                                                        }
                                                                    },
                                                                    resetAutoplay() {
                                                                        this.stopAutoplay();
                                                                        this.startAutoplay();
                                                                    }
                                                                }" x-init="startAutoplay()" @mouseenter="stopAutoplay()"
                @mouseleave="startAutoplay()">
                @foreach($allBanners as $index => $banner)
                    <div class="absolute inset-0 transition-opacity duration-700" x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-700"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        style="{{ $index === 0 ? '' : 'display: none;' }}">

                        @if(is_array($banner) && isset($banner['type']) && $banner['type'] === 'static_grid')
                            <!-- Static Grid Banner -->
                            <div class="relative w-full h-full bg-white overflow-hidden">
                                <!-- Right Image (Large) -->
                                <img src="{{ asset($banner['right_image']) }}" alt="Aqlaya Cake"
                                    class="absolute w-[47.78%] h-auto right-[-15.69%] top-[-4.14%] object-cover">
                                <!-- Left Image (Small) -->
                                <img src="{{ asset($banner['left_image']) }}" alt="Aqlaya Cake"
                                    class="absolute w-[29.24%] h-auto left-[-8.33%] top-[41.82%] object-cover">

                                <!-- Center Text -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-center z-10">
                                    <h1 class="text-6xl sm:text-7xl lg:text-9xl font-normal text-black"
                                        style="font-family: 'Shalimar', cursive;">
                                        {{ $banner['title'] }}
                                    </h1>

                                    <!-- Order Now Button -->
                                    <div class="mt-8">
                                        <a href="#catalog-grid"
                                            class="inline-flex items-center justify-center w-32 h-10 bg-zinc-600/80 hover:bg-zinc-700 rounded-3xl text-white text-xs font-medium tracking-wide transition-colors"
                                            style="font-family: 'Poppins', sans-serif;">
                                            Order Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @elseif(is_array($banner) && isset($banner['type']) && $banner['type'] === 'static_list')
                            <!-- Static List Banner -->
                            <div class="relative w-full h-full bg-white overflow-hidden">
                                <!-- Background Image (Left) - Mirror position dari right_image di banner 1 -->
                                <img src="{{ asset($banner['background_image']) }}" alt="Background"
                                    class="absolute w-[47.78%] h-auto left-[-29%] top-[-4.14%] object-cover opacity-90">

                                <!-- Main Content Area -->
                                <div class="absolute inset-0 h-full flex flex-col justify-center items-center px-4 lg:px-8">
                                    <!-- Title -->
                                    <h2 class="text-5xl lg:text-6xl font-bold mb-12 text-gray-900"
                                        style="font-family: 'Poppins', sans-serif;">
                                        {{ $banner['title'] }}
                                    </h2>

                                    <!-- Categories Grid -->
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                                        @foreach($banner['categories'] as $category)
                                            <div class="space-y-4">
                                                <!-- Category Title -->
                                                <h3 class="text-xl lg:text-2xl font-semibold text-gray-800 mb-4"
                                                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                    {{ $category['name'] }}
                                                </h3>
                                                <!-- Product List -->
                                                <ul class="space-y-2">
                                                    @foreach($category['items'] as $item)
                                                        <li class="text-sm lg:text-base text-gray-600"
                                                            style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                            {{ $item }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Dynamic Banner -->
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title ?? 'Aqlaya Cake' }}"
                                class="absolute inset-0 h-full w-full object-cover">
                        @endif
                    </div>
                @endforeach

                <!-- Dots Indicator -->
                @if($totalBanners > 1)
                    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                        @foreach($allBanners as $index => $banner)
                            <button @click="currentSlide = {{ $index }}; resetAutoplay()"
                                class="h-2 w-2 rounded-full transition-all"
                                :class="currentSlide === {{ $index }} ? 'bg-mono-900 w-8' : 'bg-mono-900/50'"
                                aria-label="Go to banner {{ $index + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Best Sellers Carousel Section -->
    @if($bestSellerCarousel->isNotEmpty())
        <section id="about" class="scroll-mt-32 bg-white py-16 lg:py-24">
            <div class="mx-auto max-w-[1600px] px-5 sm:px-8 lg:px-12">
                <div class="mb-16 lg:mb-24 text-center">
                    <h2 class="font-serif text-4xl font-light text-mono-900 sm:text-5xl lg:text-6xl">
                        Our Best Seller
                    </h2>
                </div>

                @if($bestSellersSlides->count() > 1)
                    <!-- Carousel Container (multiple slides) -->
                    <div class="relative overflow-hidden pb-32"
                        x-data="{
                                                                                                                                            currentSlide: 0,
                                                                                                                                            totalSlides: {{ $bestSellersSlides->count() }},
                                                                                                                                            autoplayInterval: null,
                                                                                                                                            startAutoplay() {
                                                                                                                                                this.autoplayInterval = setInterval(() => {
                                                                                                                                                    this.currentSlide = this.currentSlide === this.totalSlides - 1 ? 0 : this.currentSlide + 1;
                                                                                                                                                }, 4000);
                                                                                                                                            },
                                                                                                                                            stopAutoplay() {
                                                                                                                                                if (this.autoplayInterval) {
                                                                                                                                                    clearInterval(this.autoplayInterval);
                                                                                                                                                    this.autoplayInterval = null;
                                                                                                                                                }
                                                                                                                                            },
                                                                                                                                            resetAutoplay() {
                                                                                                                                                this.stopAutoplay();
                                                                                                                                                this.startAutoplay();
                                                                                                                                            }
                                                                                                                                        }" x-init="startAutoplay()">

                        <!-- Slides Wrapper -->
                        <div class="flex transition-transform duration-700 ease-in-out pb-16"
                            :style="`transform: translateX(-${currentSlide * 100}%)`">
                            @foreach($bestSellersSlides as $slideIndex => $slide)
                                <div class="w-full flex-shrink-0">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12 xl:gap-16">
                                        @foreach($slide->values() as $productIndex => $bsProduct)
                                            <article
                                                class="group flex flex-col items-center {{ $productIndex === 1 ? 'lg:mt-32' : 'lg:mt-0' }}">
                                                <a href="{{ route('products.show', $bsProduct) }}"
                                                    class="block w-full max-w-md overflow-hidden">
                                                    <img src="{{ $bsProduct->image_path ? asset('storage/' . $bsProduct->image_path) : ($bsProduct->image_url ?: asset('images/hero1.png')) }}"
                                                        alt="{{ $bsProduct->name }}"
                                                        class="aspect-square w-full object-cover transition duration-700 group-hover:scale-105">
                                                    <div class="mt-8 text-center px-4">
                                                        <h3 class="font-serif text-2xl font-light uppercase tracking-wider text-mono-900 sm:text-3xl lg:text-4xl"
                                                            style="letter-spacing: 0.08em;">
                                                            {{ $bsProduct->name }}
                                                        </h3>
                                                        <p class="mt-6 text-sm leading-relaxed text-mono-600 max-w-xs mx-auto">
                                                            {{ $bsProduct->excerpt }}
                                                        </p>
                                                    </div>
                                                </a>
                                            </article>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <!-- Single slide (no carousel needed) -->
                    <div class="pb-32">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12 xl:gap-16">
                            @foreach($bestSellerCarousel as $productIndex => $bsProduct)
                                <article class="group flex flex-col items-center {{ $productIndex === 1 ? 'lg:mt-32' : 'lg:mt-0' }}">
                                    <a href="{{ route('products.show', $bsProduct) }}" class="block w-full max-w-md overflow-hidden">
                                        <img src="{{ $bsProduct->image_path ? asset('storage/' . $bsProduct->image_path) : ($bsProduct->image_url ?: asset('images/hero1.png')) }}"
                                            alt="{{ $bsProduct->name }}"
                                            class="aspect-square w-full object-cover transition duration-700 group-hover:scale-105">
                                        <div class="mt-8 text-center px-4">
                                            <h3 class="font-serif text-2xl font-light uppercase tracking-wider text-mono-900 sm:text-3xl lg:text-4xl"
                                                style="letter-spacing: 0.08em;">
                                                {{ $bsProduct->name }}
                                            </h3>
                                            <p class="mt-6 text-sm leading-relaxed text-mono-600 max-w-xs mx-auto">
                                                {{ $bsProduct->excerpt }}
                                            </p>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <section id="catalog-grid" class="scroll-mt-32 bg-mono-50/50">
        <div class="mx-auto max-w-[1600px] px-5 py-16 sm:px-8 sm:py-20 lg:px-12 lg:py-28">

            <!-- Header and Search Bar -->
            <div class="mb-12 lg:mb-16 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <!-- Header -->
                <div class="text-left">
                    <p class="text-xs uppercase tracking-[0.3em] text-mono-400 mb-3">Catalog</p>
                    <h2 class="font-serif text-4xl font-light text-mono-900 sm:text-5xl lg:text-6xl">
                        Koleksi Aqlaya
                    </h2>
                </div>

                <!-- Search Bar -->
                <div class="w-full lg:w-auto lg:min-w-[400px] xl:min-w-[500px]">
                    <form method="GET" action="{{ route('catalog') }}"
                        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 rounded-2xl sm:rounded-full border border-mono-200 bg-white p-4 sm:px-6 sm:py-3 shadow-sm transition focus-within:border-mono-400 focus-within:shadow-md">
                        <div class="flex items-center gap-3 flex-1">
                            <svg class="h-5 w-5 flex-shrink-0 text-mono-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6m2-5a7 7 0 11-14 0 7 7 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="flex-1 bg-transparent text-sm text-mono-900 outline-none placeholder:text-mono-400"
                                placeholder="Cari produk favorit Anda...">
                        </div>
                        <select name="category"
                            class="w-full sm:w-auto sm:border-l border-mono-200 bg-transparent sm:pl-3 sm:pr-1 px-3 py-2 sm:py-0 rounded-lg sm:rounded-none text-sm text-mono-600 outline-none border sm:border-0">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="w-full sm:w-auto rounded-full bg-mono-900 px-6 py-2.5 sm:py-2 text-xs font-medium uppercase tracking-wider text-white transition hover:bg-mono-800">
                            Cari
                        </button>
                    </form>
                </div>
            </div>

            <div data-catalog-shell>
                <!-- Category Tabs -->
                <div class="flex items-center justify-start gap-2 overflow-x-auto pb-px scrollbar-hide mb-12 lg:mb-16">
                    <a href="{{ route('catalog') }}"
                        class="whitespace-nowrap rounded-full px-3 py-1.5 sm:px-4 sm:py-2 text-[10px] sm:text-xs uppercase tracking-wider transition {{ !request('category') ? 'bg-mono-900 text-white font-medium shadow-sm' : 'bg-white text-mono-500 hover:text-mono-800 hover:bg-mono-100 border border-mono-200' }}">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                            class="whitespace-nowrap rounded-full px-3 py-1.5 sm:px-4 sm:py-2 text-[10px] sm:text-xs uppercase tracking-wider transition {{ request('category') === $category->slug ? 'bg-mono-900 text-white font-medium shadow-sm' : 'bg-white text-mono-500 hover:text-mono-800 hover:bg-mono-100 border border-mono-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Product Grid -->
                <div
                    class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-5 sm:gap-y-10 lg:grid-cols-4 lg:gap-x-6 lg:gap-y-12">
                    @forelse($products as $product)
                        <article class="group flex flex-col h-full">
                            <a href="{{ route('products.show', $product) }}" class="block flex-1 flex flex-col">
                                <div
                                    class="relative overflow-hidden rounded-2xl bg-white shadow-sm transition duration-300 group-hover:shadow-lg flex-1">
                                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : ($product->image_url ?: asset('images/hero.png')) }}"
                                        alt="{{ $product->name }}"
                                        class="aspect-square w-full object-cover transition duration-700 group-hover:scale-105">
                                    @if($product->is_best_seller)
                                        <span
                                            class="absolute left-3 top-3 rounded-full bg-mono-900 px-3 py-1 text-[10px] font-medium uppercase tracking-wider text-white shadow-sm">
                                            Best Seller
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-4 space-y-1 px-1 flex-shrink-0">
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-mono-400 line-clamp-1">
                                        {{ $product->category?->name ?? 'Cake' }}
                                    </p>
                                    <h3 class="line-clamp-2 text-sm font-medium text-mono-900 sm:text-base leading-tight">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="text-xs font-medium text-mono-600 pt-1">
                                        Rp{{ number_format($product->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </a>
                        </article>
                    @empty
                        <div class="col-span-full py-24 text-center">
                            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-mono-100">
                                <svg class="h-7 w-7 text-mono-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6m2-5a7 7 0 11-14 0 7 7 0114 0z" />
                                </svg>
                            </div>
                            <p class="text-base text-mono-500">Tidak ada produk yang ditemukan.</p>
                            <p class="mt-1 text-sm text-mono-400">Coba kata kunci atau kategori lain.</p>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="mt-14 flex justify-center border-t border-mono-200 pt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const catalogGrid = document.getElementById('catalog-grid');
                if (!catalogGrid) return;

                const shouldFocusCatalog = @json(
                    request()->routeIs('catalog') ||
                    request()->filled('search') ||
                    request()->filled('category') ||
                    request()->filled('max_price') ||
                    request()->filled('page')
                );

                if (shouldFocusCatalog && !window.location.hash) {
                    requestAnimationFrame(() => {
                        catalogGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    });
                }

                const catalogUrlBase = "{{ route('catalog') }}";
                let catalogPath = '/';

                try {
                    catalogPath = new URL(catalogUrlBase).pathname;
                } catch (e) {
                    catalogPath = catalogUrlBase;
                }

                catalogGrid.addEventListener('click', function (e) {
                    const link = e.target.closest('a');
                    if (!link) return;

                    try {
                        const urlObj = new URL(link.href);
                        if (urlObj.pathname === catalogPath || link.href.includes('?page=')) {
                            e.preventDefault();
                            fetchCatalog(link.href);
                        }
                    } catch (err) { }
                });

                catalogGrid.addEventListener('submit', function (e) {
                    const form = e.target.closest('form');
                    if (form) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        const searchParams = new URLSearchParams(formData);
                        const action = form.getAttribute('action') || window.location.pathname;
                        const joiner = action.includes('?') ? '&' : '?';
                        const url = action + joiner + searchParams.toString();
                        fetchCatalog(url);
                    }
                });

                function fetchCatalog(url) {
                    const contentContainer = catalogGrid.querySelector('[data-catalog-shell]');
                    if (contentContainer) {
                        contentContainer.style.opacity = '0.5';
                        contentContainer.style.pointerEvents = 'none';
                        contentContainer.style.transition = 'opacity 0.3s';
                    }

                    fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const newContent = doc.querySelector('#catalog-grid');
                            if (newContent) {
                                catalogGrid.innerHTML = newContent.innerHTML;
                                window.history.pushState({ path: url }, '', url);
                            } else {
                                window.location.href = url;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching catalog:', error);
                            window.location.href = url;
                        });
                }

                window.addEventListener('popstate', function (e) {
                    if (e.state && e.state.path) {
                        fetchCatalog(e.state.path);
                    } else if (window.location.pathname === catalogPath) {
                        fetchCatalog(window.location.href);
                    }
                });
            });
        </script>
    @endpush
@endsection