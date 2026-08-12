<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\KnowledgeFeedback;
use App\Models\ManualKnowledge;
use App\Services\Announcement\AnnouncementService;
use App\Services\AI\AIAnalyticsService;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
        private readonly AIAnalyticsService $analyticsService,
        private readonly AnnouncementService $announcementService,
    ) {
    }

    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | Active Workspace
        |--------------------------------------------------------------------------
        */

        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        /*
        |--------------------------------------------------------------------------
        | Dashboard Stats
        |--------------------------------------------------------------------------
        */

        $stats = [
            'document_count_today' => $workspace->documents()
                ->whereDate('created_at', today())
                ->count(),

            'pending_feedback' => KnowledgeFeedback::query()
                ->where('workspace_id', $workspace->id)
                ->where('status', 'pending')
                ->count(),

            'announcement_count' => $workspace->announcements()
                ->published()
                ->count(),

            'manual_knowledge_count' => ManualKnowledge::query()
                ->where('workspace_id', $workspace->id)
                ->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Upcoming Calendar Events
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
        |
        | ActivityLog belum digunakan pada dashboard saat ini.
        | Tetap kirim collection kosong supaya component aman.
        |
        */

        $recentActivities = collect();

        /*
        |--------------------------------------------------------------------------
        | Announcements
        |--------------------------------------------------------------------------
        |
        | Menampilkan pengumuman published terbaru untuk workspace aktif.
        |
        */

        $announcements = $this->announcementService
            ->list($workspace, onlyPublished: true)
            ->take(3);

        /*
        |--------------------------------------------------------------------------
        | AI Analytics
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Analytics harus dibatasi berdasarkan workspace aktif.
        |
        | Data ini digunakan oleh:
        | - Statistik Layanan
        | - AI Engine
        | - Mini chart dashboard
        | - AI Analytics detail
        |
        */

        $analytics = $this->analyticsService->statistics(
            $workspace->id
        );

        /*
        |--------------------------------------------------------------------------
        | Service Statistics
        |--------------------------------------------------------------------------
        |
        | Jangan bikin dummy.
        |
        | Data statistik sidebar akan membaca langsung dari
        | AI Analytics yang berasal dari database.
        |
        */

        $overview = $analytics['overview'] ?? [];

        $serviceStats = collect([
            (object) [
                'label' => 'AI Requests',
                'value' => number_format(
                    (int) ($overview['requests_today'] ?? 0)
                ),
                'meta' => 'hari ini',
            ],

            (object) [
                'label' => 'Success Rate',
                'value' => ($overview['success_rate'] ?? 0) . '%',
                'meta' => 'request berhasil',
            ],

            (object) [
                'label' => 'Response',
                'value' => number_format(
                    (int) ($overview['today_avg_response_ms'] ?? 0)
                ) . ' ms',
                'meta' => 'rata-rata hari ini',
            ],

            (object) [
                'label' => 'AI Saved',
                'value' => ($overview['ai_saved_pct'] ?? 0) . '%',
                'meta' => 'efisiensi AI',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Performance Data
        |--------------------------------------------------------------------------
        |
        | Dipisahkan supaya component dashboard bisa langsung
        | menggunakan data 7 hari terakhir untuk mini chart.
        |
        */

        $performance = $analytics['performance']['requests'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Return Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [
            'workspace' => $workspace,

            'stats' => $stats,

            'upcomingEvents' => $upcomingEvents,

            'recentDocuments' => $recentDocuments,

            'recentActivities' => $recentActivities,

            'announcements' => $announcements,

            'serviceStats' => $serviceStats,

            'analytics' => $analytics,

            'performance' => $performance,
        ]);
    }
}