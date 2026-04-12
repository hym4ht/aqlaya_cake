<?php

namespace App\Services;

use App\Models\SystemNotification;
use App\Models\User;

class NotificationService
{
    public function notifyUser(User $user, string $title, string $message, ?string $actionUrl = null): void
    {
        SystemNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'action_url' => $actionUrl,
        ]);
    }

    public function notifyAdmins(string $title, string $message, ?string $actionUrl = null): void
    {
        User::query()
            ->where('role', 'admin')
            ->get()
            ->each(function (User $admin) use ($title, $message, $actionUrl): void {
                $this->notifyUser($admin, $title, $message, $actionUrl);
            });
    }
}
