<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();

        $prefix = $this->getRoutePrefix();
        return view("{$prefix}.banners.index", compact('banners'));
    }

    public function create()
    {
        $prefix = $this->getRoutePrefix();
        return view("{$prefix}.banners.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'image'     => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'image_path' => $path,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        $prefix = $this->getRoutePrefix();
        return redirect()->route("{$prefix}.banners.index")
            ->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        $prefix = $this->getRoutePrefix();
        return view("{$prefix}.banners.edit", compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image_path) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        $banner->is_active = $request->boolean('is_active', true);
        $banner->save();

        $prefix = $this->getRoutePrefix();
        return redirect()->route("{$prefix}.banners.index")
            ->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        $prefix = $this->getRoutePrefix();
        return redirect()->route("{$prefix}.banners.index")
            ->with('success', 'Banner berhasil dihapus.');
    }
}
