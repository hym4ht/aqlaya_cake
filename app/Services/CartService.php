<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CartService
{
    public const SESSION_KEY = 'aqlaya_cart';

    public function all(): Collection
    {
        return collect(session(self::SESSION_KEY, []));
    }

    public function add(Product $product, array $payload): void
    {
        $items = $this->all();
        $quantity = (int) $payload['quantity'];
        $price = (float) $product->price;

        $items->put((string) Str::uuid(), [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'image_url' => $product->image_url,
            'price' => $price,
            'quantity' => $quantity,
            'size' => $payload['size'],
            'custom_message' => $payload['custom_message'] ?? null,
            'scheduled_date' => $payload['scheduled_date'],
            'scheduled_time' => $payload['scheduled_time'] ?? null,
            'notes' => $payload['notes'] ?? null,
            'line_total' => $price * $quantity,
        ]);

        session([self::SESSION_KEY => $items->all()]);
    }

    public function updateQuantity(string $itemId, int $quantity): void
    {
        $items = $this->all();
        $item = $items->get($itemId);

        if (! $item) {
            return;
        }

        if ($quantity < 1) {
            $this->remove($itemId);

            return;
        }

        $item['quantity'] = $quantity;
        $item['line_total'] = $item['price'] * $quantity;
        $items->put($itemId, $item);

        session([self::SESSION_KEY => $items->all()]);
    }

    public function remove(string $itemId): void
    {
        $items = $this->all();
        $items->forget($itemId);

        session([self::SESSION_KEY => $items->all()]);
    }

    public function clear(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    public function count(): int
    {
        return (int) $this->all()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->all()->sum('line_total');
    }

    public function deliveryFee(string $shippingMethod): float
    {
        return $shippingMethod === 'delivery' ? 25000 : 0;
    }
}
