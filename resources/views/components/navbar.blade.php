@props(['cartCount' => 0])

<nav class="fixed left-1/2 z-50 w-[calc(100%-2rem)] max-w-4xl -translate-x-1/2 top-4 sm:top-5 rounded-full bg-white/40 backdrop-blur-md border border-white/60 shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300" x-data="{
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
    }" @scroll.window="checkScroll()" x-init="setTimeout(() => checkScroll(), 100)">
    <div class="mx-auto w-full px-5 sm:px-6 lg:px-8">
        <div class="flex h-14 lg:h-16 items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center">
                <span class="font-logo text-2xl font-normal text-mono-900">Aqlaya Cake</span>
            </a>

            <div class="hidden items-center gap-5 lg:gap-8 md:flex">
                <div class="flex items-center gap-2 lg:gap-4 border-r border-mono-200/50 pr-6 lg:pr-8">
                    <a href="{{ route('home') }}" class="relative font-sans text-sm font-medium transition duration-300 px-4 py-2 rounded-full"
                        :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-pink-700 bg-white/70 shadow-sm' : 'text-mono-600 hover:text-mono-900 hover:bg-white/40'">
                        Home
                    </a>
                    <a href="{{ route('home') }}#about"
                        class="relative font-sans text-sm font-medium transition duration-300 px-4 py-2 rounded-full"
                        :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-pink-700 bg-white/70 shadow-sm' : 'text-mono-600 hover:text-mono-900 hover:bg-white/40'">
                        About
                    </a>
                    <a href="{{ route('home') }}#catalog-grid"
                        class="relative font-sans text-sm font-medium transition duration-300 px-4 py-2 rounded-full"
                        :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'text-pink-700 bg-white/70 shadow-sm' : 'text-mono-600 hover:text-mono-900 hover:bg-white/40'">
                        Collections
                    </a>
                </div>

                @auth
                    <div class="flex items-center gap-6">
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('cart.index') }}" class="relative font-sans text-sm font-normal transition"
                                :class="{{ request()->routeIs('cart.index') ? 'true' : 'false' }} ? 'text-mono-900' : 'text-mono-600 hover:text-mono-900'">
                                Cart
                                @if($cartCount > 0)
                                    <span
                                        class="absolute -right-3 -top-2 flex h-4 w-4 items-center justify-center rounded-full bg-pink-600 text-[10px] font-medium text-white shadow-sm">{{ $cartCount }}</span>
                                @endif
                            </a>
                        @endif

                        <div class="relative" x-data="{ open: false }" @click.away="open=false">
                            <button @click="open = !open"
                                class="flex items-center gap-2 font-sans text-sm font-medium text-mono-900 transition hover:text-mono-600">
                                <span>{{ explode(' ', trim(auth()->user()->name ?? auth()->user()->email))[0] }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 -translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 -translate-y-2" x-cloak
                                class="absolute right-0 top-full z-50 mt-4 w-48 origin-top-right rounded-2xl border border-white/60 bg-white/70 backdrop-blur-xl py-2 shadow-[0_8px_30px_rgb(0,0,0,0.12)]">
                                @if(auth()->user()->role === 'customer')
                                    <a href="{{ route('orders.index') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                                        Orders
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                                        Admin Panel
                                    </a>
                                @endif

                                <div class="mt-2 border-t border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full px-4 py-3 text-left text-sm font-medium text-red-600 transition hover:bg-red-50">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}"
                            class="px-5 py-2 font-sans text-sm font-medium text-mono-900 transition hover:text-mono-600">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-pink-600 px-7 py-2.5 font-sans text-sm font-medium text-white shadow-md transition duration-300 hover:bg-pink-700 hover:shadow-lg active:scale-95">
                            Register
                        </a>
                    </div>
                @endauth
            </div>

            <button @click="mobileOpen = !mobileOpen"
                class="inline-flex items-center justify-center p-2 text-mono-600 transition hover:text-mono-900 md:hidden">
                <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95" x-cloak class="absolute left-0 right-0 top-[calc(100%+0.5rem)] rounded-3xl border border-white/60 bg-white/70 backdrop-blur-xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] md:hidden overflow-hidden">
        <div class="space-y-1 px-6 py-6">
            <a href="{{ route('home') }}" class="block py-3 text-base font-normal transition"
                :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-mono-900 font-medium' : 'text-mono-600'">
                Home
            </a>
            <a href="{{ route('home') }}#about" class="block py-3 text-base font-normal transition"
                :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-mono-900 font-medium' : 'text-mono-600'">
                About
            </a>
            <a href="{{ route('home') }}#catalog-grid" class="block py-3 text-base font-normal transition"
                :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'text-mono-900 font-medium' : 'text-mono-600'">
                Collections
            </a>

            @auth
                <div class="mt-4 space-y-1 border-t border-gray-100 pt-4">
                    @if(auth()->user()->role === 'customer')
                        <a href="{{ route('cart.index') }}"
                            class="flex items-center justify-between py-3 text-base font-normal transition"
                            :class="{{ request()->routeIs('cart.index') ? 'true' : 'false' }} ? 'text-mono-900 font-medium' : 'text-mono-600'">
                            My Cart
                            @if($cartCount > 0)
                                <span class="rounded-full bg-pink-600 px-2.5 py-0.5 text-xs font-medium text-white">{{ $cartCount }}</span>
                            @endif
                        </a>

                        <a href="{{ route('orders.index') }}" class="block py-3 text-base font-normal transition"
                            :class="{{ request()->routeIs('orders.index') ? 'true' : 'false' }} ? 'text-mono-900 font-medium' : 'text-mono-600'">
                            My Orders
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block py-3 text-base font-medium text-mono-900 transition">
                            Admin Dashboard
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full py-3 text-left text-base font-medium text-red-600">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="mt-6 flex flex-col gap-3 border-t border-gray-100 pt-8">
                    <a href="{{ route('login') }}" 
                        class="flex h-12 items-center justify-center rounded-xl border border-mono-200 font-sans text-sm font-semibold text-mono-900 transition active:bg-gray-50">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                        class="flex h-12 items-center justify-center rounded-xl bg-pink-600 font-sans text-sm font-semibold text-white shadow-md transition active:bg-pink-700">
                        Register Account
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>