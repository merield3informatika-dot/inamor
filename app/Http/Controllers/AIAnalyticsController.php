<?php

namespace App\Http\Controllers;

use App\Services\AI\AIAnalyticsService;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AIAnalyticsController extends Controller
{
    public function __construct(
        private readonly AIAnalyticsService $analyticsService,
        private readonly WorkspaceResolver $workspaceResolver,
    ) {
    }

    public function index(Request $request): View
    {
        // Resolve tenant ID aman berdasarkan profil authenticated user (tidak dari parameter URL)
        $workspaceId = $this->workspaceResolver->resolveId($request->user());

        // Lempar ID workspace ke service statistik untuk membatasi query.
        $data = $this->analyticsService->statistics($workspaceId);

        return view('ai-analytics.index', [
            'data' => $data,
        ]);
    }
}