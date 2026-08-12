<?php

namespace App\Services\Notification;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;


final class NotificationService
{
    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user,
        int $workspaceId,
        string $type,
        string $title,
        string $message,
        array $data = [],
    ): Notification {
        return Notification::create([
            'id' => (string) Str::uuid(),

            'workspace_id' => $workspaceId,

            'user_id' => $user->id,

            'type' => $type,

            'title' => $title,

            'message' => $message,

            'data' => $data,

            'read_at' => null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create For Users
    |--------------------------------------------------------------------------
    */

    public function createForUsers(
        Collection $users,
        int $workspaceId,
        string $type,
        string $title,
        string $message,
        array $data = [],
    ): Collection {
        return $users->map(
            fn (User $user) => $this->create(
                user: $user,
                workspaceId: $workspaceId,
                type: $type,
                title: $title,
                message: $message,
                data: $data,
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Unread
    |--------------------------------------------------------------------------
    */

    public function unread(
        User $user,
        int $workspaceId,
        int $limit = 20,
    ): Collection {
        return Notification::query()
            ->forWorkspace($workspaceId)
            ->where('user_id', $user->id)
            ->unread()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Count
    |--------------------------------------------------------------------------
    */

    public function unreadCount(
        User $user,
        int $workspaceId,
    ): int {
        return Notification::query()
            ->forWorkspace($workspaceId)
            ->where('user_id', $user->id)
            ->unread()
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Read
    |--------------------------------------------------------------------------
    */

    public function markAsRead(
        User $user,
        int $workspaceId,
        string $notificationId,
    ): bool {
        $notification = Notification::query()
            ->forWorkspace($workspaceId)
            ->where('user_id', $user->id)
            ->whereKey($notificationId)
            ->first();

        if (! $notification) {
            return false;
        }

        return $notification->markAsRead();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark All As Read
    |--------------------------------------------------------------------------
    */

    public function markAllAsRead(
        User $user,
        int $workspaceId,
    ): int {
        return Notification::query()
            ->forWorkspace($workspaceId)
            ->where('user_id', $user->id)
            ->unread()
            ->update([
                'read_at' => now(),
            ]);
    }
    /**
 * Get latest notifications for a user in workspace.
 *
 * Includes both read and unread notifications.
 */
public function latest(
    User $user,
    int|string $workspaceId,
    int $limit = 20,
): Collection {
    return Notification::query()
        ->where('workspace_id', $workspaceId)
        ->where('user_id', $user->id)
        ->latest('created_at')
        ->limit($limit)
        ->get();
}
}