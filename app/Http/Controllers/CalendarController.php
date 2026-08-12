<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Services\Calendar\CalendarNotificationService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
        private readonly CalendarNotificationService $calendarNotificationService,
    ) {
    }

    public function index(Request $request): View
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $events = $workspace->calendarEvents()
            ->orderBy('start_at')
            ->get();

        return view('calendar.index', [
            'events' => $events,
        ]);
    }

    public function create(Request $request): View
    {
        // Resolve workspace hanya untuk memastikan user punya akses;
        // tidak ada data lain yang dibutuhkan form create.
        $this->workspaceService->resolveActive($request->user());

        return view('calendar.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        DB::transaction(function () use ($workspace, $request, $validated) {

            $event = $workspace->calendarEvents()->create([
                ...$validated,
                'created_by' => $request->user()->id,
            ]);

            $this->calendarNotificationService->notifyCreated($event);

        });

        return redirect()
            ->route('calendar.index')
            ->with('status', 'Kegiatan berhasil ditambahkan.');
    }

public function edit(Request $request, CalendarEvent $calendar): View
{
    $workspace = $this->workspaceService->resolveActive($request->user());

    abort_if($calendar->workspace_id !== $workspace->id, 404);

    return view('calendar.edit', [
        'event' => $calendar,
    ]);
}

public function update(Request $request, CalendarEvent $calendar): RedirectResponse
{
    $workspace = $this->workspaceService->resolveActive($request->user());

    abort_if($calendar->workspace_id !== $workspace->id, 404);

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'category' => ['required', 'string', 'max:100'],
        'start_at' => ['required', 'date'],
        'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        'location' => ['nullable', 'string', 'max:255'],
        'color' => ['nullable', 'string', 'max:20'],
    ]);

    // Intentionally NOT calling calendarNotificationService here —
    // notifications are only sent on CREATE, never on update.
    $calendar->update($validated);

    return redirect()
        ->route('calendar.index')
        ->with('status', 'Kegiatan berhasil diperbarui.');
}

public function destroy(Request $request, CalendarEvent $calendar): RedirectResponse
{
    $workspace = $this->workspaceService->resolveActive($request->user());

    abort_if($calendar->workspace_id !== $workspace->id, 404);

    $calendar->delete();

    return redirect()
        ->route('calendar.index');
    }
}