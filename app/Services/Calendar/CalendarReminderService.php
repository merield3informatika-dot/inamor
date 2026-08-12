<?php

namespace App\Services\Calendar;

use App\Models\CalendarEvent;
use App\Models\Notification;
use App\Models\WorkspaceMember;
use App\Services\Notification\NotificationService;

final class CalendarReminderService
{
    /**
     * hours-before => [notification type, title, message template]
     *
     * @var array<int, array{type: string, title: string, template: string}>
     */
    private const REMINDERS = [
        10 => [
            'type' => 'calendar_reminder_10h',
            'title' => 'Kegiatan akan dimulai',
            'template' => ':title akan dimulai dalam 10 jam.',
        ],
        6 => [
            'type' => 'calendar_reminder_6h',
            'title' => 'Kegiatan akan segera dimulai',
            'template' => ':title akan dimulai dalam 6 jam.',
        ],
    ];

    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
    }

    /**
     * Check upcoming events and send any due H-10 / H-6 reminders.
     *
     * Idempotent: dedupe key is (type, user_id, calendar_event_id, start_at).
     * Because start_at is part of the dedupe key, rescheduling an event
     * to a new start_at will correctly allow a fresh reminder to be sent
     * for the new time, without duplicating reminders for the same time.
     *
     * Deleted events simply no longer appear in the query, so no
     * reminders are sent for them. Past events are excluded by the
     * `start_at > now()` filter.
     */
    public function sendDueReminders(): int
    {
        $sent = 0;

        $events = CalendarEvent::query()
            ->where('start_at', '>', now())
            ->where('start_at', '<=', now()->addHours(10))
            ->get();

        foreach ($events as $event) {

            foreach (self::REMINDERS as $hoursBefore => $meta) {

                if (now()->lt($event->start_at->copy()->subHours($hoursBefore))) {
                    continue; // not due yet
                }

                $sent += $this->sendReminderForEvent($event, $meta);

            }

        }

        return $sent;
    }

    /**
     * @param array{type: string, title: string, template: string} $meta
     */
    private function sendReminderForEvent(CalendarEvent $event, array $meta): int
    {
        $members = WorkspaceMember::query()
            ->where('workspace_id', $event->workspace_id)
            ->with('user')
            ->get();

        $sent = 0;

        foreach ($members as $member) {

            if (! $member->user) {
                continue;
            }

            $alreadySent = Notification::query()
                ->where('type', $meta['type'])
                ->where('user_id', $member->user_id)
                ->where('data->calendar_event_id', $event->id)
                ->where('data->start_at', $event->start_at->toISOString())
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $this->notificationService->create(
                user: $member->user,
                workspaceId: $event->workspace_id,
                type: $meta['type'],
                title: $meta['title'],
                message: str_replace(':title', $event->title, $meta['template']),
                data: [
                    'calendar_event_id' => $event->id,
                    'start_at' => $event->start_at->toISOString(),
                    'url' => route('calendar.index'),
                ],
            );

            $sent++;

        }

        return $sent;
    }
}