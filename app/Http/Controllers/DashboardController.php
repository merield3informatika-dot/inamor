<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\KnowledgeFeedback;
use App\Models\ManualKnowledge;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\AI\AIAnalyticsService;

class DashboardController extends Controller
{
   public function __construct(
    private readonly WorkspaceService $workspaceService,
    private readonly AIAnalyticsService $analyticsService,
) {
}

    public function index(Request $request): View
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $stats = [
            'document_count_today' => $workspace->documents()
                ->whereDate('created_at', today())
                ->count(),

            'pending_feedback' => KnowledgeFeedback::query()
                ->where('workspace_id', $workspace->id)
                ->where('status', 'pending')
                ->count(),

            'announcement_count' => 0,

            'manual_knowledge_count' => ManualKnowledge::query()
                ->where('workspace_id', $workspace->id)
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Upcoming Events
        |--------------------------------------------------------------------------
        */

        $upcomingEvents = CalendarEvent::query()
            ->where('workspace_id', $workspace->id)
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Documents
        |--------------------------------------------------------------------------
        */

        $recentDocuments = $workspace->documents()
            ->latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Activities
        |--------------------------------------------------------------------------
        | Belum ada ActivityLog.
        | Sementara kirim collection kosong supaya widget tetap aman.
        */

        $recentActivities = collect();

        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        | Belum ada modul Announcement.
        */

        $announcements = collect();

        /*
        |--------------------------------------------------------------------------
        | Service Statistics
        |--------------------------------------------------------------------------
        | Belum ada modul statistik.
        */

        $serviceStats = collect();
  $analytics = $this->analyticsService->statistics();

return view('dashboard', [
    'workspace' => $workspace,
    'stats' => $stats,
    'upcomingEvents' => $upcomingEvents,
    'recentDocuments' => $recentDocuments,
    'recentActivities' => $recentActivities,
    'announcements' => $announcements,
    'serviceStats' => $serviceStats,
    'analytics' => $analytics,
]);
    }
}