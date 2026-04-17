@props(['cartCount' => 0])

<nav class="fixed inset-x-0 top-0 z-50 bg-white/95 backdrop-blur-sm transition-all duration-300" x-data="{
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
    <div class="mx-auto w-full max-w-[1600px] px-6 sm:px-12 lg:px-16">
        <div class="flex h-20 items-center justify-between">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="inline-flex items-center">
                <span class="font-logo text-3xl font-normal text-gray-900">Aqlaya Cake</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-10 md:flex">
                <a href="{{ route('home') }}" class="relative font-sans text-sm font-normal transition duration-200"
                    :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-gray-900 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-gray-900 after:content-[\'\']' : 'text-gray-600 hover:text-gray-900'">
                    Home
                </a>
                <a href="{{ route('home') }}#about"
                    class="relative font-sans text-sm font-normal transition duration-200"
                    :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-gray-900 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-gray-900 after:content-[\'\']' : 'text-gray-600 hover:text-gray-900'">
                    About
                </a>
                <a href="{{ route('home') }}#catalog-grid"
                    class="relative font-sans text-sm font-normal transition duration-200"
                    :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'text-gray-900 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-gray-900 after:content-[\'\']' : 'text-gray-600 hover:text-gray-900'">
                    Collections
                </a>

                @auth
                    <a href="{{ route('cart.index') }}" class="relative font-sans text-sm font-normal transition"
                        :class="{{ request()->routeIs('cart.index') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600 hover:text-gray-900'">
                        Cart
                        @if($cartCount > 0)
                            <span
                                class="absolute -right-2 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-gray-900 text-[10px] font-medium text-white">{{ $cartCount }}</span>
                        @endif
                    </a>

                    <div class="relative" x-data="{ open: false }" @click.away="open=false">
                        <button @click="open = !open"
                            class="font-sans text-sm font-normal text-gray-600 transition hover:text-gray-900">
                            {{ explode(' ', trim(auth()->user()->name ?? auth()->user()->email))[0] }}
                        </button>

                        <div x-show="open" x-transition x-cloak
                            class="absolute right-0 top-full z-50 mt-2 w-48 bg-white py-2 shadow-lg">
                            <a href="{{ route('orders.index') }}"
                                class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                                Orders
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-50">
                                    Admin
                                </a>
                            @endif

                            <div class="border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full px-4 py-2 text-left text-sm text-gray-700 transition hover:bg-gray-50">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="font-sans text-sm font-normal text-gray-600 transition hover:text-gray-900">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="font-sans text-sm font-normal text-gray-600 transition hover:text-gray-900">
                        Register
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen"
                class="inline-flex items-center justify-center p-2 text-gray-600 transition hover:text-gray-900 md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" x-cloak class="border-t border-gray-100 bg-white shadow-lg md:hidden">
        <div class="space-y-1 px-6 py-4">
            <a href="{{ route('home') }}" class="block py-2 text-sm font-normal transition"
                :class="activeSection === 'home' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600'">
                Home
            </a>
            <a href="{{ route('home') }}#about" class="block py-2 text-sm font-normal transition"
                :class="activeSection === 'about' && {{ request()->routeIs('home') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600'">
                About
            </a>
            <a href="{{ route('home') }}#catalog-grid" class="block py-2 text-sm font-normal transition"
                :class="(activeSection === 'catalog-grid' && {{ request()->routeIs('home') ? 'true' : 'false' }}) || {{ request()->routeIs('catalog') || request('search') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600'">
                Collections
            </a>

            @auth
                <a href="{{ route('cart.index') }}"
                    class="flex items-center justify-between py-2 text-sm font-normal transition"
                    :class="{{ request()->routeIs('cart.index') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600'">
                    Cart
                    @if($cartCount > 0)
                        <span class="rounded-full bg-gray-900 px-2 py-0.5 text-xs text-white">{{ $cartCount }}</span>
                    @endif
                </a>

                <a href="{{ route('orders.index') }}" class="block py-2 text-sm font-normal transition"
                    :class="{{ request()->routeIs('orders.index') ? 'true' : 'false' }} ? 'text-gray-900' : 'text-gray-600'">
                    Orders
                </a>

                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-sm font-normal text-gray-600 transition">
                        Admin
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 pt-2">
                    @csrf
                    <button type="submit" class="w-full py-2 text-left text-sm font-normal text-gray-600 transition">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm font-normal text-gray-600 transition">
                    Login
                </a>
                <a href="{{ route('register') }}" class="block py-2 text-sm font-normal text-gray-600 transition">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>