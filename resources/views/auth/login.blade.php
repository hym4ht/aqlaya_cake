@extends('layouts.app')

@section('title', 'Masuk | Aqlaya Cake')

@section('content')
<div class="bg-white rounded-[2rem] border border-mono-100 shadow-sm overflow-hidden flex flex-col md:flex-row min-h-[600px]">
    <!-- Left: Form -->
    <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
        <div class="mb-10">
            <h1 class="font-serif text-3xl sm:text-4xl font-light text-mono-900 mb-3">Selamat Datang</h1>
            <p class="text-mono-500 text-sm leading-relaxed">Masuk ke akun Anda untuk pengalaman berbelanja yang lebih personal dan mengelola pesanan.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-mono-50 border border-mono-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 outline-none transition" placeholder="Anda@email.com" required>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Kata Sandi</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-mono-50 border border-mono-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 outline-none transition" placeholder="••••••••" required>
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

        <div class="mt-8 pt-8 border-t border-mono-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-mono-600">
                Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-pink-600 hover:text-pink-700 transition-colors">Daftar sekarang</a>
            </div>
        </div>

        <p class="mt-4 text-[11px] text-mono-400 leading-relaxed uppercase tracking-wider">
            Akun customer baru bisa dipakai login setelah diverifikasi admin.
        </p>
    </div>

    <!-- Right: Image Cover -->
    <div class="hidden md:block w-full md:w-1/2 relative bg-mono-50">
        <img src="{{ asset('images/hero1.png') }}" alt="Aqlaya Cake Style" class="absolute inset-0 w-full h-full object-cover">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex flex-col justify-end p-12">
            <h3 class="font-serif text-4xl text-white mb-2 leading-tight">Kreasi Rasa<br>Kualitas Premium</h3>
            <p class="text-white/80 text-sm font-light">Elegansi sederhana untuk momen spesial Anda.</p>
        </div>
    </div>
</div>
@endsection
