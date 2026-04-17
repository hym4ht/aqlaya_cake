@extends('layouts.admin')

@section('title', 'Kelola Banner — Aqlaya Cake')
@section('page-title', 'Banner Promo')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-slate-800">Banner Promo</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola banner promo yang tampil di halaman utama.</p>
        </div>
        <a href="{{ route('admin.banners.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Banner
        </a>
    </div>

    {{-- Banner List --}}
    @if($banners->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-slate-500 text-sm">Belum ada banner. Klik tombol di atas untuk menambahkan.</p>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($banners as $banner)
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row">
                        {{-- Thumbnail --}}
                        <div class="sm:w-64 h-40 sm:h-auto flex-shrink-0 bg-slate-100">
                            <img src="{{ asset('storage/' . $banner->image_path) }}"
                                 alt="Banner"
                                 class="w-full h-full object-cover">
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    @if($banner->is_active)
                                        <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[11px] font-bold rounded-full border border-emerald-200">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[11px] font-bold rounded-full border border-slate-200">Nonaktif</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-400">Ditambahkan: {{ $banner->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-2 mt-4">
                                <a href="{{ route('admin.banners.edit', $banner) }}"
                                   class="px-4 py-2 text-sm font-medium bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $banner) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus banner ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
