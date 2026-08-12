<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
|--------------------------------------------------------------------------
| Calendar Reminders
|--------------------------------------------------------------------------
|
| Checks upcoming CalendarEvent records for due H-10 / H-6 reminders and
| creates notifications for eligible workspace members. Idempotent —
| CalendarReminderService checks existing notifications before creating
| new ones, so running this multiple times never duplicates reminders.
|
*/

Schedule::command('calendar:send-reminders')->everyFiveMinutes();