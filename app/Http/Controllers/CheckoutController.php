<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly NotificationService $notificationService,
    ) {
    }

    public function create(Request $request): View|RedirectResponse
    {
        if ($this->cartService->all()->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout.create', [
            'cartItems' => $this->cartService->all(),
            'subtotal' => $this->cartService->subtotal(),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $cartItems = $this->cartService->all();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_method' => ['required', Rule::in(['pickup', 'delivery'])],
            'delivery_address' => ['nullable', 'string'],
            'order_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['shipping_method'] === 'delivery' && blank($validated['delivery_address'])) {
            return back()->withErrors([
                'delivery_address' => 'Alamat wajib diisi untuk opsi pengantaran.',
            ])->withInput();
        }

        $scheduledFor = $cartItems
            ->filter(fn(array $item) => !empty($item['scheduled_date']))
            ->map(function (array $item) {
                return Carbon::parse($item['scheduled_date'] . ' ' . ($item['scheduled_time'] ?: '09:00'));
            })
            ->sort()
            ->first();

        $subtotal = $this->cartService->subtotal();
        $deliveryFee = $this->cartService->deliveryFee($validated['shipping_method']);

        $order = DB::transaction(function () use ($request, $validated, $cartItems, $subtotal, $deliveryFee, $scheduledFor) {
            $order = Order::query()->create([
                'user_id' => $request->user()->id,
                'order_code' => $this->generateOrderCode(),
                'status' => Order::STATUS_PENDING_PAYMENT,
                'payment_status' => Order::PAYMENT_UNPAID,
                'shipping_method' => $validated['shipping_method'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'delivery_address' => $validated['shipping_method'] === 'delivery' ? $validated['delivery_address'] : null,
                'scheduled_for' => $scheduledFor,
                'message_on_cake' => $cartItems->pluck('custom_message')->filter()->implode(', '),
                'order_notes' => $validated['order_notes'] ?? null,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total_amount' => $subtotal + $deliveryFee,
            ]);

            foreach ($cartItems as $item) {
                $product = Product::query()->find($item['product_id']);

                $order->items()->create([
                    'product_id' => $product?->id,
                    'product_name' => $item['name'],
                    'product_price' => $item['price'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'],
                    'scheduled_date' => $item['scheduled_date'] ?? null,
                    'scheduled_time' => $item['scheduled_time'] ?? null,
                    'custom_message' => $item['custom_message'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'line_total' => $item['line_total'],
                ]);
            }

            return $order;
        });

        $this->notificationService->notifyUser(
            $request->user(),
            'Pesanan dibuat',
            "Pesanan {$order->order_code} berhasil dibuat dan menunggu pembayaran.",
            route('orders.show', $order),
        );

        $this->cartService->clear();

        return redirect()->route('orders.show', $order)->with('success', 'Checkout berhasil. Lanjutkan ke pembayaran.');
    }

    private function generateOrderCode(): string
    {
        do {
            $orderCode = 'AQL-' . now()->format('ymd') . '-' . Str::upper(Str::random(5));
        } while (Order::query()->where('order_code', $orderCode)->exists());

        return $orderCode;
    }
}
