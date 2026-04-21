<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\CartService;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly CartService $cartService,
    ) {
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email atau password belum sesuai.',
            ])->onlyInput('email');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->isRejected()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Pendaftaran akun customer kamu ditolak admin. Silakan hubungi admin toko untuk informasi lebih lanjut.',
            ])->onlyInput('email');
        }

        if ($user->isPendingApproval()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'Akun customer kamu masih menunggu persetujuan admin.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Pindahkan cart guest (session) ke DB setelah login
        $this->cartService->mergeSessionCartToDatabase();

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('home')
        );
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ]);

        $user = User::query()->create([
            ...$validated,
            'role' => 'customer',
            'is_approved' => false,
            'rejected_at' => null,
            'api_token' => Str::random(60),
        ]);

        $this->notificationService->notifyAdmins(
            'Customer baru menunggu persetujuan',
            "Akun {$user->name} baru saja mendaftar dan menunggu ACC admin.",
            route('admin.dashboard')
        );

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dibuat dan sedang menunggu persetujuan admin.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
