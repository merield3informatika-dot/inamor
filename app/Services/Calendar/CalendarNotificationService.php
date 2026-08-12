<?php

namespace App\Services\Calendar;

use App\Models\CalendarEvent;
use App\Models\WorkspaceMember;
use App\Services\Notification\NotificationService;

final class CalendarNotificationService
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
    }

    /**
     * Notify all eligible workspace members that a new event was created.
     * Must only be called on CREATE, never on update.
     */
    public function notifyCreated(CalendarEvent $event): void
    {
        $members = WorkspaceMember::query()
            ->where('workspace_id', $event->workspace_id)
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        $this->notificationService->createForUsers(
            users: $members,
            workspaceId: $event->workspace_id,
            type: 'calendar_created',
            title: 'Kegiatan baru ditambahkan',
            message: "Admin menambahkan jadwal {$event->title}. Yuk cek sekarang!",
            data: [
                'calendar_event_id' => $event->id,
                'url' => route('calendar.index'),
            ],
        );
    }
}