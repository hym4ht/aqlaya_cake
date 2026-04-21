@extends('layouts.admin')

@section('title', 'Dashboard — ​Admin Aqlaya Cake')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Greeting --}}
    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-slate-900">Selamat datang kembali 👋</h2>
        <p class="text-sm text-slate-500 mt-1">Pantau dapur, pesanan, dan pemasukan Aqlaya Cake.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {{-- Today Revenue --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Pendapatan Hari Ini</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>

        {{-- Month Revenue --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Pendapatan Bulan Ini</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp{{ number_format($monthRevenue, 0, ',', '.') }}</p>
        </div>

        {{-- Awaiting Confirmation --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Menunggu Konfirmasi</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $productionSummary[\App\Models\Order::STATUS_AWAITING_CONFIRMATION] ?? 0 }}</p>
        </div>

        {{-- Processing --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Sedang Diproses</span>
                <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.83.006l-2.134.59a2 2 0 00-1.384 1.272L7.5 19.5h9l-1.072-4.072z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a3 3 0 00-3 3v1a3 3 0 006 0V5a3 3 0 00-3-3z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ $productionSummary[\App\Models\Order::STATUS_PROCESSING] ?? 0 }}</p>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Incoming Orders (2 cols) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Customer Menunggu ACC</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Approve akun customer baru dari dashboard</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold">
                        {{ $pendingCustomerCount }} pending
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Kontak</th>
                                <th class="px-6 py-3">Daftar</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($pendingCustomers as $customer)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5">
                                        <div class="font-medium text-slate-800">{{ $customer->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $customer->email }}</div>
                                    </td>
                                    <td class="px-6 py-3.5 text-slate-600">{{ $customer->phone ?: '-' }}</td>
                                    <td class="px-6 py-3.5 text-slate-600">{{ $customer->created_at->translatedFormat('d M Y, H:i') }}</td>
                                    <td class="px-6 py-3.5 text-right">
                                        <form method="POST" action="{{ route('admin.customers.decide', $customer) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="decision" value="accept" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium text-white bg-pink-600 hover:bg-pink-700 transition">
                                                ACC Customer
                                            </button>
                                            <button type="submit" name="decision" value="reject" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 transition">
                                                Tolak
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">
                                        Belum ada akun customer yang menunggu persetujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Incoming Orders --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-800">Pesanan Masuk</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Order menunggu keputusan admin</p>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-800 transition px-3 py-1.5 rounded-lg hover:bg-slate-50">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider">
                                <th class="px-6 py-3">Kode</th>
                                <th class="px-6 py-3">Customer</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Total</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($incomingOrders as $order)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-3.5 font-medium text-slate-800">{{ $order->order_code }}</td>
                                    <td class="px-6 py-3.5 text-slate-600">{{ $order->customer_name }}</td>
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
                                            Buka
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        Belum ada pesanan baru
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Top Products --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-800">Produk Terlaris</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Top 5 berdasarkan histori order</p>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($topProducts as $i => $product)
                        <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                            <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->category?->name ?? 'Cake Series' }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-xs font-medium text-slate-600">{{ $product->total_sold ?? 0 }} terjual</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- RIGHT column (1 col) --}}
        <div class="space-y-6">
            {{-- Notifications --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-semibold text-slate-800">Notifikasi</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Aktivitas terbaru</p>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($notifications as $notification)
                        <div class="px-6 py-3.5 hover:bg-slate-50/50 transition-colors">
                            <p class="text-sm font-medium text-slate-800">{{ $notification->title }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">{{ $notification->message }}</p>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <p class="text-sm text-slate-400">Belum ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Low Stock --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-semibold text-slate-800">Stok Rendah</h3>
                        <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">Perlu perhatian segera</p>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($lowStockProducts as $product)
                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium shrink-0 {{ $product->stock <= 5 ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600' }}">
                                Stok {{ $product->stock }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl border border-slate-200/60 p-5">
                <h3 class="text-sm font-semibold text-slate-800 mb-3">Aksi Cepat</h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('admin.products.create') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition text-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-slate-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        <span class="text-xs font-medium text-slate-600">Tambah Produk</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition text-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-slate-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span class="text-xs font-medium text-slate-600">Kelola Pesanan</span>
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition text-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-slate-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                        <span class="text-xs font-medium text-slate-600">Laporan</span>
                    </a>
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition text-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-slate-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        <span class="text-xs font-medium text-slate-600">Lihat Toko</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
