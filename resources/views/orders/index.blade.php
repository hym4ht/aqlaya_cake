@extends('layouts.app')

@section('title', 'Pesanan Saya | Aqlaya Cake')

@section('content')
    <!-- Header -->
    <div class="mb-12 flex items-end justify-between">
        <div>
            <div class="text-xs font-semibold text-stone-400 uppercase tracking-wide mb-2">Pesanan Saya</div>
            <h1 class="font-serif text-4xl font-medium text-stone-900">Riwayat Belanja</h1>
        </div>
        <a href="{{ route('catalog') }}" class="px-5 py-2.5 border border-stone-300 text-stone-700 rounded-lg text-sm font-medium hover:bg-pink-600 hover:text-white hover:border-pink-600 transition-all">
            Pesan Lagi
        </a>
    </div>

    <!-- Orders Grid -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-2xl p-6 border border-stone-200 hover:border-stone-300 transition-all">
                <!-- Header -->
                <div class="flex items-start justify-between mb-5">
                    <div>
                        <div class="text-xs text-stone-400 mb-1">{{ $order->created_at->translatedFormat('d M Y') }}</div>
                        <h2 class="font-semibold text-lg text-stone-900">{{ $order->order_code }}</h2>
                    </div>
                    
                    @php
                        $statusColors = [
                            'pending_payment'        => 'bg-amber-50 text-amber-700',
                            'awaiting_confirmation'  => 'bg-blue-50 text-blue-700',
                            'processing'             => 'bg-indigo-50 text-indigo-700',
                            'ready'                  => 'bg-teal-50 text-teal-700',
                            'completed'              => 'bg-green-50 text-green-700',
                            'rejected'               => 'bg-red-50 text-red-700',
                        ];
                        $statusClass = $statusColors[$order->status] ?? 'bg-stone-50 text-stone-700';
                    @endphp
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusClass }}">
                        {{ $order->statusLabel() }}
                    </span>
                </div>

                <!-- Info Compact -->
                <div class="flex items-center gap-6 text-sm text-stone-600 mb-5 pb-5 border-b border-stone-100">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span>{{ $order->shipping_method === 'delivery' ? 'Diantar' : 'Diambil' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        <span>{{ $order->paymentLabel() }}</span>
                    </div>
                    @if($order->scheduled_for)
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ $order->scheduled_for->translatedFormat('d M') }}</span>
                    </div>
                    @endif
                </div>

                <!-- Items -->
                <div class="space-y-2 mb-5">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between text-sm">
                            <div class="text-stone-700">
                                <span class="font-medium">{{ $item->product_name }}</span>
                                <span class="text-stone-400 mx-2">•</span>
                                <span class="text-stone-500">{{ $item->size }}</span>
                            </div>
                            <div class="text-stone-500">{{ $item->quantity }}x</div>
                        </div>
                    @endforeach
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between pt-5 border-t border-stone-100">
                    <div class="text-lg font-semibold text-stone-900">
                        Rp{{ number_format($order->total_amount, 0, ',', '.') }}
                    </div>
                    <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition-colors">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-stone-50 border border-stone-200 border-dashed rounded-2xl p-16 text-center">
                <div class="text-5xl mb-4">📦</div>
                <h3 class="font-serif text-xl font-medium text-stone-900 mb-2">Belum ada pesanan</h3>
                <p class="text-stone-500 mb-6">Mulai belanja sekarang</p>
                <a href="{{ route('catalog') }}" class="inline-flex items-center px-5 py-2.5 bg-pink-600 text-white rounded-lg text-sm font-medium hover:bg-pink-700 transition-colors">
                    Lihat Katalog
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-8 flex items-center justify-center gap-3">
            <a href="{{ $orders->previousPageUrl() }}" class="px-4 py-2 border border-stone-200 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors @if(!$orders->previousPageUrl()) opacity-40 pointer-events-none @endif">
                ← Prev
            </a>
            <span class="text-sm text-stone-500">{{ $orders->currentPage() }} / {{ $orders->lastPage() }}</span>
            <a href="{{ $orders->nextPageUrl() }}" class="px-4 py-2 border border-stone-200 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors @if(!$orders->nextPageUrl()) opacity-40 pointer-events-none @endif">
                Next →
            </a>
        </div>
    @endif
@endsection