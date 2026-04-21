@extends('layouts.app')

@section('title', 'Keranjang Belanja | Aqlaya Cake')

@section('content')
    <div class="mb-10 text-center lg:text-left">
        <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Keranjang Belanja</div>
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
            <h1 class="font-serif text-4xl sm:text-5xl font-medium text-stone-900 leading-[1.1]">Review kustomisasi<br
                    class="hidden sm:block"> sebelum checkout</h1>
            <a href="{{ route('catalog') }}"
                class="inline-flex items-center justify-center px-6 py-3 border-2 border-mint-leaf/20 text-mint-leaf rounded-xl text-sm font-bold tracking-wide hover:bg-mint-leaf hover:border-mint-leaf hover:text-white transition-all duration-300">
                Tambah Produk
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left: Cart Items List -->
        <div class="lg:col-span-8">
            <div class="flex flex-col gap-6">
                @forelse($cartItems as $itemId => $item)
                    <div
                        class="bg-white rounded-3xl p-4 sm:p-6 border border-stone-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.03)] hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row gap-6">
                            <!-- Image -->
                            <div class="sm:w-32 lg:w-40 flex-shrink-0">
                                <div class="relative w-full aspect-square rounded-2xl overflow-hidden bg-linen pt-2 px-2">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                        class="w-full h-full object-cover mix-blend-multiply rounded-xl">
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h2 class="font-serif text-2xl font-medium text-stone-900">{{ $item['name'] }}</h2>
                                        <div class="text-lg font-bold text-stone-900 shrink-0 ml-4">
                                            Rp{{ number_format($item['line_total'], 0, ',', '.') }}</div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 mt-4 text-sm text-stone-600">
                                        <div class="flex items-center gap-2">
                                            <span class="text-stone-400">Ukuran:</span>
                                            <span class="font-medium text-stone-800">{{ $item['size'] }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-stone-400">Jadwal:</span>
                                            <span class="font-medium text-stone-800">
                                                @if(!empty($item['scheduled_date']))
                                                    {{ \Carbon\Carbon::parse($item['scheduled_date'])->translatedFormat('d M Y') }}
                                                    {{ $item['scheduled_time'] ?: '' }}
                                                @else
                                                    <span class="text-mint-leaf font-semibold">Ready Stock</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <span class="text-stone-400 shrink-0">Ucapan:</span>
                                            <span
                                                class="font-medium text-stone-800 line-clamp-1">{{ $item['custom_message'] ?: '-' }}</span>
                                        </div>
                                        <div class="flex items-start gap-2">
                                            <span class="text-stone-400 shrink-0">Catatan:</span>
                                            <span
                                                class="font-medium text-stone-800 line-clamp-1">{{ $item['notes'] ?: '-' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-wrap items-end justify-between gap-4 mt-6 pt-4 border-t border-stone-100">
                                    <form method="POST" action="{{ route('cart.update', $itemId) }}"
                                        class="flex items-center gap-3">
                                        @csrf
                                        @method('PATCH')
                                        <div
                                            class="flex items-center border border-stone-200 rounded-lg bg-stone-50 overflow-hidden">
                                            <label class="sr-only">Jumlah</label>
                                            <span
                                                class="px-3 text-xs text-stone-500 font-medium border-r border-stone-200">Qty</span>
                                            <input type="number" name="quantity" min="1" value="{{ $item['quantity'] }}"
                                                class="w-16 h-8 px-2 text-center text-sm font-medium bg-transparent outline-none focus:bg-white transition-colors"
                                                onchange="this.form.submit()">
                                        </div>
                                        <noscript><button
                                                class="text-xs text-mint-leaf underline hover:text-stone-900">Update</button></noscript>
                                    </form>

                                    <form method="POST" action="{{ route('cart.destroy', $itemId) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="flex items-center text-sm font-medium text-red-500 hover:text-red-700 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-linen/50 border border-mint-leaf/20 border-dashed rounded-3xl p-12 text-center">
                        <div class="text-4xl mb-4">🛒</div>
                        <h3 class="font-serif text-2xl font-medium text-stone-900 mb-2">Keranjang masih kosong</h3>
                        <p class="text-stone-500 mb-6">Mulai eksplorasi katalog kami dan wujudkan cake impian Anda.</p>
                        <a href="{{ route('catalog') }}"
                            class="inline-flex items-center px-6 py-3 bg-mint-leaf text-white rounded-xl text-sm font-bold shadow-sm hover:bg-mint-leaf/90 transition-colors">
                            Lihat Katalog Produk
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Summary Sidebar -->
        <div class="lg:col-span-4">
            <div class="sticky top-24 bg-white rounded-[2rem] border border-stone-100 p-6 sm:p-8 shadow-sm">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Ringkasan</div>
                <h2 class="font-serif text-2xl font-medium text-stone-900 mb-6">Siap Lanjut Checkout</h2>

                <div class="flex flex-col gap-4 mb-6 text-sm text-stone-600">
                    <div class="flex justify-between items-center py-2 border-b border-stone-50">
                        <span>Subtotal Item</span>
                        <strong class="text-stone-900 text-base">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-stone-50">
                        <span>Biaya Pengantaran</span>
                        <span class="text-stone-400 italic text-xs">Dihitung di checkout</span>
                    </div>
                </div>

                <div class="bg-stone-50 p-4 rounded-xl border border-stone-100 mb-8 flex gap-3">
                    <svg class="w-5 h-5 text-mint-leaf shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-[11px] text-stone-500 leading-relaxed">
                        Tanggal dan jam setiap item sudah tersimpan. Di langkah checkout, Anda tinggal melengkapi opsi
                        pengiriman dan data kontak.
                    </p>
                </div>

                <a href="{{ route('checkout.create') }}"
                    class="w-full flex justify-center py-4 bg-stone-900 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-stone-800 transition-all duration-300 shadow-md @if($cartItems->isEmpty()) opacity-50 pointer-events-none @endif">
                    Lanjut Checkout
                </a>

                <a href="{{ route('catalog') }}"
                    class="w-full flex justify-center py-3 mt-3 bg-transparent text-stone-500 rounded-xl text-xs font-bold uppercase hover:text-stone-900 transition-colors">
                    Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
@endsection