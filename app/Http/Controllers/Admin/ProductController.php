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

        // Handle multiple image uploads
        if ($request->hasFile('image_files')) {
            $imageFiles = $request->file('image_files');
            $imageFields = ['image_path', 'image_path_2', 'image_path_3'];

            foreach ($imageFiles as $index => $file) {
                if ($index < 3 && isset($imageFields[$index])) {
                    $validated[$imageFields[$index]] = $this->storeProductImage($file);
                }
            }
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

        // Handle individual image deletion
        $imageFields = [
            'delete_image_1' => 'image_path',
            'delete_image_2' => 'image_path_2',
            'delete_image_3' => 'image_path_3',
        ];

        foreach ($imageFields as $deleteField => $imageField) {
            if ($request->input($deleteField) === '1') {
                // Delete the image file from storage
                if ($product->$imageField) {
                    Storage::disk('public')->delete($product->$imageField);
                }
                // Set the field to null in validated data
                $validated[$imageField] = null;
            }
        }

        // Handle multiple image uploads
        if ($request->hasFile('image_files')) {
            $imageFiles = $request->file('image_files');
            $imageFieldNames = ['image_path', 'image_path_2', 'image_path_3'];
            $oldImages = [$product->image_path, $product->image_path_2, $product->image_path_3];

            // Delete all old images when new ones are uploaded
            foreach ($oldImages as $oldImage) {
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            // Store new images
            foreach ($imageFiles as $index => $file) {
                if ($index < 3 && isset($imageFieldNames[$index])) {
                    $validated[$imageFieldNames[$index]] = $this->storeProductImage($file);
                }
            }
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
        $rules = [
            'category_id' => ['nullable', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'excerpt' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:1000'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'url'],
            'image_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'sizes_input' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'product_type' => ['required', 'in:pre_order,ready_stock'],
        ];

        // Only require lead_time_days for pre-order products
        if ($request->input('product_type') === 'pre_order') {
            $rules['lead_time_days'] = ['required', 'integer', 'min:1'];
        } else {
            $rules['lead_time_days'] = ['nullable', 'integer', 'min:1'];
        }

        $validated = $request->validate($rules);

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
