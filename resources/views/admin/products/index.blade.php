@extends('layouts.admin')

@section('title', 'Produk — Admin Aqlaya Cake')
@section('page-title', 'Produk')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Manajemen Produk</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola katalog produk Aqlaya Cake</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            Tambah Produk
        </a>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari Produk</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.1-5.4a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, deskripsi, atau kategori produk"
                        class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3.5 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition shrink-0">
                Cari
            </button>
            @if($search !== '')
                <a href="{{ route('admin.products.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden"
        x-data="{
            selected: [],
            productIds: @json($products->pluck('id')->map(fn ($id) => (string) $id)->values()),
            get allSelected() {
                return this.productIds.length > 0 && this.selected.length === this.productIds.length;
            },
            get deleteLabel() {
                return this.allSelected ? 'Hapus Semua' : 'Hapus Pilihan';
            },
            get confirmMessage() {
                return this.allSelected
                    ? 'Hapus semua produk yang tampil di halaman ini?'
                    : 'Hapus ' + this.selected.length + ' produk yang dipilih?';
            },
        }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <p class="text-sm text-slate-500">
                <span x-show="selected.length === 0">Pilih produk untuk hapus banyak sekaligus.</span>
                <span x-show="selected.length > 0" x-cloak><span x-text="selected.length"></span> produk dipilih.</span>
            </p>
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.bulk-destroy') }}"
                x-show="selected.length > 0"
                x-cloak
                x-transition
                @submit="if (!selected.length || !confirm(confirmMessage)) $event.preventDefault()">
                @csrf
                @method('DELETE')
                <button type="submit"
                    :disabled="selected.length === 0"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition shrink-0 bg-red-600 text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span x-text="deleteLabel">Hapus Pilihan</span>
                </button>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="w-12 px-6 py-3.5">
                            <input type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-pink-600 focus:ring-pink-500"
                                :checked="allSelected"
                                x-effect="$el.indeterminate = selected.length > 0 && selected.length < productIds.length"
                                @change="selected = $event.target.checked ? [...productIds] : []">
                        </th>
                        <th class="px-6 py-3.5">Produk</th>
                        <th class="px-6 py-3.5">Kategori</th>
                        <th class="px-6 py-3.5">Harga</th>
                        <th class="px-6 py-3.5">Stok</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Best Seller</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-3.5">
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="bulk-delete-form"
                                    x-model="selected"
                                    class="h-4 w-4 rounded border-slate-300 text-pink-600 focus:ring-pink-500">
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-medium text-slate-800">{{ $product->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $product->excerpt }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">{{ $product->category?->name ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-3.5 font-medium text-slate-800">Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-3.5">
                                <span class="text-sm {{ $product->stock <= 5 ? 'text-red-600 font-semibold' : 'text-slate-600' }}">{{ $product->stock }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                @if($product->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-3.5">
                                <form method="POST" action="{{ route('admin.products.toggle-best-seller', $product) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $product->is_best_seller ? 'Hapus dari Best Seller' : 'Tandai sebagai Best Seller' }}"
                                        class="relative inline-flex h-6 w-10 items-center rounded-full transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-1 {{ $product->is_best_seller ? 'bg-amber-500 focus:ring-amber-300' : 'bg-slate-200 focus:ring-slate-300' }}">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow-sm transition-transform duration-200 {{ $product->is_best_seller ? 'translate-x-[18px]' : 'translate-x-0.5' }}"></span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                @if($search !== '')
                                    <p class="text-sm text-slate-400 mb-3">Tidak ada produk yang cocok dengan pencarian.</p>
                                    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-medium hover:bg-slate-800 transition">
                                        Reset Pencarian
                                    </a>
                                @else
                                    <p class="text-sm text-slate-400 mb-3">Belum ada produk pada katalog.</p>
                                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600 text-white text-xs font-medium hover:bg-pink-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                        Tambah Produk Pertama
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($products->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
