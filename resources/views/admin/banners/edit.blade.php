@extends('layouts.admin')

@section('title', 'Edit Banner — Aqlaya Cake')
@section('page-title', 'Edit Banner')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke daftar banner
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 p-6 lg:p-8 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">Edit Banner</h2>

        <form method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Current Image --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Gambar Saat Ini</label>
                <div class="rounded-xl overflow-hidden border border-slate-200 aspect-[16/9] bg-slate-50 mb-3" x-data="{ preview: null }">
                    <img :src="preview || '{{ asset('storage/' . $banner->image_path) }}'" class="w-full h-full object-cover" alt="Banner">
                </div>
            </div>

            {{-- Replace Image --}}
            <div x-data="{ preview: null }">
                <label for="image" class="block text-sm font-medium text-slate-700 mb-2">Ganti Gambar (Opsional)</label>
                <div x-show="preview" class="mb-3 rounded-xl overflow-hidden border border-emerald-200 aspect-[16/9] bg-slate-50">
                    <img :src="preview" class="w-full h-full object-cover" alt="Preview baru">
                </div>
                <input type="file" name="image" id="image" accept="image/*"
                       @change="preview = URL.createObjectURL($event.target.files[0])"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border file:border-slate-200 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100 transition cursor-pointer">
                <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs font-semibold text-slate-600 mb-1.5">📋 Ketentuan Upload:</p>
                    <ul class="text-xs text-slate-500 space-y-1 list-disc list-inside">
                        <li><span class="font-medium text-slate-600">Maks. ukuran file:</span> 2 MB</li>
                        <li><span class="font-medium text-slate-600">Format:</span> JPG, JPEG, PNG, WebP</li>
                        <li><span class="font-medium text-slate-600">Ukuran disarankan:</span> 1920 × 1080 px (rasio 16:9)</li>
                        <li><span class="font-medium text-slate-600">Min. resolusi:</span> 1280 × 720 px agar tidak pecah</li>
                    </ul>
                </div>
                @error('image')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Active --}}
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}
                           class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-200">
                    <span class="text-sm font-medium text-slate-700">Aktif</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition shadow-sm">
                    Perbarui Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
