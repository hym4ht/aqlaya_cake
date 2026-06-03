<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerAdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_admins_list(): void
    {
        $owner = $this->makeOwner();
        $admin1 = $this->makeAdmin('Admin Satu', 'admin1@example.com');
        $admin2 = $this->makeAdmin('Admin Dua', 'admin2@example.com');
        $customer = $this->makeCustomer('Customer', 'customer@example.com');

        $response = $this->actingAs($owner)->get(route('owner.admins.index'));

        $response
            ->assertOk()
            ->assertSee('Admin Satu')
            ->assertSee('admin1@example.com')
            ->assertSee('Admin Dua')
            ->assertSee('admin2@example.com')
            ->assertDontSee('customer@example.com');
    }

    public function test_owner_can_search_admins(): void
    {
        $owner = $this->makeOwner();
        $admin1 = $this->makeAdmin('Dina Admin', 'dina@example.com');
        $admin2 = $this->makeAdmin('Budi Admin', 'budi@example.com');

        $response = $this->actingAs($owner)->get(route('owner.admins.index', [
            'search' => 'Dina',
        ]));

        $response
            ->assertOk()
            ->assertSee('Dina Admin')
            ->assertDontSee('Budi Admin');
    }

    public function test_owner_can_create_new_admin(): void
    {
        $owner = $this->makeOwner();

        $response = $this->actingAs($owner)->post(route('owner.admins.store'), [
            'name' => 'Admin Baru',
            'email' => 'newadmin@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertRedirect(route('owner.admins.index'))
            ->assertSessionHas('success', 'Admin baru berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru',
            'email' => 'newadmin@example.com',
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
        ]);
    }

    public function test_owner_can_update_existing_admin(): void
    {
        $owner = $this->makeOwner();
        $admin = $this->makeAdmin('Admin Lama', 'oldadmin@example.com');

        // Update profile details without changing password
        $response = $this->actingAs($owner)->put(route('owner.admins.update', $admin), [
            'name' => 'Admin Diupdate',
            'email' => 'updatedadmin@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
        ]);

        $response
            ->assertRedirect(route('owner.admins.index'))
            ->assertSessionHas('success', 'Data admin berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Admin Diupdate',
            'email' => 'updatedadmin@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
        ]);

        // Update profile details and change password
        $response = $this->actingAs($owner)->put(route('owner.admins.update', $admin), [
            'name' => 'Admin Diupdate',
            'email' => 'updatedadmin@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response
            ->assertRedirect(route('owner.admins.index'))
            ->assertSessionHas('success', 'Data admin berhasil diperbarui.');
    }

    public function test_owner_can_delete_admin(): void
    {
        $owner = $this->makeOwner();
        $admin = $this->makeAdmin('Admin Hapus', 'deleteadmin@example.com');

        $response = $this->actingAs($owner)->delete(route('owner.admins.destroy', $admin));

        $response
            ->assertRedirect(route('owner.admins.index'))
            ->assertSessionHas('success', 'Akun admin berhasil dihapus.');

        $this->assertDatabaseMissing('users', [
            'id' => $admin->id,
        ]);
    }

    public function test_admin_cannot_access_owner_admin_management(): void
    {
        $admin = $this->makeAdmin('Admin Biasa', 'adminbiasa@example.com');
        $otherAdmin = $this->makeAdmin('Target Admin', 'targetadmin@example.com');

        // Can't view list
        $response = $this->actingAs($admin)->get(route('owner.admins.index'));
        $response->assertForbidden();

        // Can't view create form
        $response = $this->actingAs($admin)->get(route('owner.admins.create'));
        $response->assertForbidden();

        // Can't store
        $response = $this->actingAs($admin)->post(route('owner.admins.store'), [
            'name' => 'Hacker Admin',
            'email' => 'hack@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertForbidden();

        // Can't edit
        $response = $this->actingAs($admin)->get(route('owner.admins.edit', $otherAdmin));
        $response->assertForbidden();

        // Can't update
        $response = $this->actingAs($admin)->put(route('owner.admins.update', $otherAdmin), [
            'name' => 'Hacker Admin Updated',
            'email' => 'hack@example.com',
        ]);
        $response->assertForbidden();

        // Can't delete
        $response = $this->actingAs($admin)->delete(route('owner.admins.destroy', $otherAdmin));
        $response->assertForbidden();
    }

    public function test_customer_cannot_access_owner_admin_management(): void
    {
        $customer = $this->makeCustomer('Customer Biasa', 'customerbiasa@example.com');
        $targetAdmin = $this->makeAdmin('Target Admin', 'targetadmin@example.com');

        $response = $this->actingAs($customer)->get(route('owner.admins.index'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->get(route('owner.admins.create'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->post(route('owner.admins.store'), []);
        $response->assertForbidden();

        $response = $this->actingAs($customer)->get(route('owner.admins.edit', $targetAdmin));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->put(route('owner.admins.update', $targetAdmin), []);
        $response->assertForbidden();

        $response = $this->actingAs($customer)->delete(route('owner.admins.destroy', $targetAdmin));
        $response->assertForbidden();
    }

    private function makeOwner(): User
    {
        return User::query()->create([
            'name' => 'Owner',
            'email' => uniqid('owner') . '@example.test',
            'role' => 'owner',
            'password' => 'password123',
        ]);
    }

    private function makeAdmin(string $name, string $email): User
    {
        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'role' => 'admin',
            'password' => 'password123',
        ]);
    }

    private function makeCustomer(string $name, string $email): User
    {
        return User::query()->create([
            'name' => $name,
            'email' => $email,
            'role' => 'customer',
            'password' => 'password123',
        ]);
    }
}
