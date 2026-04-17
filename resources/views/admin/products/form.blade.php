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

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-5">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
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
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Tipe Produk</label>
                        <select name="product_type" id="product_type" class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                            <option value="pre_order" @selected(old('product_type', $product->product_type ?? 'pre_order') == 'pre_order')>Pre Order (PO)</option>
                            <option value="ready_stock" @selected(old('product_type', $product->product_type) == 'ready_stock')>Ready Stock</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-1">Pre Order memerlukan jadwal pengiriman, Ready Stock langsung tersedia</p>
                    </div>
                    <div id="lead_time_wrapper">
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">Lead Time (Hari)</label>
                        <input type="number" name="lead_time_days" value="{{ old('lead_time_days', $product->lead_time_days ?? 2) }}" min="1"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="2" />
                        <p class="text-[11px] text-slate-400 mt-1">Minimal hari untuk Pre Order (H-X)</p>
                    </div>
                </div>

                {{-- Image Upload Section --}}
                <div class="space-y-4" x-data="imagePreview()">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-2">Gambar Produk (Maksimal 3)</label>
                        <p class="text-[11px] text-slate-400 mb-3">Format: JPEG, PNG, GIF. Maksimal 5MB per gambar. Bisa pilih 1, 2, atau 3 gambar sekaligus.</p>
                        
                        {{-- Single Multi-File Input --}}
                        <input type="file" name="image_files[]" accept="image/jpeg,image/png,image/jpg,image/gif" multiple
                            @change="handleFiles($event)"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition" />
                        
                        {{-- Preview Selected Images --}}
                        <div x-show="previews.length > 0" class="mt-4 space-y-2">
                            <p class="text-xs font-medium text-slate-600">Preview gambar yang dipilih:</p>
                            <div class="flex flex-wrap gap-3">
                                <template x-for="(preview, index) in previews" :key="index">
                                    <div class="relative flex items-center gap-2 p-2 bg-blue-50 rounded-lg border border-blue-200">
                                        <img :src="preview" alt="Preview" class="w-12 h-16 object-cover rounded">
                                        <span class="text-[10px] text-blue-600" x-text="'Gambar ' + (index + 1)"></span>
                                        <button type="button" @click="removePreview(index)" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] hover:bg-red-600 flex items-center justify-center">×</button>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        {{-- Current Images Display --}}
                        @if($product->image_path || $product->image_path_2 || $product->image_path_3)
                            <div class="mt-4 space-y-2">
                                <p class="text-xs font-medium text-slate-600">Gambar saat ini:</p>
                                <div class="flex flex-wrap gap-3">
                                    @if($product->image_path)
                                        <div class="relative" x-data="{ show: true }" x-show="show">
                                            <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg border border-slate-200">
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-12 h-16 object-cover rounded">
                                                <span class="text-[10px] text-slate-500">Gambar 1</span>
                                            </div>
                                            <button type="button" @click="show = false; document.getElementById('delete_image_1').value = '1'" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] hover:bg-red-600 flex items-center justify-center">×</button>
                                            <input type="hidden" id="delete_image_1" name="delete_image_1" value="0">
                                        </div>
                                    @endif
                                    @if($product->image_path_2)
                                        <div class="relative" x-data="{ show: true }" x-show="show">
                                            <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg border border-slate-200">
                                                <img src="{{ asset('storage/' . $product->image_path_2) }}" alt="{{ $product->name }}" class="w-12 h-16 object-cover rounded">
                                                <span class="text-[10px] text-slate-500">Gambar 2</span>
                                            </div>
                                            <button type="button" @click="show = false; document.getElementById('delete_image_2').value = '1'" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] hover:bg-red-600 flex items-center justify-center">×</button>
                                            <input type="hidden" id="delete_image_2" name="delete_image_2" value="0">
                                        </div>
                                    @endif
                                    @if($product->image_path_3)
                                        <div class="relative" x-data="{ show: true }" x-show="show">
                                            <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg border border-slate-200">
                                                <img src="{{ asset('storage/' . $product->image_path_3) }}" alt="{{ $product->name }}" class="w-12 h-16 object-cover rounded">
                                                <span class="text-[10px] text-slate-500">Gambar 3</span>
                                            </div>
                                            <button type="button" @click="show = false; document.getElementById('delete_image_3').value = '1'" class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white rounded-full text-[10px] hover:bg-red-600 flex items-center justify-center">×</button>
                                            <input type="hidden" id="delete_image_3" name="delete_image_3" value="0">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">URL Gambar (Fallback)</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $product->image_url) }}"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="https://..." />
                        <p class="text-[11px] text-slate-400 mt-1">Digunakan jika tidak ada file gambar yang diunggah</p>
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

    <script>
        // Image Preview Component
        function imagePreview() {
            return {
                previews: [],
                files: [],
                
                handleFiles(event) {
                    const input = event.target;
                    const selectedFiles = Array.from(input.files);
                    
                    // Limit to 3 images
                    if (selectedFiles.length > 3) {
                        alert('Maksimal 3 gambar saja');
                        input.value = '';
                        return;
                    }
                    
                    this.files = selectedFiles;
                    this.previews = [];
                    
                    selectedFiles.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                },
                
                removePreview(index) {
                    this.previews.splice(index, 1);
                    this.files.splice(index, 1);
                    
                    // Update the file input
                    const input = document.querySelector('input[name="image_files[]"]');
                    const dt = new DataTransfer();
                    this.files.forEach(file => dt.items.add(file));
                    input.files = dt.files;
                }
            }
        }
        
        // Product Type Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const productTypeSelect = document.getElementById('product_type');
            const leadTimeWrapper = document.getElementById('lead_time_wrapper');

            function toggleLeadTime() {
                if (productTypeSelect.value === 'ready_stock') {
                    leadTimeWrapper.style.opacity = '0.5';
                    leadTimeWrapper.querySelector('input').disabled = true;
                } else {
                    leadTimeWrapper.style.opacity = '1';
                    leadTimeWrapper.querySelector('input').disabled = false;
                }
            }

            productTypeSelect.addEventListener('change', toggleLeadTime);
            toggleLeadTime(); // Initial state
        });
    </script>
@endsection
