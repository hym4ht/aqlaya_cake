<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_list(): void
    {
        $admin = $this->makeAdmin();
        $user1 = $this->makeCustomer('User Satu', 'user1@example.com');
        $user2 = $this->makeCustomer('User Dua', 'user2@example.com');
        $otherAdmin = $this->makeAdmin('Lain Admin', 'otheradmin@example.com');

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response
            ->assertOk()
            ->assertSee('User Satu')
            ->assertSee('user1@example.com')
            ->assertSee('User Dua')
            ->assertSee('user2@example.com')
            ->assertDontSee('otheradmin@example.com');
    }

    public function test_admin_can_search_users(): void
    {
        $admin = $this->makeAdmin();
        $user1 = $this->makeCustomer('Dina User', 'dina@example.com');
        $user2 = $this->makeCustomer('Budi User', 'budi@example.com');

        $response = $this->actingAs($admin)->get(route('admin.users.index', [
            'search' => 'Dina',
        ]));

        $response
            ->assertOk()
            ->assertSee('Dina User')
            ->assertDontSee('Budi User');
    }

    public function test_admin_can_create_new_user(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'User Baru',
            'email' => 'newuser@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'User baru berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'User Baru',
            'email' => 'newuser@example.com',
            'role' => 'customer',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
        ]);
    }

    public function test_admin_can_update_existing_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeCustomer('User Lama', 'olduser@example.com');

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'User Diupdate',
            'email' => 'updateduser@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
        ]);

        $response
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'Data user berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Diupdate',
            'email' => 'updateduser@example.com',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
        ]);
    }

    public function test_admin_can_delete_user(): void
    {
        $admin = $this->makeAdmin();
        $user = $this->makeCustomer('User Hapus', 'deleteuser@example.com');

        $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

        $response
            ->assertRedirect(route('admin.users.index'))
            ->assertSessionHas('success', 'Akun user berhasil dihapus.');

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_owner_can_view_all_users_and_admins(): void
    {
        $owner = $this->makeOwner();
        $user1 = $this->makeCustomer('User Satu', 'user1@example.com');
        $admin1 = $this->makeAdmin('Admin Satu', 'admin1@example.com');

        $response = $this->actingAs($owner)->get(route('owner.users.index'));

        $response
            ->assertOk()
            ->assertSee('User Satu')
            ->assertSee('user1@example.com')
            ->assertSee('Admin Satu')
            ->assertSee('admin1@example.com');
    }

    public function test_owner_can_create_new_user(): void
    {
        $owner = $this->makeOwner();

        $response = $this->actingAs($owner)->post(route('owner.users.store'), [
            'name' => 'User Baru Owner',
            'email' => 'newuserowner@example.com',
            'phone' => '081234567890',
            'address' => 'Jl. Test No. 1',
            'role' => 'customer',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertRedirect(route('owner.users.index'))
            ->assertSessionHas('success', 'User baru berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'User Baru Owner',
            'email' => 'newuserowner@example.com',
            'role' => 'customer',
        ]);
    }

    public function test_owner_can_create_new_admin(): void
    {
        $owner = $this->makeOwner();

        $response = $this->actingAs($owner)->post(route('owner.users.store'), [
            'name' => 'Admin Baru Owner',
            'email' => 'newadminowner@example.com',
            'phone' => '081234567891',
            'address' => 'Jl. Test No. 2',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response
            ->assertRedirect(route('owner.users.index'))
            ->assertSessionHas('success', 'User baru berhasil ditambahkan.');

        $this->assertDatabaseHas('users', [
            'name' => 'Admin Baru Owner',
            'email' => 'newadminowner@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_owner_can_update_user_and_admin_including_role(): void
    {
        $owner = $this->makeOwner();
        $user = $this->makeCustomer('User Lama', 'olduser@example.com');

        $response = $this->actingAs($owner)->put(route('owner.users.update', $user), [
            'name' => 'User Diupdate Jadi Admin',
            'email' => 'updateduser@example.com',
            'role' => 'admin',
            'phone' => '08987654321',
            'address' => 'Jl. Test Baru No. 2',
        ]);

        $response
            ->assertRedirect(route('owner.users.index'))
            ->assertSessionHas('success', 'Data user berhasil diperbarui.');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Diupdate Jadi Admin',
            'role' => 'admin',
        ]);
    }

    public function test_owner_can_delete_user_and_admin(): void
    {
        $owner = $this->makeOwner();
        $adminToKill = $this->makeAdmin('Target Admin', 'killme@example.com');

        $response = $this->actingAs($owner)->delete(route('owner.users.destroy', $adminToKill));

        $response
            ->assertRedirect(route('owner.users.index'))
            ->assertSessionHas('success', 'Akun user berhasil dihapus.');

        $this->assertDatabaseMissing('users', [
            'id' => $adminToKill->id,
        ]);
    }

    public function test_customer_cannot_access_user_management(): void
    {
        $customer = $this->makeCustomer('Customer Biasa', 'customerbiasa@example.com');
        $targetCustomer = $this->makeCustomer('Target Customer', 'targetcustomer@example.com');

        // Admin routes
        $response = $this->actingAs($customer)->get(route('admin.users.index'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->get(route('admin.users.create'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->post(route('admin.users.store'), []);
        $response->assertForbidden();

        // Owner routes
        $response = $this->actingAs($customer)->get(route('owner.users.index'));
        $response->assertForbidden();

        $response = $this->actingAs($customer)->delete(route('owner.users.destroy', $targetCustomer));
        $response->assertForbidden();
    }

    public function test_admin_can_export_pdf_report(): void
    {
        $admin = $this->makeAdmin();

        $response = $this->actingAs($admin)->get(route('admin.reports.export-pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_owner_can_export_pdf_report(): void
    {
        $owner = $this->makeOwner();

        $response = $this->actingAs($owner)->get(route('owner.reports.export-pdf'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
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

    private function makeAdmin(string $name = 'Admin', string $email = 'admin@example.com'): User
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
