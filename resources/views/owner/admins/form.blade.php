@extends('layouts.owner')

@section('title', ($admin->exists ? 'Edit' : 'Tambah') . ' Admin — Owner Aqlaya Cake')
@section('page-title', $admin->exists ? 'Edit Admin' : 'Tambah Admin')

@section('content')
    {{-- Back link --}}
    <a href="{{ route('owner.admins.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-800 transition mb-6 group">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Admin
    </a>

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6 sm:p-8">
            {{-- Header --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-slate-900">{{ $admin->exists ? 'Perbarui Akun Admin' : 'Akun Admin Baru' }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $admin->exists ? 'Perbarui informasi profil atau ganti password akun administrator' : 'Buat akun administrator baru untuk membantu pengelolaan toko' }}</p>
            </div>

            <form method="POST" action="{{ $formAction }}" class="space-y-5">
                @csrf
                @if($formMethod !== 'POST')
                    @method($formMethod)
                @endif

                {{-- Name --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Nama admin..." />
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="email@contoh.com" />
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="e.g. 08123456789" />
                </div>

                {{-- Address --}}
                <div>
                    <label class="block text-xs font-medium text-slate-500 mb-1.5">Alamat Tinggal</label>
                    <textarea name="address" rows="3"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition resize-none" placeholder="Alamat lengkap...">{{ old('address', $admin->address) }}</textarea>
                </div>

                <hr class="border-slate-100 my-6">

                {{-- Passwords --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">
                            Password {{ $admin->exists ? '(Isi untuk mengganti)' : '' }}
                        </label>
                        <input type="password" name="password" {{ $admin->exists ? '' : 'required' }}
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Minimal 8 karakter..." />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1.5">
                            Konfirmasi Password {{ $admin->exists ? '(Isi untuk mengganti)' : '' }}
                        </label>
                        <input type="password" name="password_confirmation" {{ $admin->exists ? '' : 'required' }}
                            class="w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-700 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition" placeholder="Ulangi password..." />
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-medium hover:bg-amber-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $admin->exists ? 'Simpan Perubahan' : 'Buat Akun Admin' }}
                    </button>
                    <a href="{{ route('owner.admins.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
