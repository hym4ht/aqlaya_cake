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
        <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <a href="{{ route("admin.products.create") }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 mb-6">
        <form method="GET" action="{{ route("admin.products.index") }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari Produk</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.1-5.4a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari produk..."
                        class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3.5 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-pink-600 text-white text-sm font-medium hover:bg-pink-700 transition shrink-0">
                Cari
            </button>
            @if($search !== '')
                <a href="{{ route("admin.products.index") }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <style>
        @supports selector(:has(*)) {
            #product-bulk-table:has([data-product-checkbox]:checked) #bulk-action-bar {
                display: flex !important;
            }

            #product-bulk-table:has([data-product-checkbox]:checked) #bulk-empty-message {
                display: none !important;
            }

            #product-bulk-table:has([data-product-checkbox]:checked) #bulk-selected-message {
                display: inline !important;
            }
        }
    </style>

    <div id="product-bulk-table" class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <p class="text-sm text-slate-500">
                <span id="bulk-empty-message">Centang produk, lalu klik Hapus Pilihan.</span>
                <span id="bulk-selected-message" class="hidden"><span id="bulk-selected-count">0</span> produk dipilih.</span>
            </p>
            <form id="bulk-delete-form" method="POST" action="{{ route("admin.products.bulk-destroy") }}" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>
        <div id="bulk-action-bar" class="hidden items-center justify-end gap-3 px-6 py-4 border-b border-slate-100 bg-red-50/70">
            <button type="submit" form="bulk-delete-form" id="bulk-delete-button"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition shrink-0 bg-red-600 text-white hover:bg-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span id="bulk-delete-label">Hapus Pilihan</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider border-b border-slate-100">
                        <th class="w-12 px-6 py-3.5">
                            <input type="checkbox"
                                id="product-select-all"
                                onchange="document.querySelectorAll('#product-bulk-table [data-product-checkbox]').forEach((checkbox) => { checkbox.checked = this.checked; }); window.updateProductBulkState && window.updateProductBulkState()"
                                class="h-4 w-4 rounded border-slate-300 text-pink-600 focus:ring-pink-500">
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
                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" form="bulk-delete-form" data-product-checkbox
                                    onchange="window.updateProductBulkState && window.updateProductBulkState()"
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
                                    <a href="{{ route("admin.products.index") }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600 text-white text-xs font-medium hover:bg-pink-700 transition">
                                        Reset Pencarian
                                    </a>
                                @else
                                    <p class="text-sm text-slate-400 mb-3">Belum ada produk pada katalog.</p>
                                    <a href="{{ route("admin.products.create") }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-pink-600 text-white text-xs font-medium hover:bg-pink-700 transition">
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

    <script>
        (() => {
            const root = document.getElementById('product-bulk-table');
            const setupProductBulkDelete = () => {
                const root = document.getElementById('product-bulk-table');
                if (!root || root.dataset.bulkReady === '1') return;

                root.dataset.bulkReady = '1';

                const selectAll = document.getElementById('product-select-all');
                const checkboxes = Array.from(root.querySelectorAll('[data-product-checkbox]'));
                const form = document.getElementById('bulk-delete-form');
                const actionBar = document.getElementById('bulk-action-bar');
                const label = document.getElementById('bulk-delete-label');
                const emptyMessage = document.getElementById('bulk-empty-message');
                const selectedMessage = document.getElementById('bulk-selected-message');
                const selectedCount = document.getElementById('bulk-selected-count');

                const getSelected = () => checkboxes.filter((checkbox) => checkbox.checked);

                const updateBulkState = () => {
                    const count = getSelected().length;
                    const allSelected = checkboxes.length > 0 && count === checkboxes.length;

                    if (selectAll) {
                        selectAll.checked = allSelected;
                        selectAll.indeterminate = count > 0 && count < checkboxes.length;
                    }

                    selectedCount.textContent = count;
                    emptyMessage.classList.toggle('hidden', count > 0);
                    selectedMessage.classList.toggle('hidden', count === 0);
                    actionBar.classList.toggle('hidden', count === 0);
                    actionBar.classList.toggle('flex', count > 0);
                    label.textContent = allSelected ? 'Hapus Semua' : 'Hapus Pilihan';
                };

                window.updateProductBulkState = updateBulkState;
                window.setProductBulkSelection = (checked) => {
                    checkboxes.forEach((checkbox) => {
                        checkbox.checked = checked;
                    });
                    updateBulkState();
                };

                if (selectAll) {
                    selectAll.addEventListener('change', () => {
                        window.setProductBulkSelection(selectAll.checked);
                    });
                }

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', updateBulkState);
                });

                form.addEventListener('submit', (event) => {
                    const count = getSelected().length;
                    const allSelected = checkboxes.length > 0 && count === checkboxes.length;
                    const message = allSelected
                        ? 'Hapus semua produk yang tampil di halaman ini?'
                        : `Hapus ${count} produk yang dipilih?`;

                    if (count === 0 || !confirm(message)) {
                        event.preventDefault();
                    }
                });

                updateBulkState();
            };

            if (root) {
                setupProductBulkDelete();
            } else if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupProductBulkDelete);
            } else {
                setupProductBulkDelete();
            }

            document.addEventListener('DOMContentLoaded', setupProductBulkDelete);
        })();
    </script>
@endsection
