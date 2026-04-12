<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
        // Configure Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Create a Snap token for the given order.
     */
    public function createSnapToken(Order $order): string
    {
        $items = [];

        foreach ($order->items as $item) {
            $items[] = [
                'id' => 'ITEM-' . $item->id,
                'price' => (int) $item->product_price,
                'quantity' => $item->quantity,
                'name' => mb_substr($item->product_name . ' (' . $item->size . ')', 0, 50),
            ];
        }

        // Add delivery fee as an item if applicable
        if ($order->delivery_fee > 0) {
            $items[] = [
                'id' => 'DELIVERY-FEE',
                'price' => (int) $order->delivery_fee,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) $order->total_amount,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'callbacks' => [
                'finish' => route('orders.show', $order),
            ],
        ];

        return Snap::getSnapToken($params);
    }

    /**
     * Handle Midtrans webhook/notification callback.
     */
    public function handleNotification(array $payload = []): ?Order
    {
        $notification = ! empty($payload)
            ? (object) [
                'order_id' => $payload['order_id'] ?? $payload['order_code'] ?? null,
                'transaction_status' => $payload['transaction_status'] ?? null,
                'fraud_status' => $payload['fraud_status'] ?? null,
                'payment_type' => $payload['payment_type'] ?? 'midtrans',
                'transaction_id' => $payload['transaction_id'] ?? $payload['reference'] ?? null,
            ]
            : new Notification();

        $orderCode = $notification->order_id;
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status ?? null;
        $paymentType = $notification->payment_type ?? null;
        $transactionId = $notification->transaction_id ?? null;

        $order = Order::query()->where('order_code', $orderCode)->first();

        if (! $order) {
            return null;
        }

        $isPaid = false;

        if ($transactionStatus === 'capture') {
            // For credit card: check fraud status
            $isPaid = ($fraudStatus === 'accept');
        } elseif (in_array($transactionStatus, ['settlement'], true)) {
            $isPaid = true;
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'], true)) {
            if (! $order->isPaid()) {
                $order->update([
                    'status' => Order::STATUS_REJECTED,
                    'rejection_reason' => 'Pembayaran ' . $transactionStatus . ' via Midtrans.',
                ]);
            }
        } elseif ($transactionStatus === 'pending') {
            // Do nothing, keep status pending_payment
        }

        if ($isPaid && ! $order->isPaid()) {
            $reference = $transactionId ?? ($paymentType . '-' . $orderCode);

            $order->update([
                'status' => Order::STATUS_AWAITING_CONFIRMATION,
                'payment_status' => Order::PAYMENT_PAID,
                'midtrans_reference' => $reference,
                'paid_at' => now(),
            ]);

            $this->notificationService->notifyAdmins(
                'Pesanan baru sudah dibayar',
                "Pesanan {$order->order_code} sudah tervalidasi via {$paymentType} dan siap ditinjau admin.",
                route('admin.orders.show', $order),
            );

            $this->notificationService->notifyUser(
                $order->user,
                'Pembayaran berhasil',
                "Pembayaran untuk pesanan {$order->order_code} sudah dikonfirmasi otomatis.",
                route('orders.show', $order),
            );
        }

        return $order->fresh(['items', 'user']);
    }

    /**
     * Handle a simulated payment callback (for local dev without real keys).
     */
    public function handleSimulatedCallback(string $orderCode, string $transactionStatus, ?string $reference = null): ?Order
    {
        $order = Order::query()->where('order_code', $orderCode)->first();

        if (! $order) {
            return null;
        }

        if (in_array($transactionStatus, ['capture', 'settlement', 'paid'], true) && ! $order->isPaid()) {
            $order->update([
                'status' => Order::STATUS_AWAITING_CONFIRMATION,
                'payment_status' => Order::PAYMENT_PAID,
                'midtrans_reference' => $reference ?? 'SIM-' . $order->order_code,
                'paid_at' => now(),
            ]);

            $this->notificationService->notifyAdmins(
                'Pesanan baru sudah dibayar',
                "Pesanan {$order->order_code} sudah tervalidasi dan siap ditinjau admin.",
                route('admin.orders.show', $order),
            );

            $this->notificationService->notifyUser(
                $order->user,
                'Pembayaran berhasil',
                "Pembayaran untuk pesanan {$order->order_code} sudah dikonfirmasi otomatis.",
                route('orders.show', $order),
            );
        }

        return $order->fresh(['items', 'user']);
    }

    /**
     * Check if Midtrans keys are configured (not empty).
     */
    public function isConfigured(): bool
    {
        return filled(config('midtrans.server_key')) && filled(config('midtrans.client_key'));
    }

    /**
     * Get the Midtrans client key for frontend.
     */
    public function getClientKey(): string
    {
        return config('midtrans.client_key', '');
    }

    /**
     * Get the Snap JS URL based on environment.
     */
    public function getSnapJsUrl(): string
    {
        return config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
