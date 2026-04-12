<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Services\MidtransService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerOrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(8);

        return view('orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order, MidtransService $midtransService): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load([
            'items.product',
            'reviews',
        ]);

        $snapToken = null;
        $snapJsUrl = null;
        $clientKey = null;
        $isMidtransConfigured = $midtransService->isConfigured();

        if ($order->status === Order::STATUS_PENDING_PAYMENT) {
            if ($isMidtransConfigured) {
                try {
                    $snapToken = $midtransService->createSnapToken($order);
                    $snapJsUrl = $midtransService->getSnapJsUrl();
                    $clientKey = $midtransService->getClientKey();
                } catch (\Exception $e) {
                    report($e);
                    // Falls back to simulation mode if Snap token creation fails
                }
            }
        }

        return view('orders.show', [
            'order' => $order,
            'snapToken' => $snapToken,
            'snapJsUrl' => $snapJsUrl,
            'clientKey' => $clientKey,
            'isMidtransConfigured' => $isMidtransConfigured,
            'reviewedProductIds' => $order->reviews->pluck('product_id')->all(),
        ]);
    }

    public function simulatePayment(Request $request, Order $order, MidtransService $midtransService): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        // Only allow simulation when Midtrans is NOT configured (local dev)
        if ($midtransService->isConfigured()) {
            return back()->with('error', 'Pembayaran menggunakan Midtrans. Simulasi tidak tersedia.');
        }

        $midtransService->handleSimulatedCallback($order->order_code, 'settlement', 'SIM-' . $order->order_code);

        return back()->with('success', 'Pembayaran simulasi berhasil diproses.');
    }

    public function storeReview(Request $request, Order $order, Product $product): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->status === Order::STATUS_COMPLETED, 403);
        abort_unless($order->items()->where('product_id', $product->id)->exists(), 404);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string', 'max:500'],
        ]);

        ProductReview::query()->updateOrCreate(
            [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
            ],
            $validated,
        );

        return back()->with('success', 'Terima kasih, ulasan berhasil disimpan.');
    }
}
