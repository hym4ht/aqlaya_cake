<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads_catalogue_content(): void
    {
        $product = $this->makeProduct();

        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Sistem pemesanan kue')
            ->assertSee($product->name);
    }

    public function test_customer_cannot_choose_date_before_lead_time(): void
    {
        $product = $this->makeProduct();
        $customer = User::query()->create([
            'name' => 'Lead Time Tester',
            'email' => 'leadtime@example.test',
            'role' => 'customer',
            'password' => 'password123',
        ]);

        $response = $this->actingAs($customer)->post(route('cart.store', $product), [
            'quantity' => 1,
            'size' => $product->sizes[0],
            'custom_message' => 'Selamat',
            'scheduled_date' => now()->addDay()->toDateString(),
            'scheduled_time' => '10:00',
            'notes' => 'Mohon cepat',
        ]);

        $response->assertSessionHasErrors('scheduled_date');
        $this->assertSame([], session(CartService::SESSION_KEY, []));
    }

    public function test_midtrans_webhook_marks_order_paid_and_notifies_admin(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'role' => 'admin',
            'password' => 'password123',
        ]);

        $customer = User::query()->create([
            'name' => 'Customer',
            'email' => 'customer@example.test',
            'role' => 'customer',
            'password' => 'password123',
        ]);

        $order = Order::query()->create([
            'user_id' => $customer->id,
            'order_code' => 'AQL-WEBHOOK-001',
            'status' => Order::STATUS_PENDING_PAYMENT,
            'payment_status' => Order::PAYMENT_UNPAID,
            'shipping_method' => 'pickup',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '0812',
            'subtotal' => 100000,
            'delivery_fee' => 0,
            'total_amount' => 100000,
        ]);

        $response = $this->postJson(route('midtrans.webhook'), [
            'order_code' => $order->order_code,
            'transaction_status' => 'settlement',
            'reference' => 'MID-12345',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_AWAITING_CONFIRMATION,
            'payment_status' => Order::PAYMENT_PAID,
            'midtrans_reference' => 'MID-12345',
        ]);
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $admin->id,
            'title' => 'Pesanan baru sudah dibayar',
        ]);
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $customer->id,
            'title' => 'Pembayaran berhasil',
        ]);
    }

    public function test_admin_can_accept_paid_order_and_stock_is_reduced(): void
    {
        $product = $this->makeProduct(stock: 5);

        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'kitchen-admin@example.test',
            'role' => 'admin',
            'password' => 'password123',
        ]);

        $customer = User::query()->create([
            'name' => 'Customer',
            'email' => 'kitchen-customer@example.test',
            'role' => 'customer',
            'password' => 'password123',
        ]);

        $order = Order::query()->create([
            'user_id' => $customer->id,
            'order_code' => 'AQL-ACCEPT-001',
            'status' => Order::STATUS_AWAITING_CONFIRMATION,
            'payment_status' => Order::PAYMENT_PAID,
            'shipping_method' => 'pickup',
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '0812',
            'subtotal' => 200000,
            'delivery_fee' => 0,
            'total_amount' => 200000,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'size' => $product->sizes[0],
            'quantity' => 2,
            'scheduled_date' => now()->addDays(3)->toDateString(),
            'scheduled_time' => '11:00',
            'line_total' => 200000,
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.orders.decide', $order), [
            'decision' => 'accept',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PROCESSING,
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
    }

    private function makeProduct(int $stock = 10): Product
    {
        $category = Category::query()->create([
            'name' => 'Kue Tart',
            'slug' => 'kue-tart',
        ]);

        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Rose Velvet Cake',
            'slug' => 'rose-velvet-cake',
            'excerpt' => 'Cake demo untuk pengujian.',
            'description' => 'Deskripsi produk demo untuk pengujian feature.',
            'price' => 100000,
            'stock' => $stock,
            'is_active' => true,
            'sizes' => ['Diameter 12 cm', 'Diameter 16 cm'],
            'image_url' => 'https://example.com/cake.jpg',
        ]);
    }
}
