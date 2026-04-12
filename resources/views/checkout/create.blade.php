@extends('layouts.app')

@section('title', 'Checkout | Aqlaya Cake')

@section('content')
    <div class="mb-10 text-center lg:text-left">
        <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Proses Checkout</div>
        <h1 class="font-serif text-4xl sm:text-5xl font-medium text-stone-900 leading-[1.1]">Lengkapi data pengantaran<br class="hidden sm:block"> atau pengambilan</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
        <!-- Left: Form -->
        <div class="lg:col-span-7">
            <div class="bg-white border border-stone-100 rounded-[2.5rem] p-6 sm:p-10 shadow-sm">
                <form method="POST" action="{{ route('checkout.store') }}" class="flex flex-col gap-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-b border-stone-100 pb-8">
                        <div class="sm:col-span-2">
                            <h3 class="font-serif text-2xl font-medium text-stone-800 mb-1">Data Kontak</h3>
                            <p class="text-sm text-stone-500 mb-4">Informasi untuk mengkonfirmasi pesanan.</p>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Nama Penerima</label>
                            <input type="text" name="customer_name" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition" value="{{ old('customer_name', $user->name) }}" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Nomor WhatsApp</label>
                            <input type="text" name="customer_phone" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition" value="{{ old('customer_phone', $user->phone) }}" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Email</label>
                            <input type="email" name="customer_email" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition" value="{{ old('customer_email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2">
                        <div class="sm:col-span-2">
                            <h3 class="font-serif text-2xl font-medium text-stone-800 mb-1">Pengiriman</h3>
                            <p class="text-sm text-stone-500 mb-4">Pilih metode pengambilan cake pesanan Anda.</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Metode Pengiriman</label>
                            <div class="relative">
                                <select name="shipping_method" id="shipping_method" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition appearance-none font-medium cursor-pointer" required>
                                    <option value="pickup" @selected(old('shipping_method', 'pickup') === 'pickup')>Ambil di Toko (Pickup)</option>
                                    <option value="delivery" @selected(old('shipping_method') === 'delivery')>Antar ke Alamat (Delivery)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-stone-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2" id="delivery_address_wrapper" style="display: none;">
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Alamat Lengkap</label>
                            <textarea name="delivery_address" rows="3" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition resize-none" placeholder="Isi detail alamat lengkap, patokan jalan, dan warna pagar rumah">{{ old('delivery_address', $user->address) }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Catatan Order <span class="text-stone-400 font-normal lowercase">(opsional)</span></label>
                            <textarea name="order_notes" rows="3" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition resize-none" placeholder="Contoh: kirim setelah jam 15.00 atau segera hubungi jika sudah sampai">{{ old('order_notes') }}</textarea>
                        </div>

                        <div class="sm:col-span-2 mt-4">
                            <button type="submit" class="w-full py-4 bg-stone-900 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-honey-bronze hover:text-stone-900 transition-all duration-300 shadow-md transform hover:-translate-y-0.5">
                                Buat Pesanan & Lanjut Pembayaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-5">
            <div class="sticky top-24 bg-linen rounded-[2.5rem] border border-mint-leaf/20 p-6 sm:p-8 shadow-[0_4px_20px_-4px_rgba(61,130,92,0.1)]">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Order Summary</div>
                <h2 class="font-serif text-3xl font-medium text-stone-900 mb-8">Ringkasan Item</h2>
                
                <div class="flex flex-col gap-4 mb-8 max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($cartItems as $item)
                        <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100 relative">
                            <span class="absolute -top-2 -right-2 w-6 h-6 flex items-center justify-center bg-stone-900 text-white text-[10px] font-bold rounded-full border-2 border-white shadow-sm">{{ $item['quantity'] }}</span>
                            <div class="flex justify-between items-start mb-2 pr-4">
                                <h3 class="font-serif text-lg font-medium text-stone-900">{{ $item['name'] }}</h3>
                                <div class="font-bold text-stone-900 text-sm whitespace-nowrap">Rp{{ number_format($item['line_total'], 0, ',', '.') }}</div>
                            </div>
                            <div class="text-xs text-stone-500 leading-relaxed font-medium">
                                Ukuran {{ $item['size'] }} <span class="mx-1">•</span> 
                                {{ \Carbon\Carbon::parse($item['scheduled_date'])->translatedFormat('d M Y') }} {{ $item['scheduled_time'] ?: '' }}<br>
                                @if($item['custom_message'])
                                    Ucapan: <span class="italic">"{{ $item['custom_message'] }}"</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-mint-leaf/20 pt-6">
                    <div class="flex justify-between items-center mb-3 text-stone-600">
                        <span>Subtotal Produk</span>
                        <strong class="text-stone-900">Rp{{ number_format($subtotal, 0, ',', '.') }}</strong>
                    </div>
                    
                    <div class="bg-white/50 p-3 rounded-lg border border-mint-leaf/10 mb-4 flex items-start gap-2">
                        <svg class="w-4 h-4 text-mint-leaf shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-[10px] text-stone-500 font-medium">
                            Total pembayaran dapat berubah. Biaya antar sebesar Rp25.000 akan otomatis ditambahkan jika Anda memilih opsi pengiriman "Antar ke Alamat".
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
