<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class CustomerApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_page_shows_password_requirements(): void
    {
        $response = $this->get(route('register'));

        $response
            ->assertOk()
            ->assertSee('Panduan kata sandi')
            ->assertSee('Minimal 8 karakter')
            ->assertSee('Minimal 1 huruf')
            ->assertSee('Minimal 1 angka')
            ->assertSee('Ulangi kata sandi yang sama');
    }

    public function test_customer_registration_creates_pending_account_and_redirects_to_login(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Customer Baru',
            'email' => 'baru@example.test',
            'phone' => '081234567890',
            'address' => 'Jl. Mawar No. 10',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', 'Akun berhasil dibuat dan sedang menunggu persetujuan admin.');

        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'baru@example.test',
            'role' => 'customer',
            'is_approved' => false,
        ]);
    }

    public function test_pending_customer_cannot_login_until_approved(): void
    {
        User::query()->create([
            'name' => 'Pending Customer',
            'email' => 'pending@example.test',
            'role' => 'customer',
            'is_approved' => false,
            'password' => 'Password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'pending@example.test',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Akun customer kamu masih menunggu persetujuan admin.',
        ]);
        $this->assertGuest();
    }

    public function test_rejected_customer_cannot_login_and_sees_rejection_message(): void
    {
        User::query()->create([
            'name' => 'Rejected Customer',
            'email' => 'rejected@example.test',
            'role' => 'customer',
            'is_approved' => false,
            'rejected_at' => now(),
            'password' => 'Password123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'rejected@example.test',
            'password' => 'Password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Pendaftaran akun customer kamu ditolak admin. Silakan hubungi admin toko untuk informasi lebih lanjut.',
        ]);
        $this->assertGuest();
    }

    public function test_registration_validation_messages_are_displayed_in_indonesian(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => '',
            'email' => 'bukan-email',
            'phone' => '',
            'password' => 'abc',
            'password_confirmation' => 'xyz',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors([
            'name' => 'Nama lengkap wajib diisi.',
            'email' => 'Alamat email harus berupa alamat email yang valid.',
            'phone' => 'Nomor WhatsApp wajib diisi.',
            'password' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $messages = session('errors')->getBag('default')->all();

        $this->assertContains('Kata sandi minimal berisi 8 karakter.', $messages);
        $this->assertContains('Kata sandi harus mengandung setidaknya satu angka.', $messages);
        $this->assertFalse(collect($messages)->contains(fn (string $message) => str_contains($message, 'validation.')));

        $passwordMessages = Validator::make(
            ['password' => '12345678'],
            ['password' => ['required', Password::min(8)->letters()->numbers()]]
        )->errors()->all();

        $this->assertContains('Kata sandi harus mengandung setidaknya satu huruf.', $passwordMessages);
    }

    public function test_admin_can_approve_pending_customer_from_dashboard(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'role' => 'admin',
            'password' => 'Password123',
        ]);

        $customer = User::query()->create([
            'name' => 'Pending Customer',
            'email' => 'pending@example.test',
            'role' => 'customer',
            'is_approved' => false,
            'password' => 'Password123',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.customers.decide', $customer), [
            'decision' => 'accept',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun customer berhasil disetujui.');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'is_approved' => true,
        ]);
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $customer->id,
            'title' => 'Akun customer disetujui',
        ]);
    }

    public function test_admin_can_reject_pending_customer_from_dashboard(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin-reject@example.test',
            'role' => 'admin',
            'password' => 'Password123',
        ]);

        $customer = User::query()->create([
            'name' => 'Pending Customer',
            'email' => 'reject-customer@example.test',
            'role' => 'customer',
            'is_approved' => false,
            'password' => 'Password123',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.customers.decide', $customer), [
            'decision' => 'reject',
        ]);

        $response
            ->assertRedirect()
            ->assertSessionHas('success', 'Akun customer berhasil ditolak.');

        $this->assertDatabaseHas('users', [
            'id' => $customer->id,
            'is_approved' => false,
        ]);
        $this->assertDatabaseHas('system_notifications', [
            'user_id' => $customer->id,
            'title' => 'Pendaftaran akun ditolak',
        ]);
        $this->assertNotNull($customer->fresh()->rejected_at);
    }
}
