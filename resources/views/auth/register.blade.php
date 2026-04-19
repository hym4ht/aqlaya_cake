@extends('layouts.app')

@section('title', 'Daftar | Aqlaya Cake')

@section('content')
<div class="max-w-2xl mx-auto mt-10 mb-20 bg-white rounded-[2rem] border border-mono-100 shadow-sm overflow-hidden min-h-[500px]">
    <div class="p-8 sm:p-12 lg:p-16 flex flex-col justify-center">
        <div class="mb-10 text-center">
            <h1 class="font-serif text-3xl sm:text-4xl font-light text-mono-900 mb-3">Buat Akun</h1>
            <p class="text-mono-500 text-sm leading-relaxed">Bergabung dan rasakan pengalaman berbelanja cake spesial yang elegan.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5" x-data="{ password: '', passwordConfirmation: '' }">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition @error('name') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @enderror" placeholder="Nama Anda" required>
                    @error('name')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">No. WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition @error('phone') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @enderror" placeholder="08xxxxxxxx" required>
                    @error('phone')
                        <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition @error('email') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @enderror" placeholder="Anda@email.com" required>
                @error('email')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Alamat Default <span class="text-mono-400 font-normal lowercase">(opsional)</span></label>
                <textarea name="address" rows="2" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition resize-none @error('address') border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @enderror" placeholder="Alamat pengiriman">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Kata Sandi</label>
                    <input type="password" name="password" x-model="password" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition @if($errors->has('password')) border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @endif" placeholder="Min. 8 karakter" required>
                    @if($errors->has('password'))
                        <ul class="mt-2 space-y-1 text-xs text-red-600">
                            @foreach($errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mono-700 uppercase tracking-wide mb-2">Ulangi Sandi</label>
                    <input type="password" name="password_confirmation" x-model="passwordConfirmation" class="w-full px-4 py-3 bg-mono-50 border rounded-xl text-sm outline-none transition @if($errors->has('password')) border-red-300 bg-red-50 focus:bg-white focus:ring-2 focus:ring-red-200 focus:border-red-400 @else border-mono-200 focus:bg-white focus:ring-2 focus:ring-pink-600/30 focus:border-pink-600 @endif" placeholder="Ulangi kembali" required>
                </div>
                <div class="sm:col-span-2 rounded-xl border border-mono-200 bg-mono-50 px-4 py-3">
                    <p class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        <span class="flex items-center gap-2" :class="password.length >= 8 ? 'text-pink-600' : 'text-mono-500'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="password.length >= 8 ? 'bg-pink-600' : 'bg-mono-300'"></span>
                            Min. 8 karakter
                        </span>
                        <span class="flex items-center gap-2" :class="/[A-Za-z]/.test(password) ? 'text-pink-600' : 'text-mono-500'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="/[A-Za-z]/.test(password) ? 'bg-pink-600' : 'bg-mono-300'"></span>
                            Ada huruf
                        </span>
                        <span class="flex items-center gap-2" :class="/[0-9]/.test(password) ? 'text-pink-600' : 'text-mono-500'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="/[0-9]/.test(password) ? 'bg-pink-600' : 'bg-mono-300'"></span>
                            Ada angka
                        </span>
                        <span class="flex items-center gap-2" :class="passwordConfirmation.length > 0 && passwordConfirmation === password ? 'text-pink-600' : 'text-mono-500'">
                            <span class="h-1.5 w-1.5 rounded-full" :class="passwordConfirmation.length > 0 && passwordConfirmation === password ? 'bg-pink-600' : 'bg-mono-300'"></span>
                            Sandi sama
                        </span>
                    </p>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="w-full py-4 bg-pink-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-pink-700 transition-all duration-300 shadow-md">
                    Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="mt-8 pt-8 border-t border-mono-100 text-center text-sm text-mono-600">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-pink-600 hover:text-pink-700 transition-colors">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
