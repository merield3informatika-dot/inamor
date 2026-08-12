<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\WorkspaceMember;
use App\Services\Announcement\AnnouncementService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class AnnouncementController extends Controller
{
    public function __construct(
        private readonly AnnouncementService $announcementService,
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): View
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $isAdmin = $this->isWorkspaceAdmin($workspace->id, $request->user()->id);

        $announcements = $this->announcementService->list(
            $workspace,
            onlyPublished: ! $isAdmin,
        );

        return view('announcements.index', [
            'announcements' => $announcements,
            'isAdmin' => $isAdmin,
        ]);
    }

    public function create(Request $request): View
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'membuat');

        return view('announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'membuat');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $this->announcementService->create(
            $workspace,
            $request->user(),
            $validated,
        );

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Pengumuman berhasil disimpan sebagai draft.');
    }

    public function edit(Request $request, Announcement $announcement): View
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->ensureBelongsToWorkspace($announcement, $workspace->id);

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'mengubah');

        return view('announcements.edit', [
            'announcement' => $announcement,
        ]);
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->ensureBelongsToWorkspace($announcement, $workspace->id);

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'mengubah');

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $this->announcementService->update($announcement, $validated);

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Request $request, Announcement $announcement): RedirectResponse
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->ensureBelongsToWorkspace($announcement, $workspace->id);

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'menghapus');

        $this->announcementService->delete($announcement);

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Pengumuman berhasil dihapus.');
    }

    public function publish(Request $request, Announcement $announcement): RedirectResponse
    {
        $workspace = $this->workspaceService->resolveActive($request->user());

        $this->ensureBelongsToWorkspace($announcement, $workspace->id);

        $this->authorizeAdmin($workspace->id, $request->user()->id, 'mempublikasikan');

        $this->announcementService->publish($announcement);

        return redirect()
            ->route('announcements.index')
            ->with('status', 'Pengumuman berhasil dipublikasikan.');
    }

    private function ensureBelongsToWorkspace(Announcement $announcement, int $workspaceId): void
    {
        abort_if($announcement->workspace_id !== $workspaceId, 404);
    }

    private function authorizeAdmin(int $workspaceId, int $userId, string $action): void
    {
        abort_unless(
            $this->isWorkspaceAdmin($workspaceId, $userId),
            403,
            "Hanya admin workspace yang dapat {$action} pengumuman."
        );
    }

    private function isWorkspaceAdmin(int $workspaceId, int $userId): bool
    {
        $member = WorkspaceMember::query()
            ->where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->first();

        return $member !== null && ($member->isOwner() || $member->isAdmin());
    }
}