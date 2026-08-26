<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MobileNotificationController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $notifications = Notification::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->latest('created_at')
            ->get()
            ->map(function (Notification $notification) {
                return $this->transform($notification);
            })
            ->values();

        $unreadCount = Notification::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead(
        Request $request,
        Notification $notification
    ): JsonResponse {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        abort_if(
            $notification->user_id !== $user->id,
            404
        );

        abort_if(
            $notification->workspace_id !== $workspace->id,
            404
        );

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notifikasi ditandai sebagai sudah dibaca.',
            'notification' => $this->transform($notification->fresh()),
        ]);
    }

    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        Notification::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->unread()
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'Semua notifikasi sudah ditandai sebagai dibaca.',
            'unread_count' => 0,
        ]);
    }

    private function transform(
        Notification $notification
    ): array {
        return [
            'id' => $notification->id,
            'type' => $notification->type,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => $notification->data,
            'read_at' => $notification->read_at?->toISOString(),
            'created_at' => $notification->created_at?->toISOString(),
        ];
    }
}