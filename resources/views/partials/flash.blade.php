<div class="fixed top-24 lg:top-28 left-1/2 z-[9999] w-[calc(100%-2rem)] max-w-md -translate-x-1/2 px-4 pointer-events-none"
     x-data="{ 
        showSuccess: {{ session('success') ? 'true' : 'false' }},
        showError: {{ session('error') ? 'true' : 'false' }},
        showValidation: {{ $errors->any() ? 'true' : 'false' }}
     }"
     x-init="
        if (showSuccess) { setTimeout(() => showSuccess = false, 6000); }
        if (showError) { setTimeout(() => showError = false, 6000); }
     ">
    
    {{-- Success Toast --}}
    @if(session('success'))
        <div x-show="showSuccess"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-[-1rem] scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-[-1rem] scale-95"
             class="pointer-events-auto mb-4 flex items-start gap-3 rounded-2xl border border-emerald-200/80 bg-white/95 p-4 text-sm text-emerald-950 shadow-[0_10px_30px_rgba(16,185,129,0.12)] backdrop-blur-md"
             x-cloak>
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-emerald-950">Berhasil</p>
                <p class="mt-0.5 text-emerald-800/90 leading-relaxed font-medium">{{ session('success') }}</p>
            </div>
            <button @click="showSuccess = false" class="text-emerald-400 hover:text-emerald-600 transition shrink-0 p-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Error Toast --}}
    @if(session('error'))
        <div x-show="showError"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-[-1rem] scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-[-1rem] scale-95"
             class="pointer-events-auto mb-4 flex items-start gap-3 rounded-2xl border border-red-200/80 bg-white/95 p-4 text-sm text-red-950 shadow-[0_10px_30px_rgba(239,68,68,0.12)] backdrop-blur-md"
             x-cloak>
            <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-red-950">Gagal</p>
                <p class="mt-0.5 text-red-850/90 leading-relaxed font-medium">{{ session('error') }}</p>
            </div>
            <button @click="showError = false" class="text-red-400 hover:text-red-600 transition shrink-0 p-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Validation Toast --}}
    @if($errors->any())
        <div x-show="showValidation"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-[-1rem] scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-[-1rem] scale-95"
             class="pointer-events-auto mb-4 rounded-2xl border border-amber-200/80 bg-white/95 p-4 text-sm text-amber-950 shadow-[0_10px_30px_rgba(245,158,11,0.12)] backdrop-blur-md"
             x-cloak>
            <div class="flex items-start gap-3">
                <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-amber-950">Periksa kembali data Anda</p>
                    @php($messages = $errors->all())
                    @if(count($messages) === 1)
                        <p class="mt-1 text-amber-800/90 leading-relaxed font-medium">{{ $messages[0] }}</p>
                    @else
                        <ul class="mt-2 space-y-1 text-amber-800/90 font-medium list-disc list-inside">
                            @foreach($messages as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button @click="showValidation = false" class="text-amber-400 hover:text-amber-600 transition shrink-0 p-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif
</div>
