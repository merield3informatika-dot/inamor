<?php

namespace App\Http\Controllers;

use App\Services\Notification\NotificationService;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

final class NotificationController extends Controller
{
    public function __construct(
        private readonly NotificationService $notificationService,
        private readonly WorkspaceResolver $workspaceResolver,
    ) {
    }

    /**
     * Notification page.
     */
   public function index(Request $request): View|JsonResponse
{
    $user = $request->user();

    $workspaceId = $this->workspaceResolver->resolveId($user);

    $limit = min(
        max((int) $request->integer('limit', 20), 1),
        50
    );

    $notifications = $this->notificationService->latest(
        user: $user,
        workspaceId: $workspaceId,
        limit: $limit,
    );

    $unreadCount = $this->notificationService->unreadCount(
        user: $user,
        workspaceId: $workspaceId,
    );

    /*
    |--------------------------------------------------------------------------
    | JSON response
    |--------------------------------------------------------------------------
    |
    | Dipakai oleh notification card di topbar.
    |
    */

    if ($request->expectsJson()) {
        return response()->json([
            'data' => $notifications->map(
                fn ($notification) => [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at?->toISOString(),
                    'created_at' => $notification->created_at?->toISOString(),
                ]
            )->values(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Blade response
    |--------------------------------------------------------------------------
    |
    | Dipakai ketika user membuka /notifications.
    |
    */

    return view('notifications.index', [
        'notifications' => $notifications,
        'unreadCount' => $unreadCount,
    ]);
}

    /**
     * Get unread notification count.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspaceId = $this->workspaceResolver->resolveId($user);

        $count = $this->notificationService->unreadCount(
            user: $user,
            workspaceId: $workspaceId,
        );

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function markAsRead(
        Request $request,
        string $notification,
    ): JsonResponse {
        $user = $request->user();

        $workspaceId = $this->workspaceResolver->resolveId($user);

        $updated = $this->notificationService->markAsRead(
            user: $user,
            workspaceId: $workspaceId,
            notificationId: $notification,
        );

        if (! $updated) {
            return response()->json([
                'message' => 'Notification tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'message' => 'Notification ditandai sebagai sudah dibaca.',
        ]);
    }

    /**
     * Mark all current workspace notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspaceId = $this->workspaceResolver->resolveId($user);

        $updated = $this->notificationService->markAllAsRead(
            user: $user,
            workspaceId: $workspaceId,
        );

        return response()->json([
            'message' => 'Semua notification ditandai sebagai sudah dibaca.',
            'updated' => $updated,
        ]);
    }
}