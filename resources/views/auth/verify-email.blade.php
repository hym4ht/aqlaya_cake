@extends('layouts.app')

@section('title', 'Verifikasi Email | Aqlaya Cake')

@section('content')
<div class="max-w-2xl mx-auto mt-10 mb-20 bg-white rounded-[2rem] border border-mono-100 shadow-sm overflow-hidden min-h-[500px]">
    <div class="p-8 sm:p-12 lg:p-16 flex flex-col items-center justify-center text-center">

        {{-- Icon --}}
        <div class="w-20 h-20 rounded-full bg-pink-50 flex items-center justify-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <h1 class="font-serif text-3xl sm:text-4xl font-light text-mono-900 mb-3">Cek Email Kamu</h1>
        <p class="text-mono-500 text-sm leading-relaxed max-w-md">
            Kami sudah mengirimkan link verifikasi ke <strong class="text-mono-700">{{ Auth::user()->email }}</strong>.
            Klik link tersebut untuk mengaktifkan akunmu dan mulai berbelanja!
        </p>

        <p class="text-mono-400 text-xs mt-3">Tidak menemukan emailnya? Cek folder <strong>Spam/Junk</strong> kamu.</p>

        {{-- Status sukses resend --}}
        @if (session('status') == 'Link verifikasi sudah dikirim ulang ke emailmu!')
            <div class="mt-6 px-5 py-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">
                ✅ {{ session('status') }}
            </div>
        @endif

        <div class="mt-8 flex flex-col sm:flex-row gap-3 w-full max-w-sm">
            {{-- Tombol kirim ulang --}}
            <form method="POST" action="{{ route('verification.send') }}" class="flex-1" id="resend-form">
                @csrf
                <button type="submit" id="resend-btn" class="w-full py-3 bg-pink-600 text-white rounded-xl text-sm font-bold tracking-wide uppercase hover:bg-pink-700 transition-all duration-300 shadow-md disabled:bg-mono-200 disabled:text-mono-400 disabled:shadow-none disabled:cursor-not-allowed">
                    Kirim Ulang Email
                </button>
            </form>

            {{-- Tombol logout --}}
            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                @csrf
                <button type="submit" class="w-full py-3 bg-mono-100 text-mono-700 rounded-xl text-sm font-semibold hover:bg-mono-200 transition-all duration-300">
                    Keluar
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resendBtn = document.getElementById('resend-btn');
        const resendForm = document.getElementById('resend-form');
        const cooldownTime = 30; // 30 detik
        const storageKey = 'email_resend_cooldown';

        function startCooldown(remaining) {
            resendBtn.disabled = true;
            resendBtn.innerText = `Kirim Ulang (${remaining}s)`;
            
            const interval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    resendBtn.disabled = false;
                    resendBtn.innerText = 'Kirim Ulang Email';
                    localStorage.removeItem(storageKey);
                } else {
                    resendBtn.innerText = `Kirim Ulang (${remaining}s)`;
                }
            }, 1000);
        }

        // Cek jika baru registrasi atau baru saja klik resend
        const hasSessionStatus = @json(session('status') == 'Link verifikasi sudah dikirim ulang ke emailmu!');
        const justRegistered = @json(session('registered') === true);
        
        if ((hasSessionStatus || justRegistered) && !localStorage.getItem(storageKey)) {
            localStorage.setItem(storageKey, Date.now().toString());
        }

        // Cek apakah ada cooldown aktif saat load page
        const lastSent = localStorage.getItem(storageKey);
        if (lastSent) {
            const timePassed = Math.floor((Date.now() - parseInt(lastSent)) / 1000);
            if (timePassed < cooldownTime) {
                startCooldown(cooldownTime - timePassed);
            }
        }

        // Mulai cooldown saat form di-submit
        resendForm.addEventListener('submit', function () {
            localStorage.setItem(storageKey, Date.now().toString());
        });
    });
</script>
@endsection
