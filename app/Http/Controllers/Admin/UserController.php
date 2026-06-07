<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $prefix = $this->getRoutePrefix();
        $roles = $prefix === 'owner' ? ['customer', 'admin'] : ['customer'];

        $users = User::query()
            ->whereIn('role', $roles)
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

        return view("{$prefix}.users.index", compact('users', 'search'));
    }

    public function create(): View
    {
        $prefix = $this->getRoutePrefix();

        return view("{$prefix}.users.form", [
            'user' => new User(),
            'formAction' => route("{$prefix}.users.store"),
            'formMethod' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $prefix = $this->getRoutePrefix();
        
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($prefix === 'owner') {
            $rules['role'] = ['required', 'string', 'in:customer,admin'];
        }

        $validated = $request->validate($rules);

        User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => $prefix === 'owner' ? $validated['role'] : 'customer',
            'api_token' => Str::random(60),
        ]);

        return redirect()->route("{$prefix}.users.index")->with('success', 'User baru berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $prefix = $this->getRoutePrefix();
        $roles = $prefix === 'owner' ? ['customer', 'admin'] : ['customer'];
        abort_unless(in_array($user->role, $roles), 404);

        return view("{$prefix}.users.form", [
            'user' => $user,
            'formAction' => route("{$prefix}.users.update", $user),
            'formMethod' => 'PUT',
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $prefix = $this->getRoutePrefix();
        $roles = $prefix === 'owner' ? ['customer', 'admin'] : ['customer'];
        abort_unless(in_array($user->role, $roles), 404);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        if ($prefix === 'owner') {
            $rules['role'] = ['required', 'string', 'in:customer,admin'];
        }

        $validated = $request->validate($rules);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
        ];

        if ($prefix === 'owner') {
            $updateData['role'] = $validated['role'];
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route("{$prefix}.users.index")->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $prefix = $this->getRoutePrefix();
        $roles = $prefix === 'owner' ? ['customer', 'admin'] : ['customer'];
        abort_unless(in_array($user->role, $roles), 404);

        $user->delete();

        return redirect()->route("{$prefix}.users.index")->with('success', 'Akun user berhasil dihapus.');
    }
}
