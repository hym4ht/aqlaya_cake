@extends('layouts.admin')

@section('title', 'Laporan — Admin Aqlaya Cake')
@section('page-title', 'Laporan')

@section('content')
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-slate-900">Laporan Penjualan</h2>
        <p class="text-sm text-slate-500 mt-1">Ringkasan harian dan bulanan pemasukan Aqlaya Cake</p>
    </div>

    {{-- Date Filter --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" />
            </div>
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" />
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 transition shrink-0">
                Tampilkan
            </button>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Order</span>
                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($summary['orders'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">order berhasil</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Total Pendapatan</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp{{ number_format($summary['revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">pendapatan kotor</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/60 p-5 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium uppercase tracking-wider text-slate-400">Rata-rata Order</span>
                <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900">Rp{{ number_format($summary['average'], 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">per transaksi</p>
        </div>
    </div>

    {{-- Details grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-800">Produk Terlaris</h3>
                <p class="text-xs text-slate-400 mt-0.5">Top 5 produk pada rentang ini</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topProducts as $i => $product)
                    <div class="px-6 py-3.5 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                        <span class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $product->product_name }}</p>
                            <p class="text-xs text-slate-400">Rp{{ number_format($product->total_sales, 0, ',', '.') }}</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-xs font-medium text-slate-600 shrink-0">{{ $product->total_qty }} item</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-sm text-slate-400">Belum ada transaksi berhasil</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Daily Revenue --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-800">Pendapatan Harian</h3>
                <p class="text-xs text-slate-400 mt-0.5">Tren pemasukan per hari</p>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($dailyRevenue as $day)
                    <div class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></div>
                            <span class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($day->paid_date)->translatedFormat('d M Y') }}</span>
                        </div>
                        <span class="text-sm font-semibold text-slate-800">Rp{{ number_format($day->total_revenue, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm text-slate-400">Belum ada data harian</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
