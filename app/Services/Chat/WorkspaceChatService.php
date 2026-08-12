<?php

namespace App\Services\Chat;

use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceConversation;
use App\Models\WorkspaceMessage;
use Illuminate\Support\Collection;

final class WorkspaceChatService
{
    /**
     * All conversations belonging to the given workspace.
     */
    public function conversations(Workspace $workspace): Collection
    {
        return $workspace->chatConversations()
            ->latest('updated_at')
            ->get();
    }

    /**
     * Get the workspace's default conversation, creating it if none exists.
     *
     * Basic chat has no "create conversation" UI in this version — every
     * workspace gets a single shared "General" conversation on first use.
     */
    public function ensureDefaultConversation(Workspace $workspace, User $creator): WorkspaceConversation
    {
        $existing = $workspace->chatConversations()->first();

        if ($existing) {
            return $existing;
        }

        return $workspace->chatConversations()->create([
            'created_by' => $creator->id,
            'title' => 'General',
        ]);
    }

    /**
     * Resolve a conversation, guaranteeing it belongs to the given workspace.
     */
    public function findForWorkspace(Workspace $workspace, int $conversationId): WorkspaceConversation
    {
        $conversation = $workspace->chatConversations()
            ->where('id', $conversationId)
            ->first();

        abort_if($conversation === null, 404);

        return $conversation;
    }

    public function messages(WorkspaceConversation $conversation): Collection
    {
        return $conversation->messages()
            ->with('user')
            ->oldest('created_at')
            ->get();
    }

    public function sendMessage(WorkspaceConversation $conversation, User $user, string $message): WorkspaceMessage
    {
        $created = $conversation->messages()->create([
            'user_id' => $user->id,
            'message' => $message,
        ]);

        $conversation->touch();

        return $created;
    }
}