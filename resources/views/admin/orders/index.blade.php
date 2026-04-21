@extends('layouts.admin')

@section('title', 'Pesanan — Admin Aqlaya Cake')
@section('page-title', 'Pesanan')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-slate-900">Kelola Pesanan</h2>
        <p class="text-sm text-slate-500 mt-1">Semua pesanan yang masuk ke Aqlaya Cake</p>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 mb-6">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Status Pesanan</label>
                <select name="status" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    <option value="">Semua status</option>
                    @foreach([\App\Models\Order::STATUS_PENDING_PAYMENT, \App\Models\Order::STATUS_AWAITING_CONFIRMATION, \App\Models\Order::STATUS_PROCESSING, \App\Models\Order::STATUS_READY, \App\Models\Order::STATUS_COMPLETED, \App\Models\Order::STATUS_REJECTED] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Status Pembayaran</label>
                <select name="payment_status" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                    <option value="">Semua pembayaran</option>
                    @foreach([\App\Models\Order::PAYMENT_UNPAID, \App\Models\Order::PAYMENT_PAID, \App\Models\Order::PAYMENT_REFUNDED] as $paymentStatus)
                        <option value="{{ $paymentStatus }}" @selected(request('payment_status') === $paymentStatus)>{{ str($paymentStatus)->replace('_', ' ')->title() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 transition shrink-0">
                Filter
            </button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-6 py-3.5">Customer</th>
                        <th class="px-6 py-3.5">Jadwal</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Total</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-3.5 font-medium text-slate-800">{{ $order->order_code }}</td>
                            <td class="px-6 py-3.5 text-slate-600">{{ $order->customer_name }}</td>
                            <td class="px-6 py-3.5 text-slate-500">{{ optional($order->scheduled_for)->translatedFormat('d M Y H:i') }}</td>
                            <td class="px-6 py-3.5">
                                @php
                                    $statusColors = [
                                        'pending_payment' => 'bg-slate-100 text-slate-600',
                                        'awaiting_confirmation' => 'bg-amber-50 text-amber-700',
                                        'processing' => 'bg-blue-50 text-blue-700',
                                        'ready' => 'bg-emerald-50 text-emerald-700',
                                        'completed' => 'bg-green-50 text-green-700',
                                        'rejected' => 'bg-red-50 text-red-600',
                                    ];
                                    $color = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">{{ $order->statusLabel() }}</span>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                    Detail
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-sm text-slate-400">Belum ada pesanan yang cocok dengan filter.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
