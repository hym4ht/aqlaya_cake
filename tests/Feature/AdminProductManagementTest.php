<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_products(): void
    {
        $admin = $this->makeAdmin();
        $category = Category::query()->create([
            'name' => 'Cake',
            'slug' => 'cake',
        ]);

        $match = $this->makeProduct($category, 'Rose Velvet Cake');
        $other = $this->makeProduct($category, 'Matcha Roll');

        $response = $this->actingAs($admin)->get(route('admin.products.index', [
            'search' => 'Rose',
        ]));

        $response
            ->assertOk()
            ->assertSee($match->name)
            ->assertDontSee($other->name);
    }

    public function test_admin_can_bulk_delete_products(): void
    {
        $admin = $this->makeAdmin();
        $category = Category::query()->create([
            'name' => 'Cake',
            'slug' => 'cake',
        ]);

        $first = $this->makeProduct($category, 'Produk Hapus Satu');
        $second = $this->makeProduct($category, 'Produk Hapus Dua');
        $kept = $this->makeProduct($category, 'Produk Tetap');

        $response = $this->actingAs($admin)->delete(route('admin.products.bulk-destroy'), [
            'product_ids' => [$first->id, $second->id],
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', '2 produk berhasil dihapus.');

        $this->assertDatabaseMissing('products', ['id' => $first->id]);
        $this->assertDatabaseMissing('products', ['id' => $second->id]);
        $this->assertDatabaseHas('products', ['id' => $kept->id]);
    }

    private function makeAdmin(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => uniqid('admin') . '@example.test',
            'role' => 'admin',
            'password' => 'Password123',
        ]);
    }

    private function makeProduct(Category $category, string $name): Product
    {
        return Product::query()->create([
            'category_id' => $category->id,
            'name' => $name,
            'slug' => str($name)->slug(),
            'excerpt' => 'Produk untuk pengujian.',
            'description' => 'Deskripsi produk untuk pengujian.',
            'price' => 100000,
            'stock' => 10,
            'is_active' => true,
            'product_type' => 'pre_order',
            'lead_time_days' => 2,
            'sizes' => ['Diameter 12 cm'],
        ]);
    }
}
