<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Services\LeadTimeService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request, LeadTimeService $leadTimeService)
    {
        $categories = Category::query()->orderBy('name')->get();

        $products = Product::query()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->withSum('orderItems as total_sold', 'quantity')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->string('search')->trim() . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', function ($categoryQuery) use ($request) {
                    $categoryQuery->where('slug', $request->string('category'));
                });
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', (float) $request->input('max_price'));
            })
            ->available()
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $bestSellers = Product::query()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->withSum('orderItems as total_sold', 'quantity')
            ->available()
            ->orderByDesc('is_best_seller')
            ->orderByDesc('total_sold')
            ->orderByDesc('reviews_avg_rating')
            ->take(3)
            ->get();

        // Best seller products for carousel — only products marked as best seller
        $bestSellerCarousel = Product::query()
            ->where('is_best_seller', true)
            ->available()
            ->latest()
            ->take(5)
            ->get();

        $banners = Banner::active()->latest()->get();

        return view('home', [
            'categories' => $categories,
            'products' => $products,
            'bestSellers' => $bestSellers,
            'bestSellerCarousel' => $bestSellerCarousel,
            'banners' => $banners,
            'minimumOrderDate' => $leadTimeService->minimumOrderDate()->toDateString(),
        ]);
    }

    public function show(Product $product, LeadTimeService $leadTimeService)
    {
        $product->load([
            'category',
            'reviews.user',
        ])->loadAvg('reviews', 'rating')->loadCount('reviews');

        $relatedProducts = Product::query()
            ->whereKeyNot($product->id)
            ->when($product->category_id, fn($query) => $query->where('category_id', $product->category_id))
            ->available()
            ->take(3)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'minimumOrderDate' => $leadTimeService->minimumOrderDate()->toDateString(),
        ]);
    }
}
