@extends('layouts.app')

@section('title', 'Masuk | Aqlaya Cake')

@section('content')
<div class="bg-white rounded-[2.5rem] border border-stone-100 shadow-sm overflow-hidden flex flex-col md:flex-row min-h-[600px]">
    <!-- Left: Form -->
    <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
        <div class="mb-10">
            <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Customer & Admin</div>
            <h1 class="font-serif text-3xl sm:text-4xl font-medium text-stone-900 mb-3">Selamat Datang Kembali</h1>
            <p class="text-stone-500 text-sm leading-relaxed">Masuk ke akun Anda untuk melacak pesanan, melihat riwayat transaksi, atau mengelola katalog (khusus admin).</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition" placeholder="Anda@email.com" required>
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Kata Sandi</label>
                <input type="password" name="password" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf outline-none transition" placeholder="••••••••" required>
            </div>
            
            <div class="flex items-center mt-1">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-mint-leaf bg-stone-50 border-stone-300 rounded focus:ring-mint-leaf focus:ring-2">
                <label for="remember" class="ml-2 block text-sm text-stone-600">Ingat sesi login saya</label>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="w-full py-4 bg-stone-900 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-honey-bronze hover:text-stone-900 transition-all duration-300 shadow-md">
                    Masuk ke Akun
                </button>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="text-sm text-stone-600">
                Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-mint-leaf hover:text-stone-900 transition-colors">Daftar sekarang</a>
            </div>
        </div>

        <!-- Demo Accounts (For testing purposes) -->
        <div class="mt-8 bg-stone-50 p-4 rounded-xl border border-stone-100 text-xs text-stone-500 font-mono flex flex-col gap-1">
            <div class="font-sans font-bold text-stone-700 mb-1 uppercase tracking-wider text-[10px]">Demo Akun:</div>
            <div>Admin: <span class="text-stone-800">admin@aqlaya.test</span> / password</div>
            <div>Cust: <span class="text-stone-800">customer@aqlaya.test</span> / password</div>
        </div>

        <p class="mt-4 text-xs text-stone-500 leading-relaxed">
            Akun customer baru bisa dipakai login setelah disetujui admin dari dashboard.
        </p>
    </div>

    <!-- Right: Image Cover -->
    <div class="hidden md:block w-full md:w-1/2 relative bg-linen">
        <img src="{{ asset('images/hero.png') }}" alt="Aqlaya Cake Style" class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-80 object-center">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-stone-900/60 to-transparent flex flex-col justify-end p-12">
            <h3 class="font-serif text-3xl text-white mb-2 leading-tight">Kreasi Rasa<br>Kualitas Premium</h3>
            <p class="text-white/80 text-sm">Disiapkan dengan detail sempurna untuk momen spesial Anda.</p>
        </div>
    </div>
</div>
@endsection
