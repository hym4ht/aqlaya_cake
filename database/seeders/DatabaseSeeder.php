<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin Aqlaya',
            'email' => 'admin@aqlaya.test',
            'phone' => '081200000001',
            'address' => 'Jl. Raya Aqlaya No. 17, Bandung',
            'role' => 'admin',
            'is_approved' => true,
            'approved_at' => now(),
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);

        $customer = User::query()->create([
            'name' => 'Aya Customer',
            'email' => 'customer@aqlaya.test',
            'phone' => '081200000002',
            'address' => 'Jl. Melati No. 20, Bandung',
            'role' => 'customer',
            'is_approved' => true,
            'approved_at' => now(),
            'api_token' => Str::random(60),
            'password' => Hash::make('password'),
        ]);

        $categories = collect([
            ['name' => 'Kue Tart', 'slug' => 'kue-tart', 'description' => 'Cake custom untuk ulang tahun dan momen spesial.'],
            ['name' => 'Kue Kering', 'slug' => 'kue-kering', 'description' => 'Cookies premium untuk hampers dan camilan.'],
            ['name' => 'Pastry', 'slug' => 'pastry', 'description' => 'Pastry buttery untuk meeting dan sajian toko.'],
        ])->mapWithKeys(fn ($category) => [
            $category['slug'] => Category::query()->create($category),
        ]);

        $products = collect([
            [
                'category_id' => $categories['kue-tart']->id,
                'name' => 'Berry Bloom Signature',
                'slug' => 'berry-bloom-signature',
                'excerpt' => 'Cake stroberi-vanilla dengan frosting lembut dan dekor bunga edible.',
                'description' => 'Cocok untuk ulang tahun, bridal shower, dan perayaan kantor dengan pilihan ukuran fleksibel.',
                'price' => 285000,
                'stock' => 18,
                'is_active' => true,
                'is_best_seller' => true,
                'sizes' => ['Diameter 12 cm', 'Diameter 16 cm', 'Diameter 20 cm'],
                'image_url' => 'https://images.unsplash.com/photo-1535141192574-5d4897c12636?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category_id' => $categories['kue-tart']->id,
                'name' => 'Midnight Choco Party Cake',
                'slug' => 'midnight-choco-party-cake',
                'excerpt' => 'Dark chocolate cake dengan filling ganache dan dekor premium.',
                'description' => 'Pilihan favorit untuk pecinta cokelat dengan tampilan bold dan modern.',
                'price' => 325000,
                'stock' => 12,
                'is_active' => true,
                'is_best_seller' => true,
                'sizes' => ['Diameter 14 cm', 'Diameter 18 cm', 'Diameter 22 cm'],
                'image_url' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category_id' => $categories['kue-kering']->id,
                'name' => 'Butter Cookies Hampers Box',
                'slug' => 'butter-cookies-hampers-box',
                'excerpt' => 'Satu box berisi 4 varian cookies premium.',
                'description' => 'Tersedia untuk hampers kantor, open house, dan hadiah keluarga.',
                'price' => 145000,
                'stock' => 30,
                'is_active' => true,
                'is_best_seller' => true,
                'sizes' => ['Box Reguler', 'Box Premium'],
                'image_url' => 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category_id' => $categories['pastry']->id,
                'name' => 'Croissant Gift Set',
                'slug' => 'croissant-gift-set',
                'excerpt' => 'Set croissant butter dan almond untuk brunch box.',
                'description' => 'Pastry hangat dengan tekstur flaky yang cocok untuk corporate morning box.',
                'price' => 98000,
                'stock' => 25,
                'is_active' => true,
                'is_best_seller' => true,
                'sizes' => ['Isi 4', 'Isi 6', 'Isi 12'],
                'image_url' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category_id' => $categories['kue-tart']->id,
                'name' => 'Pastel Garden Cake',
                'slug' => 'pastel-garden-cake',
                'excerpt' => 'Cake tema floral pastel untuk acara intimate.',
                'description' => 'Dekorasi kalem dengan custom ucapan dan warna yang bisa disesuaikan.',
                'price' => 295000,
                'stock' => 8,
                'is_active' => true,
                'is_best_seller' => true,
                'sizes' => ['Diameter 12 cm', 'Diameter 16 cm', 'Diameter 20 cm'],
                'image_url' => 'https://images.unsplash.com/photo-1464349095431-e9a21285b5f3?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'category_id' => $categories['pastry']->id,
                'name' => 'Cheese Danish Tray',
                'slug' => 'cheese-danish-tray',
                'excerpt' => 'Pastry tray untuk rapat, pantry kantor, dan meeting pagi.',
                'description' => 'Rasa gurih manis seimbang dengan pilihan tray untuk tim kecil maupun besar.',
                'price' => 170000,
                'stock' => 14,
                'is_active' => false,
                'is_best_seller' => false,
                'sizes' => ['Tray 10 pcs', 'Tray 20 pcs'],
                'image_url' => 'https://images.unsplash.com/photo-1517433367423-c7e5b0f35086?auto=format&fit=crop&w=1200&q=80',
            ],
        ])->mapWithKeys(fn ($product) => [
            $product['slug'] => Product::query()->create($product),
        ]);

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
                'product_id' => $products['berry-bloom-signature']->id,
                'product_name' => $products['berry-bloom-signature']->name,
                'product_price' => $products['berry-bloom-signature']->price,
                'size' => 'Diameter 16 cm',
                'quantity' => 1,
                'scheduled_date' => Carbon::now()->subDay()->toDateString(),
                'scheduled_time' => '11:00',
                'custom_message' => 'Happy Birthday Aya',
                'notes' => 'Warna pink lembut.',
                'line_total' => 285000,
            ],
            [
                'product_id' => $products['midnight-choco-party-cake']->id,
                'product_name' => $products['midnight-choco-party-cake']->name,
                'product_price' => $products['midnight-choco-party-cake']->price,
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
            'product_id' => $products['berry-bloom-signature']->id,
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
            'product_id' => $products['pastel-garden-cake']->id,
            'product_name' => $products['pastel-garden-cake']->name,
            'product_price' => $products['pastel-garden-cake']->price,
            'size' => 'Diameter 20 cm',
            'quantity' => 1,
            'scheduled_date' => Carbon::now()->addDays(3)->toDateString(),
            'scheduled_time' => '14:00',
            'custom_message' => 'Congrats on Launch Day',
            'notes' => 'Tambahkan kartu ucapan.',
            'line_total' => 295000,
        ]);

        $awaitingOrder->items()->create([
            'product_id' => $products['butter-cookies-hampers-box']->id,
            'product_name' => $products['butter-cookies-hampers-box']->name,
            'product_price' => $products['butter-cookies-hampers-box']->price,
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
