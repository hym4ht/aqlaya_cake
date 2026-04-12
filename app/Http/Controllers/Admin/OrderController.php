<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with('user')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->when($request->filled('payment_status'), fn ($query) => $query->where('payment_status', $request->string('payment_status')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'reviews']);

        return view('admin.orders.show', compact('order'));
    }

    public function decide(Request $request, Order $order, NotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', Rule::in(['accept', 'reject'])],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($order->status !== Order::STATUS_AWAITING_CONFIRMATION) {
            return back()->with('error', 'Pesanan ini tidak berada pada tahap konfirmasi admin.');
        }

        if ($validated['decision'] === 'accept') {
            DB::transaction(function () use ($order): void {
                foreach ($order->items as $item) {
                    if ($item->product && $item->product->stock < $item->quantity) {
                        abort(422, "Stok {$item->product->name} tidak mencukupi.");
                    }
                }

                foreach ($order->items as $item) {
                    if ($item->product) {
                        $item->product->decrement('stock', $item->quantity);
                    }
                }

                $order->update([
                    'status' => Order::STATUS_PROCESSING,
                    'rejection_reason' => null,
                ]);
            });

            $notificationService->notifyUser(
                $order->user,
                'Pesanan diterima',
                "Pesanan {$order->order_code} diterima dan sedang diproses dapur.",
                route('orders.show', $order),
            );

            return back()->with('success', 'Pesanan diterima dan masuk tahap produksi.');
        }

        if (blank($validated['reason'])) {
            return back()->withErrors([
                'reason' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        $order->update([
            'status' => Order::STATUS_REJECTED,
            'payment_status' => $order->isPaid() ? Order::PAYMENT_REFUNDED : $order->payment_status,
            'rejection_reason' => $validated['reason'],
        ]);

        $notificationService->notifyUser(
            $order->user,
            'Pesanan ditolak',
            "Pesanan {$order->order_code} ditolak. Alasan: {$validated['reason']}",
            route('orders.show', $order),
        );

        return back()->with('success', 'Pesanan ditolak dan notifikasi sudah dikirim ke customer.');
    }

    public function updateStatus(Request $request, Order $order, NotificationService $notificationService): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in([Order::STATUS_READY, Order::STATUS_COMPLETED])],
        ]);

        if ($validated['status'] === Order::STATUS_READY && $order->status !== Order::STATUS_PROCESSING) {
            return back()->with('error', 'Pesanan harus berada di status Diproses sebelum ditandai siap.');
        }

        if ($validated['status'] === Order::STATUS_COMPLETED && $order->status !== Order::STATUS_READY) {
            return back()->with('error', 'Pesanan harus siap diambil/diantar sebelum diselesaikan.');
        }

        $order->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === Order::STATUS_COMPLETED ? now() : $order->completed_at,
        ]);

        $label = $order->statusLabel();
        $notificationService->notifyUser(
            $order->user,
            'Status pesanan diperbarui',
            "Pesanan {$order->order_code} sekarang berstatus {$label}.",
            route('orders.show', $order),
        );

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
