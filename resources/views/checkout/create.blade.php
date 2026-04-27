@extends('layouts.app')

@section('title', 'Checkout | Aqlaya Cake')

@section('content')
    <div class="mb-4 lg:mb-6 text-center lg:text-left">
        <div class="text-[10px] font-bold tracking-widest text-pink-600 uppercase mb-1">Proses Checkout</div>
        <h1 class="font-serif text-2xl sm:text-3xl font-medium text-stone-900 leading-[1.2]">Lengkapi data pengantaran<br class="hidden sm:block"> atau pengambilan</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        <!-- Left: Form -->
        <div class="lg:col-span-7">
            <div class="bg-white border border-stone-100 rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm">
                <form method="POST" action="{{ route('checkout.store') }}" class="flex flex-col gap-4">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 border-b border-stone-100 pb-5">
                        <div class="sm:col-span-2">
                            <h3 class="font-serif text-xl font-medium text-stone-800 mb-1">Data Kontak</h3>
                            <p class="text-[11px] sm:text-xs text-stone-500 mb-2">Informasi untuk mengkonfirmasi pesanan.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Nama Penerima</label>
                            <input type="text" name="customer_name" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition" value="{{ old('customer_name', $user->name) }}" required>
                        </div>
                        <div>
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Nomor WhatsApp</label>
                            <input type="text" name="customer_phone" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition" value="{{ old('customer_phone', $user->phone) }}" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Email</label>
                            <input type="email" name="customer_email" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition" value="{{ old('customer_email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                        <div class="sm:col-span-2">
                            <h3 class="font-serif text-xl font-medium text-stone-800 mb-1">Pengiriman</h3>
                            <p class="text-[11px] sm:text-xs text-stone-500 mb-2">Pilih metode pengambilan cake pesanan Anda.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Metode Pengiriman</label>
                            <div class="relative">
                                <select name="shipping_method" id="shipping_method" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition appearance-none font-medium cursor-pointer" required>
                                    <option value="pickup" @selected(old('shipping_method', 'pickup') === 'pickup')>Ambil di Toko (Pickup)</option>
                                    <option value="delivery" @selected(old('shipping_method') === 'delivery')>Antar ke Alamat (Delivery)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2" id="delivery_address_wrapper" style="display: none;">
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Alamat Lengkap</label>
                            <textarea name="delivery_address" rows="3" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition resize-none" placeholder="Isi detail alamat lengkap..."></textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-xs font-semibold text-stone-700 uppercase tracking-wide mb-1.5">Catatan Order <span class="text-stone-400 font-normal lowercase">(opsional)</span></label>
                            <textarea name="order_notes" rows="2" class="w-full px-3 py-2.5 bg-stone-50 border border-stone-200 rounded-lg text-xs sm:text-sm focus:bg-white focus:ring-2 focus:ring-pink-500/50 focus:border-pink-500 outline-none transition resize-none" placeholder="Kirim setelah jam 15.00...">{{ old('order_notes') }}</textarea>
                        </div>

                        <div class="sm:col-span-2 mt-2">
                            <button type="submit" class="w-full py-2.5 sm:py-3 bg-pink-600 text-white rounded-lg text-xs sm:text-sm font-bold tracking-wide uppercase hover:bg-pink-700 transition-all duration-300 shadow-md transform hover:-translate-y-0.5">
                                Buat Pesanan & Lanjut Bayar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-5">
            <div class="sticky top-20 bg-pink-50 rounded-xl sm:rounded-2xl border border-pink-200/50 p-4 sm:p-6 shadow-sm">
                <div class="text-[10px] font-bold tracking-widest text-pink-600 uppercase mb-1">Order Summary</div>
                <h2 class="font-serif text-xl font-medium text-stone-900 mb-4">Ringkasan Item</h2>
                
                <div class="flex flex-col gap-3 mb-5 max-h-[35vh] overflow-y-auto pt-2 pr-4 pb-1 pl-1 -ml-1 custom-scrollbar">
                    @foreach($cartItems as $item)
                        <div class="bg-white p-3 rounded-xl shadow-sm border border-stone-100 relative">
                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 flex items-center justify-center bg-pink-600 text-white text-[9px] font-bold rounded-full border border-white shadow-sm">{{ $item['quantity'] }}</span>
                            <div class="flex gap-3">
                                <!-- Product Image -->
                                <div class="w-16 h-16 flex-shrink-0">
                                    <div class="relative w-full h-full rounded-lg overflow-hidden bg-linen">
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover mix-blend-multiply">
                                    </div>
                                </div>
                                
                                <!-- Product Details -->
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-start mb-1.5 pr-2">
                                        <h3 class="font-serif text-sm font-semibold text-stone-900 line-clamp-1">{{ $item['name'] }}</h3>
                                        <div class="font-bold text-stone-900 text-[13px] whitespace-nowrap ml-2">Rp{{ number_format($item['line_total'], 0, ',', '.') }}</div>
                                    </div>
                                    <div class="text-[10px] text-stone-500 leading-relaxed font-medium">
                                        Ukuran {{ $item['size'] }} <span class="mx-1">•</span> 
                                        {{ \Carbon\Carbon::parse($item['scheduled_date'])->translatedFormat('d M Y') }} {{ $item['scheduled_time'] ?: '' }}<br>
                                        @if($item['custom_message'])
                                            Ucapan: <span class="italic">"{{ $item['custom_message'] }}"</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-pink-200/50 pt-4">
                    <div class="flex justify-between items-center mb-2 text-[13px] text-stone-600">
                        <span>Subtotal Produk</span>
                        <strong class="text-stone-900 text-sm">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    
                    <div class="bg-white/50 p-2.5 rounded-lg border border-pink-100 mb-2 flex items-start gap-1.5">
                        <svg class="w-3.5 h-3.5 text-pink-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[9px] text-stone-500 font-medium leading-tight">
                            Total pembayaran dapat berubah. Biaya antar sebesar Rp25.000 berlaku jika Anda memilih "Antar ke Alamat".
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shippingSelect = document.getElementById('shipping_method');
        const addressWrapper = document.getElementById('delivery_address_wrapper');
        const addressTextarea = addressWrapper.querySelector('textarea');

        const syncAddressVisibility = () => {
            if (shippingSelect.value === 'delivery') {
                addressWrapper.style.display = 'block';
                addressTextarea.required = true;
                addressTextarea.value = '{{ old('delivery_address', $user->address) }}';
            } else {
                addressWrapper.style.display = 'none';
                addressTextarea.required = false;
            }
        };

        shippingSelect.addEventListener('change', syncAddressVisibility);
        syncAddressVisibility(); // Init on load
    });
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d6d3d1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a8a29e; }
</style>
@endpush
