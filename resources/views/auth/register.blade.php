@extends('layouts.app')

@section('title', 'Daftar | Aqlaya Cake')

@section('content')
<div class="bg-white rounded-[2.5rem] border border-stone-100 shadow-sm overflow-hidden flex flex-col md:flex-row-reverse min-h-[600px]">
    <!-- Right: Form (Reversed layout compared to Login) -->
    <div class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
        <div class="mb-10">
            <div class="text-[11px] font-bold tracking-widest text-mint-leaf uppercase mb-2">Customer Baru</div>
            <h1 class="font-serif text-3xl sm:text-4xl font-medium text-stone-900 mb-3">Membuat Akun Baru</h1>
            <p class="text-stone-500 text-sm leading-relaxed">Bergabunglah untuk mulai memesan cake spesial. Setelah daftar, akun customer akan aktif begitu disetujui admin.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" x-data="{ password: '', passwordConfirmation: '' }">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition @error('name') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @enderror" placeholder="Nama Anda" required>
                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">No. WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition @error('phone') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @enderror" placeholder="08xxxxxxxx" required>
                    @error('phone')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition @error('email') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @enderror" placeholder="Anda@email.com" required>
                @error('email')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Alamat Default <span class="text-stone-400 font-normal lowercase">(opsional)</span></label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition resize-none @error('address') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @enderror" placeholder="Alamat rumah/kantor untuk pengiriman">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Kata Sandi</label>
                    <input type="password" name="password" x-model="password" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition @if($errors->has('password')) border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @endif" placeholder="Min. 8 karakter" required>
                    @if($errors->has('password'))
                        <ul class="mt-2 space-y-1 text-xs text-red-600">
                            @foreach($errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-stone-700 uppercase tracking-wide mb-2">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" x-model="passwordConfirmation" class="w-full px-4 py-3 bg-stone-50 border rounded-xl text-sm outline-none transition @if($errors->has('password')) border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-stone-200 focus:bg-white focus:ring-2 focus:ring-mint-leaf/50 focus:border-mint-leaf @endif" placeholder="Ulangi kembali" required>
                </div>
                <div class="sm:col-span-2 rounded-xl border border-stone-200 bg-stone-50 px-4 py-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-stone-700">Panduan kata sandi</p>
                    <p class="mt-1 text-sm text-stone-600">Gunakan kata sandi yang aman sebelum akun didaftarkan.</p>
                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                        <div class="flex items-center gap-2" :class="password.length >= 8 ? 'text-emerald-700' : 'text-stone-500'">
                            <span class="h-2 w-2 rounded-full" :class="password.length >= 8 ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                            Minimal 8 karakter
                        </div>
                        <div class="flex items-center gap-2" :class="/[A-Za-z]/.test(password) ? 'text-emerald-700' : 'text-stone-500'">
                            <span class="h-2 w-2 rounded-full" :class="/[A-Za-z]/.test(password) ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                            Minimal 1 huruf
                        </div>
                        <div class="flex items-center gap-2" :class="/[0-9]/.test(password) ? 'text-emerald-700' : 'text-stone-500'">
                            <span class="h-2 w-2 rounded-full" :class="/[0-9]/.test(password) ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                            Minimal 1 angka
                        </div>
                        <div class="flex items-center gap-2" :class="passwordConfirmation.length > 0 && passwordConfirmation === password ? 'text-emerald-700' : 'text-stone-500'">
                            <span class="h-2 w-2 rounded-full" :class="passwordConfirmation.length > 0 && passwordConfirmation === password ? 'bg-emerald-500' : 'bg-stone-300'"></span>
                            Ulangi kata sandi yang sama
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="w-full py-4 bg-stone-900 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-honey-bronze hover:text-stone-900 transition-all duration-300 shadow-md">
                    Daftarkan Akun
                </button>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-stone-100 text-center text-sm text-stone-600">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-mint-leaf hover:text-stone-900 transition-colors">Masuk di sini</a>
        </div>
    </div>

    <!-- Left: Image Cover -->
    <div class="hidden md:block w-full md:w-1/2 relative bg-mint-leaf/20">
        <svg class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-50" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,0 Q50,100 100,0 V100 H0 Z" fill="#3d825c"/>
        </svg>
        <!-- Overlay Content -->
        <div class="absolute inset-0 flex flex-col items-center justify-center p-12 text-center">
            <div class="font-serif text-5xl text-stone-900 mb-6 opacity-20 transform -rotate-12">🍰</div>
            <h3 class="font-serif text-3xl font-medium text-stone-900 mb-4 leading-tight">Pengalaman Pesan<br>Lebih Mudah</h3>
            <p class="text-stone-700 text-sm max-w-sm">Simpan riwayat pesanan, kumpulkan referensi rasa, dan checkout lebih cepat di transaksi berikutnya.</p>
        </div>
    </div>
</div>
@endsection
