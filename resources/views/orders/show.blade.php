@extends('layouts.app')

@section('title', $order->order_code . ' | Aqlaya Cake')

@section('content')
    <div class="mb-10 text-center lg:text-left flex flex-col lg:flex-row lg:items-end justify-between gap-6">
        <div>
            <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Detail Pesanan</div>
            <h1 class="font-serif text-4xl sm:text-5xl font-medium text-stone-900 leading-[1.1]">{{ $order->order_code }}</h1>
            <p class="text-stone-500 mt-3 font-medium">Dibuat pada {{ $order->created_at->translatedFormat('d M Y H:i') }}</p>
        </div>
        
        <div>
            @php
                $statusColors = [
                    'pending_payment' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'awaiting_confirmation' => 'bg-sky-100 text-sky-800 border-sky-200',
                    'processing' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                    'shipped' => 'bg-fuchsia-100 text-fuchsia-800 border-fuchsia-200',
                    'ready' => 'bg-teal-100 text-teal-800 border-teal-200',
                    'completed' => 'bg-mint-leaf text-white border-mint-leaf',
                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                ];
                $statusClass = $statusColors[$order->status] ?? 'bg-stone-100 text-stone-600 border-stone-200';
            @endphp
            <span class="inline-flex px-4 py-2 text-sm font-bold tracking-widest uppercase rounded-full border {{ $statusClass }}">
                {{ $order->statusLabel() }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left Column: Details & Items -->
        <div class="lg:col-span-8 flex flex-col gap-8">
            <!-- Payment & Shipping Overview -->
            <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-stone-100 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div>
                        <div class="text-xs text-stone-400 font-bold uppercase tracking-wider mb-2">Pembayaran</div>
                        <div class="text-lg font-medium text-stone-900">{{ $order->paymentLabel() }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-stone-400 font-bold uppercase tracking-wider mb-2">Pengiriman</div>
                        <div class="text-lg font-medium text-stone-900">{{ $order->shipping_method === 'delivery' ? 'Antar ke Alamat' : 'Ambil di Toko' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-stone-400 font-bold uppercase tracking-wider mb-2">Total Tagihan</div>
                        <div class="text-xl font-bold text-stone-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
                
                @if($order->rejection_reason)
                    <div class="mt-8 bg-red-50 border border-red-100 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">!</div>
                        <div>
                            <div class="font-bold text-red-800 mb-1">Pesanan Dibatalkan</div>
                            <p class="text-sm text-red-600">{{ $order->rejection_reason }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-stone-100 shadow-sm">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Item Pesanan</div>
                <h2 class="font-serif text-3xl font-medium text-stone-900 mb-8">Produksi Per Item</h2>
                
                <div class="flex flex-col gap-6">
                    @foreach($order->items as $item)
                        <div class="bg-stone-50 rounded-2xl p-6 border border-stone-100">
                            <div class="flex justify-between items-start gap-4 mb-4">
                                <div>
                                    <h3 class="font-serif text-xl font-medium text-stone-900 mb-1">{{ $item->product_name }}</h3>
                                    <div class="text-sm text-stone-600 font-medium">
                                        {{ $item->quantity }}x <span class="mx-1">•</span> {{ $item->size }} <span class="mx-1">•</span> 
                                        {{ \Carbon\Carbon::parse($item->scheduled_date)->translatedFormat('d F Y') }} {{ $item->scheduled_time ?: '' }}
                                    </div>
                                </div>
                                <strong class="text-stone-900 text-lg">Rp{{ number_format($item->line_total, 0, ',', '.') }}</strong>
                            </div>
                            
                            <div class="text-sm text-stone-500 bg-white p-4 rounded-xl border border-stone-100 flex flex-col gap-1">
                                <div><span class="font-semibold text-stone-700">Ucapan:</span> <span class="italic">{{ $item->custom_message ?: '-' }}</span></div>
                                <div><span class="font-semibold text-stone-700">Catatan:</span> {{ $item->notes ?: '-' }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Review Section (If Completed) -->
            @if($order->status === \App\Models\Order::STATUS_COMPLETED)
                <div class="bg-white rounded-[2.5rem] p-6 sm:p-10 border border-mint-leaf/20 shadow-[0_4px_20px_-4px_rgba(61,130,92,0.1)]">
                    <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Ulasan Setelah Selesai</div>
                    <h2 class="font-serif text-3xl font-medium text-stone-900 mb-6">Beri Rating Pesanan</h2>
                    
                    <div class="flex flex-col gap-8">
                        @php $hasUnreviewed = false; @endphp
                        @foreach($order->items as $item)
                            @if($item->product && !in_array($item->product_id, $reviewedProductIds, true))
                                @php $hasUnreviewed = true; @endphp
                                <form method="POST" action="{{ route('orders.reviews.store', [$order, $item->product]) }}" class="bg-linen/50 rounded-2xl p-6 border border-mint-leaf/10">
                                    @csrf
                                    <div class="font-serif text-xl font-medium text-stone-900 mb-4">{{ $item->product_name }}</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                        <div class="sm:col-span-1">
                                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Rating</label>
                                            <select name="rating" class="w-full px-4 py-3 bg-white border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-mint-leaf/50 outline-none transition" required>
                                                <option value="">Pilih</option>
                                                @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} Bintang</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Ulasan Singkat</label>
                                            <div class="flex gap-2">
                                                <input type="text" name="review" class="w-full px-4 py-3 bg-white border border-stone-200 rounded-xl text-sm focus:ring-2 focus:ring-mint-leaf/50 outline-none transition" placeholder="Sampaikan pendapat Anda">
                                                <button type="submit" class="px-6 py-3 bg-stone-900 text-white font-bold text-sm tracking-wider uppercase rounded-xl hover:bg-honey-bronze hover:text-stone-900 transition-colors shrink-0">Kirim</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        @endforeach
                        
                        @if(!$hasUnreviewed)
                            <div class="text-stone-500 text-center py-4 bg-stone-50 rounded-2xl border border-stone-100">
                                ⭐ Anda telah meninjau semua produk dalam pesanan ini. Terima kasih!
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            {{-- Midtrans Snap Payment (Real Gateway) --}}
            @if($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT && $snapToken)
                <div class="bg-stone-900 rounded-[2rem] p-6 sm:p-8 text-white relative overflow-hidden shadow-lg">
                    <!-- Decor -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-mint-leaf rounded-full opacity-20 blur-2xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-honey-bronze rounded-full opacity-10 blur-xl"></div>
                    
                    <div class="relative z-10">
                        <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Pembayaran Midtrans</div>
                        <h2 class="font-serif text-2xl font-medium mb-4">Bayar Sekarang</h2>
                        <p class="text-sm text-stone-300 mb-6 leading-relaxed">
                            Klik tombol di bawah untuk membuka halaman pembayaran Midtrans. Pilih metode pembayaran yang Anda inginkan (QRIS, Transfer Bank, GoPay, ShopeePay, dll).
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mb-6">
                            <span class="inline-flex items-center gap-1 bg-white/10 rounded-lg px-3 py-1.5 text-xs font-semibold text-white/80">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Aman & Terenkripsi
                            </span>
                            <span class="inline-flex items-center gap-1 bg-white/10 rounded-lg px-3 py-1.5 text-xs font-semibold text-white/80">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Verifikasi Otomatis
                            </span>
                        </div>

                        <button type="button" id="pay-button"
                            class="w-full py-4 bg-mint-leaf text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-mint-leaf/90 transition-all shadow-md flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Bayar via Midtrans
                        </button>

                        <p class="text-[10px] text-stone-500 mt-3 text-center">
                            Total: <strong class="text-white">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        </p>
                    </div>
                </div>

            {{-- Simulation Mode (Local Dev Only) --}}
            @elseif($order->status === \App\Models\Order::STATUS_PENDING_PAYMENT && !$isMidtransConfigured)
                <div class="bg-stone-900 rounded-[2rem] p-6 sm:p-8 text-white relative overflow-hidden shadow-lg">
                    <!-- Decor -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-400 rounded-full opacity-20 blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="text-[11px] font-bold tracking-widest text-amber-400 uppercase mb-2">Mode Simulasi</div>
                        <h2 class="font-serif text-2xl font-medium mb-4">Pembayaran Lokal</h2>
                        <p class="text-sm text-stone-300 mb-4 leading-relaxed">
                            Midtrans belum dikonfigurasi. Gunakan tombol di bawah untuk menyimulasikan pembayaran secara lokal.
                        </p>
                        
                        <div class="bg-amber-400/10 border border-amber-400/20 rounded-xl p-3 mb-6 text-xs text-amber-200 flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            <span>Isi <code class="bg-white/10 px-1 rounded">MIDTRANS_SERVER_KEY</code> dan <code class="bg-white/10 px-1 rounded">MIDTRANS_CLIENT_KEY</code> di file .env untuk mengaktifkan gateway sungguhan.</span>
                        </div>
                        
                        <form method="POST" action="{{ route('orders.simulate-payment', $order) }}">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-amber-500 text-stone-900 rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-amber-400 transition-all shadow-md">
                                Simulasikan Pembayaran
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-stone-100 shadow-sm">
                <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Informasi Kontak</div>
                <h2 class="font-serif text-2xl font-medium text-stone-900 mb-6">Penerima Pesanan</h2>
                
                <div class="flex flex-col gap-5 text-sm">
                    <div>
                        <div class="text-stone-400 text-xs font-bold uppercase tracking-wider mb-1">Nama</div>
                        <div class="font-medium text-stone-900">{{ $order->customer_name }}</div>
                    </div>
                    <div>
                        <div class="text-stone-400 text-xs font-bold uppercase tracking-wider mb-1">Email</div>
                        <div class="font-medium text-stone-900">{{ $order->customer_email }}</div>
                    </div>
                    <div>
                        <div class="text-stone-400 text-xs font-bold uppercase tracking-wider mb-1">WhatsApp</div>
                        <div class="font-medium text-stone-900">{{ $order->customer_phone }}</div>
                    </div>
                    <div class="pt-4 border-t border-stone-100">
                        <div class="text-stone-400 text-xs font-bold uppercase tracking-wider mb-1">Tujuan / Alamat</div>
                        <div class="font-medium text-stone-800 leading-relaxed bg-stone-50 p-3 rounded-xl border border-stone-100 mt-2">
                            {{ $order->shipping_method === 'delivery' ? $order->delivery_address : 'Pesanan akan diambil langsung oleh pelanggan ke toko (Pickup).' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Midtrans Reference Info --}}
            @if($order->midtrans_reference)
                <div class="bg-white rounded-[2rem] p-6 sm:p-8 border border-stone-100 shadow-sm">
                    <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Info Pembayaran</div>
                    <h2 class="font-serif text-2xl font-medium text-stone-900 mb-4">Referensi Transaksi</h2>
                    <div class="text-sm text-stone-600 flex flex-col gap-2">
                        <div class="flex justify-between">
                            <span class="text-stone-400">Reference</span>
                            <span class="font-mono font-medium text-stone-800 text-xs bg-stone-50 px-2 py-1 rounded">{{ $order->midtrans_reference }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-stone-400">Dibayar</span>
                            <span class="font-medium text-stone-800">{{ optional($order->paid_at)->translatedFormat('d M Y H:i') ?: '-' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@if($snapToken && $snapJsUrl && $clientKey)
<script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const payButton = document.getElementById('pay-button');
        if (!payButton) return;

        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    // Payment success — reload to see updated status
                    window.location.reload();
                },
                onPending: function (result) {
                    // Payment is pending
                    window.location.reload();
                },
                onError: function (result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    console.error('Payment error:', result);
                },
                onClose: function () {
                    // User closed the payment popup
                    console.log('Payment popup closed');
                }
            });
        });
    });
</script>
@endif
@endpush
