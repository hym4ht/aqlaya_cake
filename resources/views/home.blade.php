@extends('layouts.app')

@section('title', 'Aqlaya Cake | Curated Patisserie Landing')

@section('content')
    @php
        $heroProduct = $bestSellers->first() ?? $products->first();
        $heroBanner = $banners->first();
        $heroImage = $heroBanner?->image_path
            ? asset('storage/' . $heroBanner->image_path)
            : ($heroProduct?->image_url ?: asset('images/hero.png'));
        $heroTitle = $heroBanner?->title ?: 'Aqlaya Cake';
        $heroSubtitle = $heroBanner?->subtitle ?: 'Koleksi kue dan dessert table dengan rasa yang hangat, styling yang rapi, dan presentasi yang terasa seperti editorial spread.';
        $minimumOrderLabel = \Illuminate\Support\Carbon::parse($minimumOrderDate)->locale('id')->translatedFormat('d M Y');
        $signatureProducts = $bestSellers
            ->concat($products->getCollection())
            ->unique('id')
            ->take(6)
            ->values();
    @endphp

    <section class="border-b border-black/10 pt-28 sm:pt-32 lg:pt-36">
        <div class="mx-auto max-w-[1600px] px-5 pb-12 sm:px-8 sm:pb-14 lg:px-12 lg:pb-16">
            <div class="mb-6">
                @include('partials.flash')
            </div>

            <div class="overflow-hidden rounded-[28px] bg-[#111111] text-white">
                <div class="grid lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="flex min-h-[26rem] flex-col justify-between px-6 py-8 sm:px-10 sm:py-10 lg:min-h-[36rem] lg:px-14 lg:py-14">
                        <div>
                            <span class="sr-only">Sistem pemesanan kue</span>
                            <p class="text-[11px] uppercase tracking-[0.34em] text-white/60">Curated Patisserie</p>
                            <h1 class="mt-6 max-w-[11ch] font-serif text-5xl font-semibold uppercase leading-[0.86] tracking-[-0.04em] text-white sm:text-6xl lg:text-[5.5rem]">
                                {{ $heroTitle }}
                            </h1>
                            <p class="mt-6 max-w-xl text-sm leading-7 text-white/72 sm:text-base">
                                {{ $heroSubtitle }}
                            </p>
                        </div>

                        <div class="mt-10 flex flex-col gap-8">
                            <div class="flex flex-wrap gap-3">
                                <a href="#catalog-grid" class="bg-white px-6 py-3 text-[11px] uppercase tracking-[0.24em] text-black transition hover:bg-white/90">
                                    Lihat katalog
                                </a>
                                <a href="#about" class="border border-white/20 px-6 py-3 text-[11px] uppercase tracking-[0.24em] text-white transition hover:border-white/45">
                                    Tentang studio
                                </a>
                            </div>

                            <div class="grid gap-4 border-t border-white/10 pt-5 text-[11px] uppercase tracking-[0.2em] text-white/58 sm:grid-cols-3">
                                <div>
                                    <p class="text-white/40">Pre-order</p>
                                    <p class="mt-2 text-white">{{ $minimumOrderLabel }}</p>
                                </div>
                                <div>
                                    <p class="text-white/40">Best Seller</p>
                                    <p class="mt-2 text-white">{{ $heroProduct?->name ?? 'Aqlaya Selection' }}</p>
                                </div>
                                <div>
                                    <p class="text-white/40">Location</p>
                                    <p class="mt-2 text-white">Pekalongan, Jawa Tengah</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative min-h-[22rem] border-t border-white/10 lg:min-h-full lg:border-l lg:border-t-0">
                        <img
                            src="{{ $heroImage }}"
                            alt="{{ $heroTitle }}"
                            class="absolute inset-0 h-full w-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/10 to-black/5"></div>
                        <div class="absolute right-5 top-5 h-3.5 w-3.5 bg-white mix-blend-difference sm:right-6 sm:top-6"></div>

                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6 lg:p-8">
                            <div class="border-t border-white/15 pt-4">
                                <div class="flex items-end justify-between gap-4 border-b border-white/15 pb-3">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.22em] text-white/55">{{ $heroProduct?->category?->name ?? 'Signature Cake' }}</p>
                                        <h2 class="mt-2 font-serif text-2xl font-semibold uppercase text-white sm:text-3xl">
                                            {{ $heroProduct?->name ?? 'Aqlaya Signature' }}
                                        </h2>
                                    </div>
                                    @if($heroProduct)
                                        <span class="shrink-0 text-[10px] uppercase tracking-[0.26em] text-white/72">
                                            Rp{{ number_format($heroProduct->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-3 max-w-md text-sm leading-6 text-white/70">
                                    {{ $heroProduct?->excerpt ?? 'Presentasi yang tenang, clean, dan elegan untuk hadiah maupun momen spesial.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="scroll-mt-32 border-b border-black/10 bg-[#efe7dd]">
        <div class="mx-auto max-w-[1600px] px-5 py-14 sm:px-8 sm:py-16 lg:px-12 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-end">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.32em] text-black/45">Editorial Landing</p>
                    <h2 class="mt-5 max-w-[10ch] font-serif text-[clamp(3.8rem,10vw,8.5rem)] font-semibold uppercase leading-[0.82] tracking-[-0.06em] text-black">
                        Aqlaya<br>
                        <span class="font-normal italic normal-case">cake</span>
                    </h2>
                </div>
                <div class="lg:pb-3">
                    <p class="text-sm leading-7 text-black/65">
                        Tema halaman ini dibentuk seperti katalog editorial: ruang kosong yang lega, kontras hitam-putih yang kuat, dan fokus pada foto produk agar tiap koleksi terasa lebih eksklusif.
                    </p>
                    <div class="mt-6 h-px w-24 bg-black/25"></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[1600px] px-5 py-12 sm:px-8 sm:py-16 lg:px-12 lg:py-20">
        <div class="mb-10 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[11px] uppercase tracking-[0.3em] text-black/45">Signature Selection</p>
                <h2 class="mt-4 font-serif text-3xl font-semibold text-black sm:text-4xl lg:text-5xl">
                    Koleksi utama dengan presentasi yang clean.
                </h2>
            </div>
            <a href="#catalog-grid" class="text-[11px] uppercase tracking-[0.24em] text-black/55 transition hover:text-black">
                Explore catalog
            </a>
        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-14 md:grid-cols-2 xl:grid-cols-3">
            @foreach($signatureProducts as $product)
                <article class="group">
                    <a href="{{ route('products.show', $product) }}" class="block">
                        <div class="relative overflow-hidden bg-[#ece6dc]">
                            <img
                                src="{{ $product->image_url ?: asset('images/hero.png') }}"
                                alt="{{ $product->name }}"
                                class="aspect-[4/5] h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                            >
                            <div class="absolute right-4 top-4 h-3.5 w-3.5 {{ $loop->odd ? 'bg-black' : 'bg-white mix-blend-difference' }}"></div>
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-4 border-b border-black/10 pb-3">
                            <div class="min-w-0">
                                <p class="mb-2 text-[10px] uppercase tracking-[0.24em] text-black/45">{{ $product->category?->name ?? 'Cake Series' }}</p>
                                <h3 class="truncate font-serif text-2xl font-semibold uppercase text-black">
                                    {{ $product->name }}
                                </h3>
                            </div>
                            <span class="shrink-0 text-[10px] uppercase tracking-[0.24em] text-black/65">
                                Rp{{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <p class="mt-3 text-sm leading-6 text-black/62">
                            {{ $product->excerpt }}
                        </p>
                    </a>
                </article>
            @endforeach
        </div>
    </section>

    <section class="border-y border-black/10 bg-[#f1ece5]">
        <div class="mx-auto flex max-w-[1200px] flex-col items-center px-5 py-16 text-center sm:px-8 lg:px-12 lg:py-24">
            <div class="max-w-4xl">
                <p class="font-serif text-3xl leading-tight text-black sm:text-4xl lg:text-5xl">
                    "Kami tidak hanya membuat kue, tetapi menyusun momen yang terasa tenang, hangat, dan layak diingat dari tampilan pertamanya."
                </p>
            </div>
            <p class="mt-6 text-[11px] uppercase tracking-[0.28em] text-black/55">Aqlaya Cake Studio</p>
        </div>
    </section>

    <section id="catalog-grid" class="scroll-mt-32">
        <div class="mx-auto max-w-[1600px] px-5 py-12 sm:px-8 sm:py-16 lg:px-12 lg:py-20">
            <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(320px,420px)] lg:items-end">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-black/45">Full Catalog</p>
                    <h2 class="mt-4 font-serif text-3xl font-semibold text-black sm:text-4xl lg:text-5xl">
                        Jelajahi seluruh koleksi Aqlaya.
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-black/62">
                        Gunakan filter untuk menemukan kue, dessert box, atau hadiah manis yang paling cocok untuk acara Anda.
                    </p>
                </div>

                <form method="GET" action="{{ route('catalog') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_220px_auto]">
                    <label class="block">
                        <span class="sr-only">Cari produk</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            class="w-full border border-black/15 bg-transparent px-4 py-3 text-sm text-black outline-none transition placeholder:text-black/35 focus:border-black/35"
                            placeholder="Cari kue, pastry, atau gift set"
                        >
                    </label>
                    <label class="block">
                        <span class="sr-only">Pilih kategori</span>
                        <select name="category" class="w-full border border-black/15 bg-transparent px-4 py-3 text-sm text-black outline-none transition focus:border-black/35">
                            <option value="">Semua kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="bg-black px-5 py-3 text-[11px] uppercase tracking-[0.22em] text-white transition hover:bg-black/85">
                        Filter
                    </button>
                </form>
            </div>

            <div data-catalog-shell class="mt-10">
                <div class="flex flex-wrap gap-2 border-b border-black/10 pb-6">
                    <a href="{{ route('catalog') }}" class="border px-3 py-2 text-[10px] uppercase tracking-[0.22em] transition {{ !request('category') ? 'border-black bg-black text-white' : 'border-black/10 text-black/60 hover:border-black/25 hover:text-black' }}">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('catalog', ['category' => $category->slug]) }}" class="border px-3 py-2 text-[10px] uppercase tracking-[0.22em] transition {{ request('category') === $category->slug ? 'border-black bg-black text-white' : 'border-black/10 text-black/60 hover:border-black/25 hover:text-black' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>

                <div class="mt-10 grid grid-cols-2 gap-x-4 gap-y-10 md:grid-cols-3 xl:grid-cols-4">
                    @forelse($products as $product)
                        <article class="group">
                            <a href="{{ route('products.show', $product) }}" class="block">
                                <div class="relative overflow-hidden bg-[#ece6dc]">
                                    <img
                                        src="{{ $product->image_url ?: asset('images/hero.png') }}"
                                        alt="{{ $product->name }}"
                                        class="aspect-[4/5] h-full w-full object-cover transition duration-700 group-hover:scale-[1.03]"
                                    >
                                    <div class="absolute right-3 top-3 h-3 w-3 {{ $product->is_best_seller ? 'bg-black' : 'bg-white mix-blend-difference' }}"></div>
                                </div>

                                <div class="mt-4 border-b border-black/10 pb-3">
                                    <p class="text-[10px] uppercase tracking-[0.22em] text-black/45">{{ $product->category?->name ?? 'Cake' }}</p>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <h3 class="line-clamp-2 font-serif text-lg font-semibold uppercase text-black sm:text-2xl">
                                            {{ $product->name }}
                                        </h3>
                                        <span class="shrink-0 text-[10px] uppercase tracking-[0.2em] text-black/60">
                                            Rp{{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <p class="line-clamp-2 text-sm leading-6 text-black/60">{{ $product->excerpt }}</p>
                                    <p class="shrink-0 text-[10px] uppercase tracking-[0.18em] text-black/38">{{ $product->reviews_count }} review</p>
                                </div>
                            </a>
                        </article>
                    @empty
                        <div class="col-span-full border border-black/10 px-6 py-12 text-center text-sm leading-7 text-black/60">
                            Tidak ada produk yang sesuai dengan pencarian Anda saat ini.
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="mt-12 flex justify-center border-t border-black/10 pt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            catalogGrid.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;

                try {
                    const urlObj = new URL(link.href);
                    if (urlObj.pathname === catalogPath || link.href.includes('?page=')) {
                        e.preventDefault();
                        fetchCatalog(link.href);
                    }
                } catch (err) {}
            });

            catalogGrid.addEventListener('submit', function(e) {
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
                        catalogGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    } else {
                        window.location.href = url;
                    }
                })
                .catch(error => {
                    console.error('Error fetching catalog:', error);
                    window.location.href = url;
                });
            }

            window.addEventListener('popstate', function(e) {
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
