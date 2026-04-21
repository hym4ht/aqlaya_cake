
@extends('layouts.app')

@section('title', 'Aqlaya Cake | Curated Patisserie Landing')

@section('content')
    <style>
        @font-face {
            font-family: 'Verona-Bold';
            src: url('{{ asset('fonts/Verona-Bold.otf') }}') format('opentype');
            font-weight: bold;
            font-style: normal;
            font-display: swap;
        }
    </style>
    @php
        $heroProduct = $bestSellers->first() ?? $products->first();
        $minimumOrderLabel = \Illuminate\Support\Carbon::parse($minimumOrderDate)->locale('id')->translatedFormat('d M Y');
        $signatureProducts = $bestSellers
            ->concat($products)
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
            <div class="relative overflow-hidden h-screen bg-white" x-data="{
                                                                        currentSlide: 0,
                                                                        totalSlides: {{ $totalBanners }},
                                                                        autoplayInterval: null,
                                                                        startAutoplay() {
                                                                            this.autoplayInterval = setInterval(() => {
                                                                                this.currentSlide = this.currentSlide === this.totalSlides - 1 ? 0 : this.currentSlide + 1;
                                                                            }, 8000);
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
                                                                    }" x-init="startAutoplay()"
                @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
                @foreach($allBanners as $index => $banner)
                    <div class="absolute inset-0 transition-opacity duration-700" x-show="currentSlide === {{ $index }}"
                        x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-700"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        style="{{ $index === 0 ? '' : 'display: none;' }}">

                        @if(is_array($banner) && isset($banner['type']) && $banner['type'] === 'static_grid')
                            <!-- Static Grid Banner -->
<!-- Static Banner Custom -->
<div class="relative w-full h-full bg-white overflow-hidden flex flex-col justify-center">

    <!-- Background Text Layer -->
    <div class="absolute inset-0 flex flex-col justify-center items-center select-none pointer-events-none whitespace-nowrap overflow-hidden z-0" style="line-height: 0.82;">
        <div class="text-rose-400 text-[60px] sm:text-[70px] md:text-[120px] lg:text-[184px] font-bold font-['Verona-Bold'] -ml-[17%]">
            AQLAYA CAKE
        </div>
        <div class="text-rose-400 text-[60px] sm:text-[70px] md:text-[120px] lg:text-[184px] font-bold font-['Verona-Bold'] ml-[5%]">
            <span class="tracking-[0.07em]">AQLAYA </span><span class="tracking-widest">C</span><span class="tracking-[0.07em]">AKE</span>
        </div>
        <div class="text-rose-400 text-[60px] sm:text-[70px] md:text-[120px] lg:text-[184px] font-bold font-['Verona-Bold'] -ml-[4%]">
            <span class="tracking-[0.07em]">AQLAYA CA</span><span class="tracking-[0.02em]">K</span><span class="tracking-[0.07em]">E</span>
        </div>
    </div>

    <!-- Main Image -->
    <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none mt-[2%]">
        <img 
            src="{{ asset('images/hero1.png') }}" 
            class="w-[204px] sm:w-[400px] md:w-[600px] lg:w-[614px] object-contain drop-shadow-2xl"
        >
    </div>

    <!-- Bottom Content -->
   <div class="absolute bottom-20 md:bottom-20 xl:bottom-14 w-full flex flex-col md:flex-row items-center justify-center gap-6 lg:gap-12 z-20">
        <!-- Tagline -->
        <span class="text-stone-300 text-2xl lg:text-4xl font-normal font-['Niva-Black-Italic']">
            Toko kue no 1 di Tegal
        </span>

        <!-- Button -->
        <a href="#catalog-grid"
            class="h-10 w-28 lg:h-14 lg:w-44 bg-rose-400 hover:bg-rose-500 text-white rounded-full text-lg lg:text-1xl font-normal font-['Plus_Jakarta_Sans'] transition shadow-lg flex items-center justify-center">
            Order Now
        </a>

    </div>

</div>
                        @elseif(is_array($banner) && isset($banner['type']) && $banner['type'] === 'static_list')
                            <!-- Static List Banner -->
                            <div class="relative w-full h-full bg-white overflow-hidden">
                                <!-- Background Image (Left) - Mirror position dari right_image di banner 1 -->
                                <img src="{{ asset($banner['background_image']) }}" alt="Background"
                                    class="absolute w-[47.78%] h-auto left-[-29%] top-[-4.14%] object-cover opacity-90 hidden md:block">

                                <!-- Main Content Area -->
                                <div class="absolute inset-0 h-full flex flex-col justify-center items-center px-6 sm:px-8 lg:px-12 w-full max-w-6xl mx-auto">
                                    <!-- Title -->
                                    <h2 class="text-3xl sm:text-5xl lg:text-6xl font-bold mb-6 sm:mb-12 text-gray-900 text-center"
                                        style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                        {{ $banner['title'] }}
                                    </h2>

                                    <!-- Categories Grid -->
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-2 gap-y-6 sm:gap-6 lg:gap-12 w-full">
                                        @foreach($banner['categories'] as $category)
                                            <div class="space-y-2 sm:space-y-4">
                                                <!-- Category Title -->
                                                <h3 class="text-lg sm:text-xl lg:text-2xl font-semibold text-pink-600 sm:text-gray-800 mb-1 sm:mb-4 border-b border-pink-100 pb-1 sm:border-none sm:pb-0"
                                                    style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                    {{ $category['name'] }}
                                                </h3>
                                                <!-- Product List -->
                                                <ul class="space-y-1 sm:space-y-2">
                                                    @foreach($category['items'] as $item)
                                                        <li class="text-[11px] sm:text-sm lg:text-base text-gray-600 leading-snug"
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

                <!-- Dots Indicator (Removed) -->
            </div>
        </div>
    </section>

    <!-- About Section -->
<section id="about" class="scroll-mt-32 bg-white py-24 lg:py-40 relative overflow-hidden">
    <div class="mx-auto max-w-[1000px] px-6 lg:px-12 text-center">
        
        <div class="flex flex-col items-center">
            <span class="text-[11px] uppercase tracking-[0.4em] text-zinc-400 font-bold mb-8 block">Authentic Taste</span>
            
            <h2 class="text-4xl lg:text-6xl font-bold text-zinc-900 leading-[1.1] mb-12 tracking-tight">
                Kue yang jujur lahir dari <br/>
                <span class="text-zinc-400 font-light italic serif">bahan-bahan pilihan.</span>
            </h2>
            
            <div class="max-w-2xl space-y-8 text-zinc-600 leading-relaxed text-lg lg:text-xl font-light">
                <p>
                    Di <strong>Aqlaya Cake</strong>, kami tidak menggunakan jalan pintas. Setiap adonan diolah dengan teliti menggunakan bahan premium untuk memastikan rasa yang konsisten dan memuaskan.
                </p>
                <p>
                    Bagi kami, sepotong kue bukan sekadar hidangan penutup, tapi sebuah cara untuk merayakan momen manis bersama orang-orang tersayang.
                </p>
            </div>

            <div class="mt-20 w-full pt-12 border-t border-zinc-100 flex flex-col items-center gap-6">
                <div class="text-center">
                    <p class="text-xs uppercase tracking-widest text-zinc-400 mb-2 font-semibold">Kunjungi Workshop Kami</p>
                    <p class="text-zinc-900 font-medium text-lg">Jl. KH. Ahmad Dahlan No. 33, Kota Tegal</p>
                </div>
                
                <a href="https://share.google/xhFZ1pTvAWPygYIVo" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="group inline-flex items-center gap-2 text-sm font-bold text-zinc-900 hover:text-zinc-500 transition-colors">
                    <span class="border-b-2 border-zinc-900 group-hover:border-zinc-500 pb-0.5">Petunjuk Lokasi</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</section>

    <!-- Best Sellers Dynamic Revolve Section -->
@if($bestSellerCarousel->isNotEmpty())
    <section class="relative bg-white overflow-hidden py-8 sm:py-16 lg:py-24 min-h-[500px] lg:min-h-[850px] flex items-center" 
        x-data="{
            activeIdx: 0,
            products: [],
            init() {
                this.products = JSON.parse(this.$refs.productsData.textContent);
            }
        }">
        
        @php
            $bsProductsData = $bestSellerCarousel->take(5)->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'excerpt' => $p->excerpt ?? 'Hidangan spesial pilihan terbaik Aqlaya Cake.',
                    'image' => $p->image_path ? asset('storage/' . $p->image_path) : ($p->image_url ?: asset('images/hero1.png')),
                ];
            })->values();
        @endphp
        <span x-ref="productsData" class="hidden">{!! json_encode($bsProductsData) !!}</span>

        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-[35vw] h-[85vh] bg-pink-600 rounded-l-[150px] lg:rounded-l-[400px] z-0 hidden lg:block"></div>
        <div class="absolute bottom-0 w-full h-[30vh] sm:h-[45vh] bg-pink-600 rounded-t-[100px] z-0 lg:hidden"></div>

        <div class="mx-auto max-w-[1400px] px-6 sm:px-8 lg:px-12 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-4 sm:gap-8 lg:gap-0">
                
                <div class="w-full lg:w-1/2 flex flex-col justify-center relative z-20 text-center lg:text-left mt-2 lg:mt-0">
                    <h2 class="order-1 text-4xl sm:text-6xl lg:text-8xl font-black text-gray-900 leading-[0.85] mb-2 sm:mb-6 lg:mb-12">
                        Today's<br/>
                        <span class="font-normal text-3xl sm:text-5xl lg:text-7xl lowercase">special</span>
                    </h2>

                    <div class="order-3 lg:order-2 relative h-20 sm:h-32 lg:h-52 flex justify-center lg:justify-start mt-2 lg:mt-0">
                        <template x-for="(product, index) in products" :key="index">
                            <div class="absolute inset-x-0 lg:inset-auto lg:left-0 lg:right-auto transition-all duration-700"
                                 x-show="activeIdx === index"
                                 x-transition:enter="transition ease-out delay-500 duration-500"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h3 class="text-xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-1 lg:mb-4 tracking-tight" x-text="product.name"></h3>
                                <p class="text-gray-500 text-sm sm:text-base lg:text-lg max-w-sm mx-auto lg:mx-0 leading-relaxed" x-text="product.excerpt"></p>
                            </div>
                        </template>
                    </div>

                    <div class="order-2 lg:order-3 flex items-center justify-center lg:justify-start gap-3 sm:gap-4 mt-1 lg:mt-12 overflow-x-auto pt-4 pb-2 lg:py-4 px-2 scrollbar-hide">
                        <template x-for="(product, index) in products" :key="index">
                            <button @click="activeIdx = index" class="group focus:outline-none flex flex-col items-center flex-shrink-0">
                                <div class="w-12 h-12 sm:w-16 sm:h-16 lg:w-20 lg:h-20 rounded-full p-1 border-2 transition-all duration-500"
                                     :class="activeIdx === index ? 'border-pink-600 scale-110 shadow-lg' : 'border-transparent opacity-40 hover:opacity-100'">
                                    <img :src="product.image" class="w-full h-full object-cover rounded-full">
                                </div>
                                <div class="mt-2 lg:mt-3 w-6 lg:w-8 h-1 transition-all duration-500" :class="activeIdx === index ? 'bg-pink-600' : 'bg-transparent'"></div>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 relative h-[250px] sm:h-[450px] lg:h-[750px] flex items-center justify-center lg:justify-end overflow-visible">
                    
                    <div class="relative w-[220px] h-[220px] sm:w-[320px] sm:h-[320px] lg:w-[600px] lg:h-[600px]">
                        
                        <template x-for="(product, index) in products" :key="index">
                            <div class="absolute inset-0 flex items-center justify-center transition-all"
                                 x-show="activeIdx === index"
                                 
                                 /* Animasi Swing: Piring seolah diputar masuk dari sisi luar lingkaran */
                                 x-transition:enter="transition duration-[1200ms] cubic-bezier(0.19, 1, 0.22, 1)"
                                 x-transition:enter-start="opacity-0 rotate-[90deg] translate-x-[150px] sm:translate-x-[300px] lg:translate-x-[500px] translate-y-[80px] lg:translate-y-[200px] scale-50"
                                 x-transition:enter-end="opacity-100 rotate-0 translate-x-0 translate-y-0 scale-100"
                                 
                                 x-transition:leave="transition duration-[800ms] ease-in"
                                 x-transition:leave-start="opacity-100 rotate-0 translate-x-0 scale-100"
                                 x-transition:leave-end="opacity-0 rotate-[-90deg] translate-x-[150px] sm:translate-x-[300px] lg:translate-x-[500px] translate-y-[-80px] lg:translate-y-[-200px] scale-50 blur-lg">
                                 
                                <div class="w-full h-full rounded-full bg-white border-[8px] sm:border-[12px] lg:border-[25px] border-white shadow-[0_20px_40px_rgba(0,0,0,0.3)] lg:shadow-[0_50px_100px_rgba(0,0,0,0.4)] overflow-hidden flex items-center justify-center">
                                     <img :src="product.image" class="w-full h-full object-cover rounded-full">
                                </div>

                            </div>
                        </template>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endif
    <section id="catalog-grid" class="scroll-mt-32 bg-mono-50/50 relative">
        <div class="mx-auto max-w-[1600px] px-5 py-16 sm:px-8 sm:py-20 lg:px-12 lg:py-28">
            <div class="js-catalog-desktop-stage relative">
                <div class="js-catalog-desktop-shell flex flex-col w-full z-20">
                    <div class="flex-shrink-0 bg-mono-50 pb-4 sm:pb-6" style="background-color: #fbfbfb; margin: 0 -1.25rem; padding-left: 1.25rem; padding-right: 1.25rem;">
                        <!-- Header -->
                        <div class="mb-6 lg:mb-8 pt-2">
                            <div class="text-left">
                                <p class="text-[10px] lg:text-xs uppercase tracking-[0.3em] text-pink-600 font-bold mb-1 sm:mb-2">Catalog</p>
                                <h2 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-light text-mono-900 leading-none">
                                    Koleksi Aqlaya
                                </h2>
                            </div>
                        </div>

                        <!-- Category and Search Row -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-2 border-b border-mono-200/60 pb-4">
                            
                            <!-- Category Tabs -->
                            <div class="flex items-center justify-start gap-2 overflow-x-auto scrollbar-hide w-full lg:w-auto flex-1">
                                <a href="{{ route('catalog') }}"
                                    class="whitespace-nowrap rounded-full px-4 py-1.5 sm:px-5 sm:py-2 text-[10px] sm:text-xs font-semibold uppercase tracking-wider transition-all {{ !request('category') ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-mono-500 hover:text-pink-600 hover:bg-pink-50 border border-mono-200' }}">
                                    Semua
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('catalog', ['category' => $category->slug]) }}"
                                        class="whitespace-nowrap rounded-full px-4 py-1.5 sm:px-5 sm:py-2 text-[10px] sm:text-xs font-semibold uppercase tracking-wider transition-all {{ request('category') === $category->slug ? 'bg-pink-600 text-white shadow-sm' : 'bg-white text-mono-500 hover:text-pink-600 hover:bg-pink-50 border border-mono-200' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>

                            <!-- Search Bar -->
                            <div class="w-full lg:w-auto shrink-0">
                                <form method="GET" action="{{ route('catalog') }}" class="relative group w-full sm:w-72 lg:w-80">
                                    @if(request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="w-full rounded-full border border-mono-200 bg-white py-2 pl-11 pr-4 text-sm text-mono-900 outline-none placeholder:text-mono-400 transition-all focus:border-pink-500 focus:ring-1 focus:ring-pink-500 shadow-sm"
                                        placeholder="Cari produk...">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-mono-400 group-focus-within:text-pink-500 transition-colors pointer-events-none">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6m2-5a7 7 0 11-14 0 7 7 0114 0z" />
                                        </svg>
                                    </div>
                                    <button type="submit" class="hidden">Cari</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div id="catalog-results-panel" class="flex-1 overflow-visible lg:overflow-hidden min-h-0 pt-4">
                        <div data-catalog-shell>
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
                                                        class="absolute left-3 top-3 rounded-full bg-pink-600 px-3 py-1 text-[10px] font-medium uppercase tracking-wider text-white shadow-sm">
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


                        </div>
                    </div>
                </div>
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
                        if (urlObj.pathname === catalogPath) {
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
                            const newContent = doc.querySelector('[data-catalog-shell]');
                            if (newContent && contentContainer) {
                                contentContainer.innerHTML = newContent.innerHTML;
                                contentContainer.style.opacity = '1';
                                contentContainer.style.pointerEvents = '';
                                window.history.pushState({ path: url }, '', url);
                                
                                // Re-bind parallax mechanics
                                if (window.bindCatalogResultImages) window.bindCatalogResultImages();
                                if (window.scheduleCatalogDesktopLayout) window.scheduleCatalogDesktopLayout();
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

            // PARALLAX DESKTOP CATALOG LAYOUT (Imported from Luwungragi paradigm)
            var resultsEl   = document.getElementById('catalog-results-panel');
            var siteHeaderEl = document.querySelector('nav');
            var catalogDesktopStageEl = document.querySelector('.js-catalog-desktop-stage');
            var catalogDesktopShellEl = document.querySelector('.js-catalog-desktop-shell');
            var catalogScrollFrame = null;
            var catalogLayoutFrame = null;
            var catalogDesktopState = {
                enabled: false,
                stickyTop: 0,
                maxInternalScroll: 0,
            };

            function isDesktopCatalogMode() {
                // Di Luwungragi ini dibatasi >= 1024,
                // Tapi untuk Aqlaya Cake kita aktifkan untuk SEMUA ukuran layar (termasuk mobile)
                return true;
            }

            function syncCatalogDesktopScroll() {
                if (!catalogDesktopState.enabled || !catalogDesktopStageEl || !resultsEl) return;
                var stageRect = catalogDesktopStageEl.getBoundingClientRect();
                var nextScrollTop = Math.max(
                    0,
                    Math.min(catalogDesktopState.stickyTop - stageRect.top, catalogDesktopState.maxInternalScroll)
                );
                resultsEl.scrollTop = nextScrollTop;
            }

            function applyCatalogDesktopLayout() {
                if (!catalogDesktopStageEl || !catalogDesktopShellEl || !resultsEl) return;

                if (!isDesktopCatalogMode()) {
                    catalogDesktopState.enabled = false;
                    catalogDesktopStageEl.style.height = '';
                    catalogDesktopShellEl.classList.remove('sticky');
                    catalogDesktopShellEl.style.top = '';
                    catalogDesktopShellEl.style.height = '';
                    resultsEl.style.overflow = 'visible';
                    resultsEl.overflowY = 'visible';
                    resultsEl.scrollTop = 0;
                    return;
                }

                var headerHeight = siteHeaderEl ? siteHeaderEl.offsetHeight : 80;
                var stickyTop = headerHeight; 
                
                // Gunakan innerHeight asli device untuk container, tapi jangan batasi minHeight
                var shellHeight = window.innerHeight - stickyTop;

                catalogDesktopShellEl.classList.add('sticky');
                catalogDesktopShellEl.style.top = stickyTop + 'px';
                catalogDesktopShellEl.style.height = shellHeight + 'px';
                resultsEl.style.overflow = 'hidden';
                resultsEl.style.overflowY = 'hidden'; // Ensure native touch scroll disables on inner child

                var maxInternalScroll = Math.max(resultsEl.scrollHeight - resultsEl.clientHeight, 0);

                catalogDesktopStageEl.style.height = (shellHeight + maxInternalScroll) + 'px';
                catalogDesktopState.enabled = true;
                catalogDesktopState.stickyTop = stickyTop;
                catalogDesktopState.maxInternalScroll = maxInternalScroll;

                syncCatalogDesktopScroll();
            }

            function scheduleCatalogDesktopLayout() {
                if (catalogLayoutFrame) cancelAnimationFrame(catalogLayoutFrame);
                catalogLayoutFrame = requestAnimationFrame(function () {
                    catalogLayoutFrame = null;
                    applyCatalogDesktopLayout();
                });
            }

            function scheduleCatalogDesktopScroll() {
                if (catalogScrollFrame) return;
                catalogScrollFrame = requestAnimationFrame(function () {
                    catalogScrollFrame = null;
                    syncCatalogDesktopScroll();
                });
            }

            function bindCatalogResultImages() {
                if (!resultsEl) return;
                resultsEl.querySelectorAll('img').forEach(function (img) {
                    if (img.complete) return;
                    img.addEventListener('load', scheduleCatalogDesktopLayout, { once: true });
                    img.addEventListener('error', scheduleCatalogDesktopLayout, { once: true });
                });
            }

            window.scheduleCatalogDesktopLayout = scheduleCatalogDesktopLayout;
            window.bindCatalogResultImages = bindCatalogResultImages;
            
            bindCatalogResultImages();
            scheduleCatalogDesktopLayout();
            window.addEventListener('load', scheduleCatalogDesktopLayout);
            window.addEventListener('resize', scheduleCatalogDesktopLayout);
            window.addEventListener('scroll', scheduleCatalogDesktopScroll, { passive: true });
        </script>
    @endpush
@endsection