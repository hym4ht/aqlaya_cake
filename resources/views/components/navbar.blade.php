@props(['cartCount' => 0])

<nav class="fixed inset-x-0 top-0 z-50 border-b border-black/10 bg-[#f5efe7]/82 backdrop-blur-xl transition-all duration-300"
    x-data="{
        mobileOpen: false,
        activeSection: 'home',
        checkScroll() {
            let sections = ['about', 'catalog-grid'];
            let current = 'home';
            for (let s of sections) {
                let el = document.getElementById(s);
                if (el && el.getBoundingClientRect().top <= 200) {
                    current = s;
                }
            }
            if (window.scrollY < 100) current = 'home';
            this.activeSection = current;
        }
    }"
    @scroll.window="checkScroll()"
    x-init="setTimeout(() => checkScroll(), 100)">
    <div class="mx-auto h-[4.75rem] w-full max-w-[1600px] px-5 sm:px-8 lg:h-24 lg:px-12">
        <div class="flex h-full items-center justify-between gap-4 lg:gap-8">
            <a href="{{ route('home') }}" class="inline-flex items-center">
                <span class="font-serif text-2xl font-semibold uppercase tracking-[0.18em] text-black sm:text-[1.7rem]">Aqlaya</span>
            </a>

            <div class="ml-auto hidden items-center gap-8 md:flex lg:gap-12">
                <a href="{{ route('home') }}"
                    class="border-b pb-1 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                    :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/50 hover:border-black/30 hover:text-black'">
                    Home
                </a>
                <a href="{{ route('home') }}#about"
                    class="border-b pb-1 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                    :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/50 hover:border-black/30 hover:text-black'">
                    Studio
                </a>
                <a href="{{ route('home') }}#catalog-grid"
                    class="border-b pb-1 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                    :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/50 hover:border-black/30 hover:text-black'">
                    Catalog
                </a>
            </div>

            <div class="flex items-center gap-2 md:pl-4">
                @auth
                    <a href="{{ route('cart.index') }}"
                        class="relative inline-flex h-10 w-10 items-center justify-center border border-black/10 transition {{ request()->routeIs('cart.index') ? 'bg-black text-white' : 'text-black/70 hover:border-black/20 hover:bg-black/[0.03]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span id="cart-count-badge"
                            class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-black text-[10px] font-semibold text-white {{ $cartCount > 0 ? '' : 'hidden' }}">{{ $cartCount }}</span>
                    </a>

                    <div class="relative hidden md:block" x-data="{ borderOpen: false }" @click.away="borderOpen=false">
                        <button @click="borderOpen = !borderOpen"
                            class="inline-flex items-center gap-3 border px-3 py-2 transition"
                            :class="borderOpen ? 'border-black bg-black text-white' : 'border-black/10 text-black/80 hover:border-black/20 hover:bg-black/[0.03]'">
                            <div class="flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold"
                                :class="borderOpen ? 'bg-white text-black' : 'bg-black text-white'">
                                {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                            </div>
                            <span class="hidden text-sm font-medium lg:block">{{ explode(' ', trim(auth()->user()->name ?? auth()->user()->email))[0] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="borderOpen" x-transition x-cloak
                            class="absolute right-0 top-full z-50 mt-2 w-52 border border-black/10 bg-[#f5efe7] py-2 shadow-xl">
                            <a href="{{ route('orders.index') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-sm transition {{ request()->routeIs('orders.index') ? 'bg-black text-white' : 'text-black/70 hover:bg-black/[0.04]' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Pesanan
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-2.5 text-sm text-black/70 transition hover:bg-black/[0.04]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                    </svg>
                                    Admin
                                </a>
                            @endif

                            <div class="mt-1 border-t border-black/10 pt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 transition hover:bg-red-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="hidden items-center gap-2 md:flex">
                        <a href="{{ route('login') }}"
                            class="px-3 py-2 text-[11px] uppercase tracking-[0.18em] text-black/70 transition hover:text-black">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="border border-black bg-black px-4 py-2 text-[11px] uppercase tracking-[0.18em] text-white transition hover:bg-black/85">Daftar</a>
                    </div>
                @endauth

                <button @click="mobileOpen = !mobileOpen"
                    class="inline-flex h-10 w-10 items-center justify-center border border-black/10 transition hover:border-black/20 hover:bg-black/[0.03] md:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black/70" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak
        class="max-h-[calc(100vh-5rem)] overflow-y-auto border-t border-black/10 bg-[#f5efe7] shadow-lg md:hidden">
        <div class="space-y-2 px-5 py-4">
            <a href="{{ route('home') }}"
                class="block border-b px-1 py-3 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/60'">
                Home
            </a>
            <a href="{{ route('home') }}#about"
                class="block border-b px-1 py-3 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/60'">
                Studio
            </a>
            <a href="{{ route('home') }}#catalog-grid"
                class="block border-b px-1 py-3 font-serif text-[12px] uppercase tracking-[0.24em] transition"
                :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'border-black text-black' : 'border-transparent text-black/60'">
                Catalog
            </a>

            <div class="mt-4 border-t border-black/10 pt-4">
                @auth
                    <a href="{{ route('cart.index') }}"
                        class="flex items-center gap-3 px-1 py-3 text-sm font-medium transition {{ request()->routeIs('cart.index') ? 'text-black' : 'text-black/70' }}">
                        Keranjang
                        @if($cartCount > 0)
                            <span class="ml-auto rounded-full bg-black px-2 py-0.5 text-[10px] text-white">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('orders.index') }}"
                        class="block px-1 py-3 text-sm font-medium transition {{ request()->routeIs('orders.index') ? 'text-black' : 'text-black/70' }}">
                        Pesanan
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-1 py-3 text-sm font-medium text-black/70 transition">
                            Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full px-1 py-3 text-left text-sm font-medium text-red-600 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-1 py-3 text-sm font-medium text-black/70 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="mt-2 block border border-black bg-black px-4 py-3 text-center text-[11px] uppercase tracking-[0.18em] text-white transition hover:bg-black/85">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
