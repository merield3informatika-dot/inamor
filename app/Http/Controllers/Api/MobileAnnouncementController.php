<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MobileAnnouncementController extends Controller
{
    public function __construct(
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        $announcements = $workspace->announcements()
            ->where('status', 'published')
            ->latest('published_at')
            ->get()
            ->map(function (Announcement $announcement) {
                return $this->transform($announcement);
            })
            ->values();

        return response()->json([
            'announcements' => $announcements,
        ]);
    }

    public function show(
        Request $request,
        Announcement $announcement
    ): JsonResponse {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        abort_if(
            $announcement->workspace_id !== $workspace->id,
            404
        );

        abort_if(
            ! $announcement->isPublished(),
            404
        );

        return response()->json([
            'announcement' => $this->transform($announcement),
        ]);
    }

    private function transform(
        Announcement $announcement
    ): array {
        return [
            'id' => $announcement->id,
            'title' => $announcement->title,
            'content' => $announcement->content,

            'thumbnail_url' => $announcement->thumbnail
                ? asset('storage/' . $announcement->thumbnail)
                : null,

            'published_at' => $announcement->published_at?->toISOString(),

            'author' => [
                'id' => $announcement->author?->id,
                'name' => $announcement->author?->name,
            ],
        ];
    }
}