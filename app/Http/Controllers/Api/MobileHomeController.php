<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MobileHomeController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'avatar_url' => $user->avatar
                ? asset('storage/' . $user->avatar)
                : null,
        ];


        /*
        |--------------------------------------------------------------------------
        | Workspace
        |--------------------------------------------------------------------------
        */

        $workspaceData = [
            'id' => $workspace->id,
            'name' => $workspace->name,
            'logo_url' => $workspace->logo
                ? asset('storage/' . $workspace->logo)
                : null,
        ];


        /*
        |--------------------------------------------------------------------------
        | Published Announcements
        |--------------------------------------------------------------------------
        |
        | Ambil beberapa pengumuman terbaru.
        | Home mobile nantinya akan menampilkannya sebagai carousel.
        |
        */

        $announcements = $workspace->announcements()
            ->where('status', 'published')
            ->latest('published_at')
            ->limit(10)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'content' => $announcement->content,

                    'thumbnail_url' => $announcement->thumbnail
                        ? asset('storage/' . $announcement->thumbnail)
                        : null,

                    'published_at' => $announcement->published_at?->toISOString(),
                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | Unread Notifications
        |--------------------------------------------------------------------------
        |
        | Hanya hitung notifikasi milik user aktif
        | pada workspace aktif.
        |
        */

        $unreadNotificationCount = Notification::query()
            ->where('user_id', $user->id)
            ->where('workspace_id', $workspace->id)
            ->unread()
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Upcoming Calendar Events
        |--------------------------------------------------------------------------
        */

        $upcomingEvents = $workspace->calendarEvents()
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(5)
            ->get();


        $nextEvent = $upcomingEvents->first();


        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([

            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            'user' => $userData,


            /*
            |--------------------------------------------------------------------------
            | Workspace
            |--------------------------------------------------------------------------
            */

            'workspace' => $workspaceData,


            /*
            |--------------------------------------------------------------------------
            | Announcements
            |--------------------------------------------------------------------------
            */

            'announcements' => $announcements,


            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */

            'unread_notification_count' => $unreadNotificationCount,


            /*
            |--------------------------------------------------------------------------
            | Next Event
            |--------------------------------------------------------------------------
            */

            'next_event' => $nextEvent
                ? [
                    'id' => $nextEvent->id,
                    'title' => $nextEvent->title,
                    'description' => $nextEvent->description,
                    'category' => $nextEvent->category,
                    'start_at' => $nextEvent->start_at?->toISOString(),
                    'end_at' => $nextEvent->end_at?->toISOString(),
                    'location' => $nextEvent->location,
                    'color' => $nextEvent->color,
                    'date_label' => $nextEvent->date_label,
                    'time_range_label' => $nextEvent->time_range_label,
                ]
                : null,


            /*
            |--------------------------------------------------------------------------
            | Upcoming Events
            |--------------------------------------------------------------------------
            */

            'upcoming_events' => $upcomingEvents
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'description' => $event->description,
                        'category' => $event->category,
                        'start_at' => $event->start_at?->toISOString(),
                        'end_at' => $event->end_at?->toISOString(),
                        'location' => $event->location,
                        'color' => $event->color,
                        'date_label' => $event->date_label,
                        'time_range_label' => $event->time_range_label,
                    ];
                })
                ->values(),

        ]);
    }
}