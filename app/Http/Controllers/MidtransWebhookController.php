<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransService $midtransService): JsonResponse
    {
        try {
            $order = $midtransService->handleNotification($request->json()->all() ?: $request->all());

            if (! $order) {
                return response()->json([
                    'message' => 'Pesanan tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'message' => 'Webhook diproses.',
                'status' => $order->status,
                'payment_status' => $order->payment_status,
            ]);
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'message' => 'Gagal memproses webhook: ' . $e->getMessage(),
            ], 500);
        }
    }
}
