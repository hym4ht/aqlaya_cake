<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;


class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::query()->orderBy('name')->get(),
            'formAction' => route('admin.products.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $this->uniqueSlug($validated['name']);

        if ($request->hasFile('image_file')) {
            $validated['image_path'] = $this->storeProductImage($request->file('image_file'));
        }

        Product::query()->create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'formAction' => route('admin.products.update', $product),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request);
        $validated['slug'] = $product->slug === Str::slug($validated['name'])
            ? $product->slug
            : $this->uniqueSlug($validated['name'], $product->id);

        if ($request->hasFile('image_file')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $validated['image_path'] = $this->storeProductImage($request->file('image_file'));
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleBestSeller(Product $product): RedirectResponse
    {
        $product->update(['is_best_seller' => !$product->is_best_seller]);

        $status = $product->is_best_seller ? 'ditandai sebagai' : 'dihapus dari';

        return back()->with('success', "Produk \"{$product->name}\" berhasil {$status} Best Seller.");
    }

    private function validateProduct(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sizes_input' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
        ]);

        $validated['sizes'] = collect(preg_split('/[\r\n,]+/', $validated['sizes_input']))
            ->map(fn($size) => trim((string) $size))
            ->filter()
            ->values()
            ->all();

        unset($validated['sizes_input']);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_best_seller'] = $request->boolean('is_best_seller');

        return $validated;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::query()
                ->when($ignoreId, fn($query) => $query->whereKeyNot($ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function storeProductImage($file): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('products', $filename, 'public');

        return $path;
    }
}
