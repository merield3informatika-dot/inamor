<?php

namespace App\Http\Controllers;

use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MobileCalendarController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $events = $workspace->calendarEvents()
            ->where('start_at', '>=', now()->startOfDay())
            ->orderBy('start_at')
            ->get();

        return view('mobile.calendar.index', [
            'user' => $user,
            'workspace' => $workspace,
            'events' => $events,
        ]);
    }
}