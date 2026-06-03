@extends('layouts.owner')

@section('title', 'Kelola Admin — Owner Aqlaya Cake')
@section('page-title', 'Kelola Admin')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-slate-900">Kelola Admin</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar pengguna dengan hak akses administrator</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 sm:items-center">
            <a href="{{ route('owner.admins.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-amber-600 text-white text-sm font-medium hover:bg-amber-700 transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Tambah Admin
            </a>
        </div>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 p-5 mb-6">
        <form method="GET" action="{{ route('owner.admins.index') }}" class="flex flex-col sm:flex-row items-end gap-3">
            <div class="flex-1 w-full">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari Admin</label>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.1-5.4a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau no. telepon..."
                        class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3.5 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-slate-400 focus:ring-2 focus:ring-slate-200 outline-none transition">
                </div>
            </div>
            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition shrink-0">
                Cari
            </button>
            @if($search !== '')
                <a href="{{ route('owner.admins.index') }}" class="w-full sm:w-auto text-center px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium text-slate-600 hover:bg-slate-50 transition shrink-0">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-medium text-slate-400 uppercase tracking-wider border-b border-slate-100 bg-slate-50/50">
                        <th class="px-6 py-3.5">Nama</th>
                        <th class="px-6 py-3.5">Email</th>
                        <th class="px-6 py-3.5">No. Telepon</th>
                        <th class="px-6 py-3.5">Alamat</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-3.5">
                                <p class="font-medium text-slate-800">{{ $admin->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">Terdaftar {{ $admin->created_at->format('d M Y') }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                {{ $admin->email }}
                            </td>
                            <td class="px-6 py-3.5 text-slate-600">
                                {{ $admin->phone ?? '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-slate-600 max-w-xs truncate">
                                {{ $admin->address ?? '—' }}
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('owner.admins.edit', $admin) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 bg-slate-50 hover:bg-slate-100 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('owner.admins.destroy', $admin) }}" class="inline" onsubmit="return confirm('Hapus akun admin ini? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                @if($search !== '')
                                    <p class="text-sm text-slate-400 mb-3">Tidak ada admin yang cocok dengan pencarian.</p>
                                    <a href="{{ route('owner.admins.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-900 text-white text-xs font-medium hover:bg-slate-800 transition">
                                        Reset Pencarian
                                    </a>
                                @else
                                    <p class="text-sm text-slate-400 mb-3">Belum ada akun admin terdaftar.</p>
                                    <a href="{{ route('owner.admins.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-600 text-white text-xs font-medium hover:bg-amber-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Admin Pertama
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($admins->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
@endsection
