<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobileCalendarController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $events = CalendarEvent::query()
            ->where('workspace_id', $workspace->id)
            ->orderBy('start_at')
            ->get()
            ->map(function (CalendarEvent $event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'category' => $event->category,
                    'startAt' => $event->start_at?->toIso8601String(),
                    'endAt' => $event->end_at?->toIso8601String(),
                    'location' => $event->location,
                    'color' => $event->color,
                ];
            });

        return response()->json([
            'workspace' => [
                'id' => $workspace->id,
                'name' => $workspace->name,
            ],
            'events' => $events,
        ]);
    }
}