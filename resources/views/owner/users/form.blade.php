@extends('layouts.owner')

@section('title', ($user->exists ? 'Edit' : 'Tambah') . ' User & Admin — Owner Aqlaya Cake')
@section('page-title', $user->exists ? 'Edit User & Admin' : 'Tambah User & Admin')

@section('content')
    {{-- Back link --}}
    <a href="{{ route('owner.users.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar User
    </a>

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 sm:p-8">
            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-900">{{ $user->exists ? 'Perbarui Akun' : 'Akun Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $user->exists ? 'Perbarui informasi profil, hak akses (role), atau ganti password akun' : 'Buat akun customer atau administrator baru' }}</p>
            </div>

            <form method="POST" action="{{ $formAction }}" class="space-y-5">
                @csrf
                @if($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Nama user..." />
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="email@contoh.com" />
                </div>

                {{-- Role Selection --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Hak Akses (Role)</label>
                    <select name="role" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Customer (User Biasa)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator (Admin Toko)</option>
                    </select>
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="e.g. 08123456789" />
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Alamat Tinggal</label>
                    <textarea name="address" rows="3"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition resize-none" placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                </div>

                <hr class="border-slate-100 my-6">

                {{-- Passwords --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">
                            Password {{ $user->exists ? '(Isi untuk mengganti)' : '' }}
                        </label>
                        <input type="password" name="password" {{ $user->exists ? '' : 'required' }}
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Minimal 8 karakter..." />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">
                            Konfirmasi Password {{ $user->exists ? '(Isi untuk mengganti)' : '' }}
                        </label>
                        <input type="password" name="password_confirmation" {{ $user->exists ? '' : 'required' }}
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Ulangi password..." />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $user->exists ? 'Simpan Perubahan' : 'Buat Akun' }}
                    </button>
                    <a href="{{ route('owner.users.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
