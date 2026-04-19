@extends('layouts.app')

@section('title', $product->name . ' | Aqlaya Cake')

@section('content')
    <div class="mb-8">
        <a href="{{ route('catalog') }}"
            class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali ke Katalog
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 mb-20">
        <!-- Left: Image & Details -->
        <div class="lg:col-span-7 flex flex-col gap-8">
            <!-- Main Image Card -->
            <div class="bg-white p-4 sm:p-6 lg:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm col-span-full" x-data="{
                        currentImage: 0,
                        images: [
                            @if($product->image_path)
                                '{{ asset('storage/' . $product->image_path) }}',
                            @endif
                            @if($product->image_path_2)
                                '{{ asset('storage/' . $product->image_path_2) }}',
                            @endif
                            @if($product->image_path_3)
                                '{{ asset('storage/' . $product->image_path_3) }}',
                            @endif
                        ].filter(Boolean).length > 0 ? [
                            @if($product->image_path)
                                '{{ asset('storage/' . $product->image_path) }}',
                            @endif
                            @if($product->image_path_2)
                                '{{ asset('storage/' . $product->image_path_2) }}',
                            @endif
                            @if($product->image_path_3)
                                '{{ asset('storage/' . $product->image_path_3) }}',
                            @endif
                        ].filter(Boolean) : ['{{ $product->image_url ?: asset('images/hero1.png') }}']
                    }">
                <!-- Main Image Display -->
                <div class="relative rounded-3xl overflow-hidden bg-linen aspect-[4/3] sm:aspect-[16/10] mb-4">
                    <template x-for="(image, index) in images" :key="index">
                        <img :src="image" alt="{{ $product->name }}"
                            class="absolute inset-0 w-full h-full object-cover mix-blend-multiply transition-all duration-500"
                            :class="currentImage === index ? 'opacity-100 scale-100' : 'opacity-0 scale-95'"
                            x-show="currentImage === index" x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    </template>
                </div>

                <!-- Thumbnail Navigation (only show if multiple images) -->
                <div x-show="images.length > 1" class="flex gap-3 mb-8 overflow-x-auto pb-2">
                    <template x-for="(image, index) in images" :key="index">
                        <button @click="currentImage = index"
                            class="flex-shrink-0 relative rounded-xl overflow-hidden bg-linen border-2 transition-all duration-300 w-20 h-20 sm:w-24 sm:h-24"
                            :class="currentImage === index ? 'border-mint-leaf shadow-md scale-105' : 'border-slate-200 hover:border-slate-300 opacity-70 hover:opacity-100'">
                            <img :src="image" alt="{{ $product->name }}"
                                class="w-full h-full object-cover mix-blend-multiply">
                        </button>
                    </template>
                </div>

                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span
                        class="px-3 py-1 text-xs font-semibold uppercase tracking-wider bg-linen text-slate-600 rounded-full border border-mint-leaf/20">{{ $product->category?->name ?? 'Cake Series' }}</span>
                    <div
                        class="flex items-center gap-1.5 px-3 py-1 bg-honey-bronze/20 text-slate-800 rounded-full border border-honey-bronze/30">
                        <span class="text-honey-bronze text-sm">★</span>
                        <span class="text-xs font-bold">{{ number_format($product->reviews_avg_rating ?? 0, 1) }}</span>
                        <span class="text-[10px] text-slate-500 font-medium">({{ $product->reviews_count }})</span>
                    </div>
                </div>

                <h1 class="font-serif text-4xl sm:text-5xl lg:text-6xl font-medium text-slate-900 mb-6 leading-[1.1]">
                    {{ $product->name }}
                </h1>
                <p class="text-lg text-slate-500 mb-8 leading-relaxed font-light">{{ $product->excerpt }}</p>

                <div class="prose prose-stone max-w-none text-slate-600 mb-10 leading-relaxed">
                    <p>{{ $product->description }}</p>
                </div>

                <!-- Highlights Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Harga Dasar</div>
                        <div class="text-xl font-medium text-slate-900">Rp{{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">Stok Tersedia</div>
                        <div class="text-xl font-medium text-slate-900">{{ $product->stock }} Item</div>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-1">
                            {{ $product->isPreOrder() ? 'Lead Time' : 'Ketersediaan' }}
                        </div>
                        <div class="text-xl font-medium text-slate-900">
                            @if($product->isPreOrder())
                                Min. H-{{ $product->lead_time_days }}
                            @else
                                Ready Stock
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Customization Form & Reviews -->
        <div class="lg:col-span-5 flex flex-col gap-8">
            <!-- Form Card -->
            <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Kustomisasi Pesanan</div>
                <h2 class="font-serif text-3xl font-medium text-slate-900 mb-8">Atur Detail Cake Anda</h2>

                @auth
                    @if(auth()->user()->role === 'customer')
                        <form method="POST" action="{{ route('cart.store', $product) }}" class="flex flex-col gap-5">
                            @csrf
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Ukuran</label>
                                    <select name="size"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition text-slate-700 appearance-none"
                                        required>
                                        <option value="">Pilih ukuran</option>
                                        @foreach($product->sizes ?? [] as $size)
                                            <option value="{{ $size }}" @selected(old('size') === $size)>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Jumlah</label>
                                    <input type="number" name="quantity" min="1" max="{{ $product->stock }}"
                                        value="{{ old('quantity', 1) }}"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition"
                                        required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Request
                                    Ucapan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                                <input type="text" name="custom_message" value="{{ old('custom_message') }}"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition"
                                    placeholder="Contoh: Happy Birthday Aya">
                            </div>

                            @if($product->isPreOrder())
                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Tanggal</label>
                                        <input type="date" name="scheduled_date" min="{{ $minimumOrderDate }}"
                                            value="{{ old('scheduled_date', $minimumOrderDate) }}"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition uppercase"
                                            required>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Jam</label>
                                        <input type="time" name="scheduled_time" value="{{ old('scheduled_time', '10:00') }}"
                                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition">
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wide mb-2">Catatan
                                    Tambahan <span class="text-slate-400 font-normal lowercase">(opsional)</span></label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition resize-none"
                                    placeholder="Contoh: warna krim dominan pink blush">{{ old('notes') }}</textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit"
                                    class="w-full py-4 bg-slate-900 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-honey-bronze hover:text-slate-900 transition-all duration-300 shadow-md transform hover:-translate-y-0.5">
                                    Masukkan ke Keranjang
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-200">
                            <div class="text-3xl mb-3">🔒</div>
                            <p class="text-slate-600 text-sm mb-2 font-semibold">Akun Admin</p>
                            <p class="text-slate-500 text-xs leading-relaxed">Akun admin tidak dapat melakukan pemesanan. Gunakan akun customer untuk memesan produk.</p>
                        </div>
                    @endif
                @else
                    <div class="bg-linen rounded-2xl p-6 text-center border border-mint-leaf/20">
                        <p class="text-slate-600 text-sm mb-6 leading-relaxed">Silakan masuk ke akun Anda untuk menyimpan cake
                            ini ke keranjang pesanan dan melanjutkan pre-order.</p>
                        <a href="{{ route('login') }}"
                            class="inline-block w-full py-3 bg-mint-leaf text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-mint-leaf/90 transition-all duration-300 shadow-sm">
                            Masuk untuk Memesan
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Reviews Section -->
            <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 sm:p-8 shadow-sm">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Rating & Ulasan</div>
                <h2 class="font-serif text-3xl font-medium text-slate-900 mb-6">Testimoni Pembeli</h2>

                <div class="flex flex-col gap-4">
                    @forelse($product->reviews->sortByDesc('created_at') as $review)
                        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <div class="font-semibold text-slate-900 text-sm mb-1">{{ $review->user->name }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $review->created_at->translatedFormat('d F Y') }}
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 bg-white px-2 py-1 rounded border border-slate-200">
                                    <span class="text-honey-bronze text-xs">★</span>
                                    <span class="text-xs font-bold text-slate-700">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                            <p class="text-sm text-slate-600 mt-3 leading-relaxed">
                                {{ $review->review ?: 'Customer memberikan rating memuaskan tanpa komentar tambahan.' }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="text-3xl text-slate-300 mb-2">✧</div>
                            <div class="text-sm text-slate-500">Belum ada ulasan. Jadilah pembeli pertama!</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->isNotEmpty())
        <section class="mb-12">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Kategori Serupa</div>
                    <h2 class="font-serif text-3xl font-medium text-slate-900">Mungkin Anda Suka</h2>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('products.show', $related) }}"
                        class="group bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col">
                        <div class="relative overflow-hidden rounded-2xl mb-4 bg-linen aspect-[4/3]">
                            <img src="{{ $related->image_path ? asset('storage/' . $related->image_path) : ($related->image_url ?: asset('images/hero1.png')) }}"
                                alt="{{ $related->name }}"
                                class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105">
                        </div>
                        <h3 class="font-serif text-xl font-medium text-slate-900 mb-2 truncate group-hover:text-mint-leaf transition-colors">
                            {{ $related->name }}
                        </h3>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 whitespace-normal">{{ $related->excerpt }}</p>
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <strong class="text-slate-900 text-lg">Rp{{ number_format($related->price, 0, ',', '.') }}</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
@endsection