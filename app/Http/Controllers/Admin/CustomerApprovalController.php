<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomerApprovalController extends Controller
{
    public function __invoke(Request $request, User $user, NotificationService $notificationService): RedirectResponse
    {
        abort_unless($user->isCustomer(), 404);

        $validated = $request->validate([
            'decision' => ['required', Rule::in(['accept', 'reject'])],
        ]);

        if (! $user->isPendingApproval()) {
            return back()->with('error', 'Akun customer ini sudah diproses sebelumnya.');
        }

        if ($validated['decision'] === 'accept') {
            $user->forceFill([
                'is_approved' => true,
                'approved_at' => now(),
                'rejected_at' => null,
            ])->save();

            $notificationService->notifyUser(
                $user,
                'Akun customer disetujui',
                'Akun kamu sudah aktif. Silakan login untuk mulai memesan cake.',
                route('login')
            );

            return back()->with('success', 'Akun customer berhasil disetujui.');
        }

        $user->forceFill([
            'is_approved' => false,
            'approved_at' => null,
            'rejected_at' => now(),
        ])->save();

        $notificationService->notifyUser(
            $user,
            'Pendaftaran akun ditolak',
            'Pendaftaran akun customer kamu belum dapat disetujui admin.',
            null
        );

        return back()->with('success', 'Akun customer berhasil ditolak.');
    }
}
