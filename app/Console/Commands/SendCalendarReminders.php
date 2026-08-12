<?php

namespace App\Console\Commands;

use App\Services\Calendar\CalendarReminderService;
use Illuminate\Console\Command;

final class SendCalendarReminders extends Command
{
    protected $signature = 'calendar:send-reminders';

    protected $description = 'Send due H-10 and H-6 calendar event reminders to eligible workspace members.';

    public function __construct(
        private readonly CalendarReminderService $reminderService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $sent = $this->reminderService->sendDueReminders();

        $this->info("Calendar reminders sent: {$sent}");

        return self::SUCCESS;
    }
}