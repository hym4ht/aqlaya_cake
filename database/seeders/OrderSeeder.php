<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@aqlaya.test')->first();
        $customer = User::where('email', 'customer@aqlaya.test')->first();
        
        $products = Product::all()->keyBy('slug');

        $completedOrder = Order::query()->create([
            'user_id' => $customer->id,
            'order_code' => 'AQL-DEMO-001',
            'status' => Order::STATUS_COMPLETED,
            'payment_status' => Order::PAYMENT_PAID,
            'shipping_method' => 'pickup',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'scheduled_for' => Carbon::now()->subDay(),
            'message_on_cake' => 'Happy Birthday Aya',
            'subtotal' => 610000,
            'delivery_fee' => 0,
            'total_amount' => 610000,
            'midtrans_reference' => 'SIM-AQL-DEMO-001',
            'paid_at' => Carbon::now()->subDays(3),
            'completed_at' => Carbon::now()->subDay(),
        ]);

        $completedOrder->items()->createMany([
            [
                'product_id' => $products['butter-cake']->id,
                'product_name' => $products['butter-cake']->name,
                'product_price' => $products['butter-cake']->price,
                'size' => 'Diameter 16 cm',
                'quantity' => 1,
                'scheduled_date' => Carbon::now()->subDay()->toDateString(),
                'scheduled_time' => '11:00',
                'custom_message' => 'Happy Birthday Aya',
                'notes' => 'Warna pink lembut.',
                'line_total' => 285000,
            ],
            [
                'product_id' => $products['fruit-cake']->id,
                'product_name' => $products['fruit-cake']->name,
                'product_price' => $products['fruit-cake']->price,
                'size' => 'Diameter 18 cm',
                'quantity' => 1,
                'scheduled_date' => Carbon::now()->subDay()->toDateString(),
                'scheduled_time' => '11:30',
                'custom_message' => 'Best Team Ever',
                'notes' => 'Tanpa kacang.',
                'line_total' => 325000,
            ],
        ]);

        ProductReview::query()->create([
            'product_id' => $products['butter-cake']->id,
            'user_id' => $customer->id,
            'order_id' => $completedOrder->id,
            'rating' => 5,
            'review' => 'Dekor cantik dan rasa fresh. Proses order sampai pickup terasa jelas statusnya.',
        ]);

        $awaitingOrder = Order::query()->create([
            'user_id' => $customer->id,
            'order_code' => 'AQL-DEMO-002',
            'status' => Order::STATUS_AWAITING_CONFIRMATION,
            'payment_status' => Order::PAYMENT_PAID,
            'shipping_method' => 'delivery',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => $customer->phone,
            'delivery_address' => $customer->address,
            'scheduled_for' => Carbon::now()->addDays(3)->setTime(14, 0),
            'message_on_cake' => 'Congrats on Launch Day',
            'order_notes' => 'Mohon telepon 15 menit sebelum tiba.',
            'subtotal' => 440000,
            'delivery_fee' => 25000,
            'total_amount' => 465000,
            'midtrans_reference' => 'SIM-AQL-DEMO-002',
            'paid_at' => Carbon::now()->subHours(5),
        ]);

        $awaitingOrder->items()->create([
            'product_id' => $products['birthday-cake-custom']->id,
            'product_name' => $products['birthday-cake-custom']->name,
            'product_price' => $products['birthday-cake-custom']->price,
            'size' => 'Diameter 20 cm',
            'quantity' => 1,
            'scheduled_date' => Carbon::now()->addDays(3)->toDateString(),
            'scheduled_time' => '14:00',
            'custom_message' => 'Congrats on Launch Day',
            'notes' => 'Tambahkan kartu ucapan.',
            'line_total' => 295000,
        ]);

        $awaitingOrder->items()->create([
            'product_id' => $products['cheesecake']->id,
            'product_name' => $products['cheesecake']->name,
            'product_price' => $products['cheesecake']->price,
            'size' => 'Box Premium',
            'quantity' => 1,
            'scheduled_date' => Carbon::now()->addDays(3)->toDateString(),
            'scheduled_time' => '14:00',
            'custom_message' => null,
            'notes' => 'Gunakan pita warna rose gold.',
            'line_total' => 145000,
        ]);

        SystemNotification::query()->create([
            'user_id' => $admin->id,
            'title' => 'Pesanan auto-lunas menunggu keputusan',
            'message' => 'Pesanan AQL-DEMO-002 sudah dibayar dan menunggu konfirmasi kapasitas dapur.',
            'action_url' => '/admin/orders/'.$awaitingOrder->id,
        ]);

        SystemNotification::query()->create([
            'user_id' => $customer->id,
            'title' => 'Pembayaran berhasil',
            'message' => 'Pesanan AQL-DEMO-002 sudah dibayar dan menunggu konfirmasi admin.',
            'action_url' => '/orders/'.$awaitingOrder->id,
        ]);
    }
}
