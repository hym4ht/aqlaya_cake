<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartService
{
    public const SESSION_KEY = 'aqlaya_cart';

    // ─── Helpers ──────────────────────────────────────────────

    private function isLoggedIn(): bool
    {
        return Auth::check();
    }

    // ─── Read ──────────────────────────────────────────────────

    public function all(): Collection
    {
        if ($this->isLoggedIn()) {
            return Cart::where('user_id', Auth::id())
                ->get()
                ->keyBy('id')
                ->map(fn ($row) => $this->rowToArray($row));
        }

        return collect(session(self::SESSION_KEY, []));
    }

    // ─── Write ─────────────────────────────────────────────────

    public function add(Product $product, array $payload): void
    {
        $quantity = (int) $payload['quantity'];
        $price    = (float) $product->price;

        if ($this->isLoggedIn()) {
            Cart::create([
                'id'             => (string) Str::uuid(),
                'user_id'        => Auth::id(),
                'product_id'     => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'image_url'      => $product->image_url,
                'price'          => $price,
                'quantity'       => $quantity,
                'size'           => $payload['size'],
                'custom_message' => $payload['custom_message'] ?? null,
                'scheduled_date' => $payload['scheduled_date'] ?? null,
                'scheduled_time' => $payload['scheduled_time'] ?? null,
                'notes'          => $payload['notes'] ?? null,
                'line_total'     => $price * $quantity,
            ]);

            return;
        }

        // Guest: simpan ke session
        $items = $this->all();
        $items->put((string) Str::uuid(), [
            'product_id'     => $product->id,
            'name'           => $product->name,
            'slug'           => $product->slug,
            'image_url'      => $product->image_url,
            'price'          => $price,
            'quantity'       => $quantity,
            'size'           => $payload['size'],
            'custom_message' => $payload['custom_message'] ?? null,
            'scheduled_date' => $payload['scheduled_date'] ?? null,
            'scheduled_time' => $payload['scheduled_time'] ?? null,
            'notes'          => $payload['notes'] ?? null,
            'line_total'     => $price * $quantity,
        ]);

        session([self::SESSION_KEY => $items->all()]);
    }

    public function updateQuantity(string $itemId, int $quantity): void
    {
        if ($this->isLoggedIn()) {
            $row = Cart::where('id', $itemId)->where('user_id', Auth::id())->first();
            if (! $row) return;

            if ($quantity < 1) {
                $row->delete();
                return;
            }

            $row->update([
                'quantity'   => $quantity,
                'line_total' => $row->price * $quantity,
            ]);

            return;
        }

        // Guest
        $items = $this->all();
        $item  = $items->get($itemId);
        if (! $item) return;

        if ($quantity < 1) {
            $this->remove($itemId);
            return;
        }

        $item['quantity']   = $quantity;
        $item['line_total'] = $item['price'] * $quantity;
        $items->put($itemId, $item);

        session([self::SESSION_KEY => $items->all()]);
    }

    public function remove(string $itemId): void
    {
        if ($this->isLoggedIn()) {
            Cart::where('id', $itemId)->where('user_id', Auth::id())->delete();
            return;
        }

        $items = $this->all();
        $items->forget($itemId);
        session([self::SESSION_KEY => $items->all()]);
    }

    public function clear(): void
    {
        if ($this->isLoggedIn()) {
            Cart::where('user_id', Auth::id())->delete();
            return;
        }

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

    // ─── Migrate guest cart ke DB setelah login ───────────────

    public function mergeSessionCartToDatabase(): void
    {
        $sessionItems = collect(session(self::SESSION_KEY, []));

        if ($sessionItems->isEmpty()) return;

        foreach ($sessionItems as $item) {
            Cart::create([
                'id'             => (string) Str::uuid(),
                'user_id'        => Auth::id(),
                'product_id'     => $item['product_id'],
                'name'           => $item['name'],
                'slug'           => $item['slug'],
                'image_url'      => $item['image_url'] ?? null,
                'price'          => $item['price'],
                'quantity'       => $item['quantity'],
                'size'           => $item['size'],
                'custom_message' => $item['custom_message'] ?? null,
                'scheduled_date' => $item['scheduled_date'] ?? null,
                'scheduled_time' => $item['scheduled_time'] ?? null,
                'notes'          => $item['notes'] ?? null,
                'line_total'     => $item['line_total'],
            ]);
        }

        session()->forget(self::SESSION_KEY);
    }

    // ─── Internal helper ──────────────────────────────────────

    private function rowToArray(Cart $row): array
    {
        return [
            'product_id'     => $row->product_id,
            'name'           => $row->name,
            'slug'           => $row->slug,
            'image_url'      => $row->image_url,
            'price'          => $row->price,
            'quantity'       => $row->quantity,
            'size'           => $row->size,
            'custom_message' => $row->custom_message,
            'scheduled_date' => $row->scheduled_date,
            'scheduled_time' => $row->scheduled_time,
            'notes'          => $row->notes,
            'line_total'     => $row->line_total,
        ];
    }
}
