<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use App\Services\LeadTimeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly LeadTimeService $leadTimeService,
    ) {
    }

    public function index(): View
    {
        return view('cart.index', [
            'cartItems' => $this->cartService->all(),
            'subtotal' => $this->cartService->subtotal(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        $rules = [
            'product_size_id' => ['required', 'exists:product_sizes,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'custom_message' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];

        // Only require scheduled date/time for pre-order products
        if ($product->isPreOrder()) {
            $rules['scheduled_date'] = ['required', 'date'];
            $rules['scheduled_time'] = ['nullable', 'date_format:H:i'];
        } else {
            $rules['scheduled_date'] = ['nullable', 'date'];
            $rules['scheduled_time'] = ['nullable', 'date_format:H:i'];
        }

        $validated = $request->validate($rules);

        // Validate size belongs to product and has stock
        $productSize = $product->productSizes()->findOrFail($validated['product_size_id']);
        
        if ($validated['quantity'] > $productSize->stock) {
            return back()->withErrors([
                'quantity' => 'Stok tidak mencukupi. Tersedia: ' . $productSize->stock,
            ])->withInput();
        }

        // Only validate lead time for pre-order products
        if ($product->isPreOrder() && !$this->leadTimeService->isAllowed($request->string('scheduled_date'), $product->lead_time_days)) {
            return back()->withErrors([
                'scheduled_date' => 'Tanggal produksi minimal H-' . $product->lead_time_days . ' dari hari ini.',
            ])->withInput();
        }

        $this->cartService->add($product, $request->only([
            'quantity',
            'product_size_id',
            'custom_message',
            'scheduled_date',
            'scheduled_time',
            'notes',
        ]));

        return redirect()->route('cart.index')->with('success', 'Produk berhasil masuk ke keranjang.');
    }

    public function update(Request $request, string $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $this->cartService->updateQuantity($itemId, (int) $validated['quantity']);

        return back()->with('success', 'Jumlah item diperbarui.');
    }

    public function destroy(string $itemId): RedirectResponse
    {
        $this->cartService->remove($itemId);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }
}
