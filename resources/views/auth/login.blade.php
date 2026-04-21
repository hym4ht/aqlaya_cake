@extends('layouts.app')

@section('title', 'Masuk | Aqlaya Cake')

@section('content')
<div class="max-w-md mx-auto mt-10 mb-20 bg-white rounded-[2rem] border border-mono-100 shadow-sm overflow-hidden">
    <div class="p-8 sm:p-12 flex flex-col justify-center">
        <div class="mb-10 text-center">
            <h1 class="font-serif text-3xl sm:text-4xl font-light text-mono-900 mb-3">Selamat Datang</h1>
            <p class="text-mono-500 text-sm leading-relaxed">Masuk ke akun Anda untuk pengalaman berbelanja yang lebih personal.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-mono-50 border border-mono-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 outline-none transition" placeholder="Anda@email.com" required>
            </div>
            
            <div x-data="{ show: false }">
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Kata Sandi</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="password" class="w-full px-4 py-3 bg-mono-50 border border-mono-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 outline-none transition pr-12" placeholder="••••••••" required>
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-mono-400 hover:text-pink-600 transition focus:outline-none">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="flex items-center mt-1">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-pink-600 bg-mono-50 border-mono-300 rounded focus:ring-pink-600 focus:ring-2">
                <label for="remember" class="ml-2 block text-sm text-mono-600">Ingat sesi saya</label>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="w-full py-4 bg-pink-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-pink-700 transition-all duration-300 shadow-md">
                    Masuk ke Akun
                </button>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-mono-100 flex flex-col items-center justify-center gap-4">
            <div class="text-sm text-mono-600 text-center">
                Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-pink-600 hover:text-pink-700 transition-colors">Daftar sekarang</a>
            </div>
        </div>

        <div class="mt-6 text-center">
            <p class="text-[11px] text-mono-400 leading-relaxed uppercase tracking-wider">
                Akun customer baru bisa dipakai login setelah diverifikasi admin.
            </p>
        </div>
    </div>
</div>
@endsection
