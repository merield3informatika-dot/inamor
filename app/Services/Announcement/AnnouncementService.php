<?php

namespace App\Services\Announcement;

use App\Models\Announcement;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class AnnouncementService
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
    }

    /**
     * List announcements for a workspace.
     */
    public function list(Workspace $workspace, bool $onlyPublished = false): Collection
    {
        $query = $workspace->announcements()
            ->with('author')
            ->latest('created_at');

        if ($onlyPublished) {
            $query->published();
        }

        return $query->get();
    }

    /**
     * Create a new announcement. Always starts as draft.
     *
     * @param array{title: string, content: string} $data
     */
    public function create(Workspace $workspace, User $author, array $data): Announcement
    {
        return $workspace->announcements()->create([
            'author_id' => $author->id,
            'title' => $data['title'],
            'content' => $data['content'],
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    /**
     * @param array{title: string, content: string} $data
     */
    public function update(Announcement $announcement, array $data): Announcement
    {
        $announcement->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        return $announcement->fresh();
    }

    public function delete(Announcement $announcement): void
    {
        $announcement->delete();
    }

    /**
     * Publish an announcement and notify eligible workspace members.
     *
     * Idempotent: publishing an already-published announcement again
     * does NOT create duplicate notifications.
     */
    public function publish(Announcement $announcement): Announcement
    {
        return DB::transaction(function () use ($announcement) {

            $updated = Announcement::query()
                ->where('id', $announcement->id)
                ->where('status', 'draft')
                ->update([
                    'status' => 'published',
                    'published_at' => now(),
                ]);

            if ($updated === 0) {
                // Already published previously — no-op, no duplicate notifications.
                return $announcement->fresh();
            }

            $announcement->refresh();

            $members = WorkspaceMember::query()
                ->where('workspace_id', $announcement->workspace_id)
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter();

            $this->notificationService->createForUsers(
                users: $members,
                workspaceId: $announcement->workspace_id,
                type: 'announcement',
                title: $announcement->title !== '' ? $announcement->title : 'Pengumuman baru',
                message: $announcement->title,
                data: [
                    'announcement_id' => $announcement->id,
                    'url' => route('announcements.index'),
                ],
            );

            return $announcement;
        });
    }
}