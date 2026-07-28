<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __construct(
        private readonly WorkspaceResolver $workspaceResolver
    ) {
    }

    public function index(Request $request): View
    {
        $workspace = $this->workspaceResolver->resolve($request->user());

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
        $this->workspaceResolver->resolve($request->user());

        return view('calendar.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $workspace = $this->workspaceResolver->resolve($request->user());

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', 'max:100'],
            'start_at' => ['required', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'location' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $workspace->calendarEvents()->create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('calendar.index')
            ->with('status', 'Kegiatan berhasil ditambahkan.');
    }

public function edit(Request $request, CalendarEvent $calendar): View
{
    $workspace = $this->workspaceResolver->resolve($request->user());

    abort_if($calendar->workspace_id !== $workspace->id, 404);

    return view('calendar.edit', [
        'event' => $calendar,
    ]);
}

public function update(Request $request, CalendarEvent $calendar): RedirectResponse
{
    $workspace = $this->workspaceResolver->resolve($request->user());

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

    $calendar->update($validated);

    return redirect()
        ->route('calendar.index')
        ->with('status', 'Kegiatan berhasil diperbarui.');
}

public function destroy(Request $request, CalendarEvent $calendar): RedirectResponse
{
    $workspace = $this->workspaceResolver->resolve($request->user());

    abort_if($calendar->workspace_id !== $workspace->id, 404);

    $calendar->delete();

    return redirect()
        ->route('calendar.index');
    }
}