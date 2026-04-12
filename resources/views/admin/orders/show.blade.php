@extends('layouts.admin')

@section('title', $order->order_code . ' — Admin Aqlaya Cake')
@section('page-title', 'Detail Pesanan')

@section('content')
    {{-- Back link --}}
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali ke Daftar Pesanan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- LEFT (2 cols) --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Order header card --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ $order->order_code }}</h2>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $order->customer_name }} • {{ $order->customer_phone }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending_payment' => 'bg-slate-100 text-slate-600',
                            'awaiting_confirmation' => 'bg-amber-50 text-amber-700 border border-amber-200',
                            'processing' => 'bg-blue-50 text-blue-700 border border-blue-200',
                            'ready' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                            'completed' => 'bg-green-50 text-green-700 border border-green-200',
                            'rejected' => 'bg-red-50 text-red-600 border border-red-200',
                        ];
                        $color = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color }} shrink-0">{{ $order->statusLabel() }}</span>
                </div>

                {{-- 3 mini cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400 mb-1">Pembayaran</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $order->paymentLabel() }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400 mb-1">Metode</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $order->shipping_method === 'delivery' ? 'Antar ke Alamat' : 'Ambil di Toko' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400 mb-1">Total</p>
                        <p class="text-sm font-semibold text-slate-800">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>

                @if($order->delivery_address)
                    <div class="mt-4 flex items-start gap-2.5 p-3.5 bg-slate-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-sm text-slate-600">{{ $order->delivery_address }}</p>
                    </div>
                @endif
            </div>

            {{-- Items table --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-800">Rincian Item</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Jadwal produksi per item pesanan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3">Ukuran</th>
                                <th class="px-6 py-3">Jadwal</th>
                                <th class="px-6 py-3">Qty</th>
                                <th class="px-6 py-3">Ucapan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-medium text-slate-800">{{ $item->product_name }}</td>
                                    <td class="px-6 py-3.5 text-slate-600">{{ $item->size }}</td>
                                    <td class="px-6 py-3.5 text-slate-500">{{ \Carbon\Carbon::parse($item->scheduled_date)->translatedFormat('d M Y') }} {{ $item->scheduled_time ?: '' }}</td>
                                    <td class="px-6 py-3.5 text-slate-600">{{ $item->quantity }}</td>
                                    <td class="px-6 py-3.5 text-slate-500 italic">{{ $item->custom_message ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT (1 col) --}}
        <div class="space-y-6">
            {{-- Admin Decision --}}
            @if($order->status === \App\Models\Order::STATUS_AWAITING_CONFIRMATION)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-1">Konfirmasi Admin</h3>
                    <p class="text-xs text-slate-400 mb-5">Terima atau tolak pesanan ini</p>

                    <form method="POST" action="{{ route('admin.orders.decide', $order) }}" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="decision" value="accept">
                        <button class="w-full py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            Terima & Mulai Produksi
                        </button>
                    </form>

                    <div class="relative mb-4">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100"></div></div>
                        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs text-slate-400">atau</span></div>
                    </div>

                    <form method="POST" action="{{ route('admin.orders.decide', $order) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="decision" value="reject">
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Alasan Penolakan</label>
                        <textarea name="reason" rows="3" class="w-full rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition resize-none" placeholder="Contoh: slot produksi penuh"></textarea>
                        <button class="w-full mt-3 py-2.5 rounded-xl border border-red-200 text-red-600 text-sm font-medium hover:bg-red-50 transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            Tolak & Proses Refund
                        </button>
                    </form>
                </div>
            @endif

            {{-- Update Status --}}
            @if($order->status === \App\Models\Order::STATUS_PROCESSING || $order->status === \App\Models\Order::STATUS_READY)
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-sm font-semibold text-slate-800 mb-1">Update Status</h3>
                    <p class="text-xs text-slate-400 mb-5">Ubah status produksi pesanan</p>

                    @if($order->status === \App\Models\Order::STATUS_PROCESSING)
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ \App\Models\Order::STATUS_READY }}">
                            <button class="w-full py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                Tandai Siap Diambil/Diantar
                            </button>
                        </form>
                    @endif

                    @if($order->status === \App\Models\Order::STATUS_READY)
                        <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ \App\Models\Order::STATUS_COMPLETED }}">
                            <button class="w-full py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Tandai Pesanan Selesai
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Midtrans info --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <h3 class="text-sm font-semibold text-slate-800 mb-1">Info Pembayaran</h3>
                <p class="text-xs text-slate-400 mb-4">Referensi Midtrans</p>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Reference</span>
                        <span class="text-sm font-medium text-slate-700 font-mono">{{ $order->midtrans_reference ?: '—' }}</span>
                    </div>
                    <div class="border-t border-slate-50"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Dibayar</span>
                        <span class="text-sm text-slate-600">{{ optional($order->paid_at)->translatedFormat('d M Y H:i') ?: '—' }}</span>
                    </div>
                    <div class="border-t border-slate-50"></div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-400">Selesai</span>
                        <span class="text-sm text-slate-600">{{ optional($order->completed_at)->translatedFormat('d M Y H:i') ?: '—' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
