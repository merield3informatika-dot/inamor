<?php

namespace App\Http\Controllers;

use App\Services\Notification\NotificationService;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MobileController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
        private readonly NotificationService $notificationService,
    ) {
    }

    public function home(Request $request): View
    {
        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Active Workspace
        |--------------------------------------------------------------------------
        */

        $workspace = $this->workspaceService->resolveActive($user);

        /*
        |--------------------------------------------------------------------------
        | Latest Announcements
        |--------------------------------------------------------------------------
        */

        $announcements = $workspace->announcements()
            ->published()
            ->latest('published_at')
            ->latest('created_at')
            ->limit(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Calendar
        |--------------------------------------------------------------------------
        */

        $upcomingEvents = $workspace->calendarEvents()
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Notifications
        |--------------------------------------------------------------------------
        */

        $notifications = $this->notificationService->latest(
            user: $user,
            workspaceId: $workspace->id,
            limit: 5,
        );

        /*
        |--------------------------------------------------------------------------
        | Unread Notification Count
        |--------------------------------------------------------------------------
        */

        $unreadNotificationCount = $this->notificationService->unreadCount(
            user: $user,
            workspaceId: $workspace->id,
        );

        /*
        |--------------------------------------------------------------------------
        | Workspace Membership
        |--------------------------------------------------------------------------
        */

        $membership = $user->workspaceMemberships()
            ->where('workspace_id', $workspace->id)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view('mobile.home', [
            'user' => $user,
            'workspace' => $workspace,
            'membership' => $membership,

            'announcements' => $announcements,
            'upcomingEvents' => $upcomingEvents,

            'notifications' => $notifications,
            'unreadNotificationCount' => $unreadNotificationCount,
        ]);
    }
}