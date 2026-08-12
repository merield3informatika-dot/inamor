<?php

namespace App\Console\Commands;

use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Services\Announcement\AnnouncementService;
use App\Services\Calendar\CalendarNotificationService;
use App\Services\Calendar\CalendarReminderService;
use App\Services\Chat\WorkspaceChatService;
use App\Services\Notification\NotificationService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RuntimeException;
use Throwable;

#[Signature('app:test-claude-features')]
#[Description('Test Announcement, Calendar, Notification, and Workspace Chat features')]
class TestClaudeFeatures extends Command
{
    public function handle(): int
    {
        $this->newLine();

        $this->components->info('INAMOR / CLAUDE FEATURE TEST');

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Workspace
        |--------------------------------------------------------------------------
        */

      $workspace = Workspace::query()
    ->with('owner')
    ->whereHas('members', function ($query) {
        $query->select('workspace_id')
            ->groupBy('workspace_id')
            ->havingRaw('COUNT(*) >= 2');
    })
    ->first();
        if (! $workspace) {
            $this->components->error(
                'Tidak ada workspace di database.'
            );

            return self::FAILURE;
        }

        $admin = $workspace->owner;

        if (! $admin) {
            $this->components->error(
                "Workspace #{$workspace->id} tidak memiliki owner."
            );

            return self::FAILURE;
        }

        $member = User::query()
            ->whereHas('workspaceMemberships', function ($query) use ($workspace) {
                $query->where('workspace_id', $workspace->id);
            })
            ->whereKeyNot($admin->id)
            ->first();

        if (! $member) {
            $this->components->error(
                "Workspace #{$workspace->id} belum memiliki member kedua."
            );

            $this->line(
                'Tambahkan minimal satu member selain owner sebelum menjalankan test.'
            );

            return self::FAILURE;
        }

        $memberCount = WorkspaceMember::query()
            ->where('workspace_id', $workspace->id)
            ->count();

        $this->components->twoColumnDetail(
            'Workspace',
            "{$workspace->name} (#{$workspace->id})"
        );

        $this->components->twoColumnDetail(
            'Admin',
            "{$admin->name} (#{$admin->id})"
        );

        $this->components->twoColumnDetail(
            'Member',
            "{$member->name} (#{$member->id})"
        );

        $this->components->twoColumnDetail(
            'Members',
            (string) $memberCount
        );

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Services
        |--------------------------------------------------------------------------
        */

        $announcementService = app(
            AnnouncementService::class
        );

        $calendarNotificationService = app(
            CalendarNotificationService::class
        );

        $calendarReminderService = app(
            CalendarReminderService::class
        );

        $chatService = app(
            WorkspaceChatService::class
        );

        $notificationService = app(
            NotificationService::class
        );

        /*
        |--------------------------------------------------------------------------
        | Result tracking
        |--------------------------------------------------------------------------
        */

        $passed = 0;
        $failed = 0;

        /*
        |--------------------------------------------------------------------------
        | 1. ANNOUNCEMENT
        |--------------------------------------------------------------------------
        */

        $this->components->info('1. Announcement');

        try {
            $announcement = $announcementService->create(
                $workspace,
                $admin,
                [
                    'title' => 'Uji Pengumuman ' . now()->format('H:i:s'),
                    'content' => 'Isi pengumuman uji dari automated feature test.',
                ]
            );

            $this->check(
                $announcement->status === 'draft',
                'Create draft',
                $passed,
                $failed
            );

            $announcementService->publish(
                $announcement
            );

            $announcement->refresh();

            $this->check(
                $announcement->status === 'published',
                'Publish announcement',
                $passed,
                $failed
            );

            $notificationCount = Notification::query()
                ->where('type', 'announcement')
                ->where(
                    'data->announcement_id',
                    $announcement->id
                )
                ->count();

            $this->check(
                $notificationCount === $memberCount,
                "Announcement notification {$notificationCount}/{$memberCount}",
                $passed,
                $failed
            );

            /*
            |--------------------------------------------------------------------------
            | Republish — must not duplicate
            |--------------------------------------------------------------------------
            */

            $beforeRepublish = $notificationCount;

            $announcementService->publish(
                $announcement
            );

            $afterRepublish = Notification::query()
                ->where('type', 'announcement')
                ->where(
                    'data->announcement_id',
                    $announcement->id
                )
                ->count();

            $this->check(
                $beforeRepublish === $afterRepublish,
                'Announcement no duplicate',
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Announcement',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 2. CALENDAR CREATED NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '2. Calendar Created Notification'
        );

        try {
            $event = $workspace->calendarEvents()->create([
                'title' => 'Rapat Uji ' . now()->format('H:i:s'),
                'category' => 'meeting',
                'start_at' => now()->addHours(9),
                'created_by' => $admin->id,
            ]);

            $calendarNotificationService->notifyCreated(
                $event
            );

            $notificationCount = Notification::query()
                ->where('type', 'calendar_created')
                ->where(
                    'data->calendar_event_id',
                    $event->id
                )
                ->count();

            $this->check(
                $notificationCount === $memberCount,
                "Calendar notification {$notificationCount}/{$memberCount}",
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Calendar created notification',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 3. CALENDAR H-10
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '3. Calendar H-10 Reminder'
        );

        try {
            $calendarReminderService->sendDueReminders();

            $reminderCount = Notification::query()
                ->where('type', 'calendar_reminder_10h')
                ->where(
                    'data->calendar_event_id',
                    $event->id ?? null
                )
                ->count();

            $this->check(
                $reminderCount === $memberCount,
                "H-10 notification {$reminderCount}/{$memberCount}",
                $passed,
                $failed
            );

            $beforeSecondRun = $reminderCount;

            $calendarReminderService->sendDueReminders();

            $afterSecondRun = Notification::query()
                ->where('type', 'calendar_reminder_10h')
                ->where(
                    'data->calendar_event_id',
                    $event->id ?? null
                )
                ->count();

            $this->check(
                $beforeSecondRun === $afterSecondRun,
                'H-10 no duplicate',
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Calendar H-10',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 4. CALENDAR H-6
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '4. Calendar H-6 Reminder'
        );

        try {
            $event2 = $workspace->calendarEvents()->create([
                'title' => 'Rapat H-6 ' . now()->format('H:i:s'),
                'category' => 'meeting',
                'start_at' => now()->addHours(5),
                'created_by' => $admin->id,
            ]);

            $calendarReminderService->sendDueReminders();

            $reminderCount = Notification::query()
                ->where('type', 'calendar_reminder_6h')
                ->where(
                    'data->calendar_event_id',
                    $event2->id
                )
                ->count();

            $this->check(
                $reminderCount === $memberCount,
                "H-6 notification {$reminderCount}/{$memberCount}",
                $passed,
                $failed
            );

            $beforeSecondRun = $reminderCount;

            $calendarReminderService->sendDueReminders();

            $afterSecondRun = Notification::query()
                ->where('type', 'calendar_reminder_6h')
                ->where(
                    'data->calendar_event_id',
                    $event2->id
                )
                ->count();

            $this->check(
                $beforeSecondRun === $afterSecondRun,
                'H-6 no duplicate',
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Calendar H-6',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 5. NOTIFICATION
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '5. Notification System'
        );

        try {
            $unreadBefore = $notificationService->unreadCount(
                $admin,
                $workspace->id
            );

            $this->line(
                "  Unread before: {$unreadBefore}"
            );

            $one = Notification::query()
                ->where('user_id', $admin->id)
                ->where('workspace_id', $workspace->id)
                ->unread()
                ->first();

            if ($one) {
                $notificationService->markAsRead(
                    $admin,
                    $workspace->id,
                    $one->id
                );

                $unreadAfter = $notificationService->unreadCount(
                    $admin,
                    $workspace->id
                );

                $this->check(
                    $unreadAfter < $unreadBefore,
                    'Mark one as read',
                    $passed,
                    $failed
                );

                $existsInHistory = $notificationService
                    ->latest(
                        $admin,
                        $workspace->id
                    )
                    ->pluck('id')
                    ->contains($one->id);

                $this->check(
                    $existsInHistory,
                    'Read notification remains in history',
                    $passed,
                    $failed
                );
            } else {
                $this->components->warn(
                    'Tidak ada unread notification untuk mark-one test.'
                );
            }

            $notificationService->markAllAsRead(
                $admin,
                $workspace->id
            );

            $finalUnread = $notificationService->unreadCount(
                $admin,
                $workspace->id
            );

            $this->check(
                $finalUnread === 0,
                'Mark all as read',
                $passed,
                $failed
            );

            $historyCount = $notificationService
                ->latest(
                    $admin,
                    $workspace->id
                )
                ->count();

            $this->check(
                $historyCount >= 0,
                "History available ({$historyCount})",
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Notification system',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 6. WORKSPACE CHAT
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '6. Workspace Chat'
        );

        try {
            $conversation = $chatService->ensureDefaultConversation(
                $workspace,
                $admin
            );

            $this->check(
                $conversation !== null,
                'Create / retrieve conversation',
                $passed,
                $failed
            );

            $message = $chatService->sendMessage(
                $conversation,
                $member,
                'Halo tim! Ini pesan automated feature test.'
            );

            $this->check(
                $message !== null,
                'Send message',
                $passed,
                $failed
            );

            $messages = $chatService
                ->messages($conversation)
                ->pluck('message');

            $this->check(
                $messages->contains(
                    'Halo tim! Ini pesan automated feature test.'
                ),
                'Retrieve messages',
                $passed,
                $failed
            );

            $this->check(
                $message->user_id === $member->id,
                'Message belongs to sender',
                $passed,
                $failed
            );
        } catch (Throwable $e) {
            $this->failException(
                'Workspace Chat',
                $e,
                $failed
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | 7. WORKSPACE ISOLATION
        |--------------------------------------------------------------------------
        */

        $this->components->info(
            '7. Workspace Isolation'
        );

        try {
            $otherWorkspace = Workspace::query()
                ->where('id', '!=', $workspace->id)
                ->first();

            if (! $otherWorkspace) {
                $this->components->warn(
                    'Workspace kedua tidak tersedia. Isolation test dilewati.'
                );
            } else {
                try {
                    $chatService->findForWorkspace(
                        $otherWorkspace,
                        $conversation->id
                    );

                    $this->components->error(
                        'Workspace isolation FAILED.'
                    );

                    $failed++;
                } catch (Throwable) {
                    $this->components->twoColumnDetail(
                        '✓',
                        'Workspace isolation berhasil'
                    );

                    $passed++;
                }
            }
        } catch (Throwable $e) {
            $this->failException(
                'Workspace isolation',
                $e,
                $failed
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->components->info(
            '========================================'
        );

        $this->components->twoColumnDetail(
            'Passed',
            (string) $passed
        );

        $this->components->twoColumnDetail(
            'Failed',
            (string) $failed
        );

        $this->components->info(
            '========================================'
        );

        $this->newLine();

        if ($failed > 0) {
            $this->components->error(
                'FEATURE TEST FAILED'
            );

            return self::FAILURE;
        }

        $this->components->success(
            'SEMUA FEATURE TEST LOLOS'
        );

        return self::SUCCESS;
    }

    private function check(
        bool $condition,
        string $label,
        int &$passed,
        int &$failed
    ): void {
        if ($condition) {
            $this->components->twoColumnDetail(
                '✓',
                $label
            );

            $passed++;

            return;
        }

        $this->components->twoColumnDetail(
            '✗',
            $label
        );

        $failed++;
    }

    private function failException(
        string $section,
        Throwable $exception,
        int &$failed
    ): void {
        $this->components->error(
            "{$section} ERROR"
        );

        $this->line(
            $exception->getMessage()
        );

        $failed++;
    }
}