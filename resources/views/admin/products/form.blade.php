@extends('layouts.admin')

@section('title', ($product->exists ? 'Edit' : 'Tambah') . ' Produk — Admin Aqlaya Cake')
@section('page-title', $product->exists ? 'Edit Produk' : 'Tambah Produk')

@section('content')
    {{-- Back link --}}
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        Kembali ke Daftar Produk
    </a>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 sm:p-8">
            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-900">{{ $product->exists ? 'Perbarui Produk' : 'Produk Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $product->exists ? 'Perbarui informasi produk di katalog' : 'Masukkan produk baru ke katalog Aqlaya Cake' }}</p>
            </div>

            <form method="POST" action="{{ $formAction }}" class="space-y-5">
                @csrf
                @if($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Nama kue..." />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Kategori</label>
                        <select name="category_id" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                            <option value="">Tanpa kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Deskripsi Singkat</label>
                    <input type="text" name="excerpt" value="{{ old('excerpt', $product->excerpt) }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Ringkasan singkat produk..." />
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Deskripsi Detail</label>
                    <textarea name="description" rows="4" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition resize-none" placeholder="Deskripsi lengkap produk...">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Harga Dasar</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="0" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Stok</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" required
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="0" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">URL Gambar</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="https://..." />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Daftar Ukuran</label>
                    <textarea name="sizes_input" rows="2" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition resize-none" placeholder="Diameter 12 cm, Diameter 16 cm, Diameter 20 cm">{{ old('sizes_input', implode(', ', $product->sizes ?? [])) }}</textarea>
                    <p class="text-[11px] text-slate-400 mt-1">Pisahkan ukuran dengan koma</p>
                </div>

                {{-- Toggles --}}
                <div class="flex flex-col sm:flex-row gap-5 pt-1">
                    <label class="relative flex items-center gap-3 cursor-pointer group">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active ?? true))
                            class="peer sr-only" id="is_active">
                        <div class="w-10 h-6 rounded-full bg-slate-200 peer-checked:bg-slate-900 transition-colors duration-200 relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform after:duration-200 peer-checked:after:translate-x-4"></div>
                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">Tampilkan di katalog</span>
                    </label>

                    <label class="relative flex items-center gap-3 cursor-pointer group">
                        <input type="hidden" name="is_best_seller" value="0">
                        <input type="checkbox" name="is_best_seller" value="1" @checked(old('is_best_seller', $product->is_best_seller ?? false))
                            class="peer sr-only" id="is_best_seller">
                        <div class="w-10 h-6 rounded-full bg-slate-200 peer-checked:bg-amber-500 transition-colors duration-200 relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:w-5 after:h-5 after:rounded-full after:bg-white after:shadow-sm after:transition-transform after:duration-200 peer-checked:after:translate-x-4"></div>
                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">Best seller</span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        {{ $product->exists ? 'Simpan Perubahan' : 'Tambah Produk' }}
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
