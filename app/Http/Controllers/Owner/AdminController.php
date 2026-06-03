<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $admins = User::query()
            ->where('role', 'admin')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('owner.admins.index', compact('admins', 'search'));
    }

    public function create(): View
    {
        return view('owner.admins.form', [
            'admin' => new User(),
            'formAction' => route('owner.admins.store'),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'api_token' => Str::random(60),
        ]);

        return redirect()->route('owner.admins.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }

    public function edit(User $admin): View
    {
        // Make sure we only edit users with role 'admin'
        abort_unless($admin->role === 'admin', 404);

        return view('owner.admins.form', [
            'admin' => $admin,
            'formAction' => route('owner.admins.update', $admin),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, User $admin): RedirectResponse
    {
        abort_unless($admin->role === 'admin', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $admin->update($updateData);

        return redirect()->route('owner.admins.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy(User $admin): RedirectResponse
    {
        abort_unless($admin->role === 'admin', 404);

        $admin->delete();

        return redirect()->route('owner.admins.index')->with('success', 'Akun admin berhasil dihapus.');
    }
}
