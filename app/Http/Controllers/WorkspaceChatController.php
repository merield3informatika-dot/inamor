<?php

namespace App\Http\Controllers;

use App\Services\Chat\WorkspaceChatService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class WorkspaceChatController extends Controller
{
    public function __construct(
        private readonly WorkspaceChatService $chatService,
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    /**
     * GET /chat — redirects to the first (or default) conversation.
     */
 public function index(Request $request): RedirectResponse
{
    $workspace = $this->workspaceService->resolveActive(
        $request->user()
    );

    $conversation = $this->chatService->conversations($workspace)->first()
        ?? $this->chatService->ensureDefaultConversation(
            $workspace,
            $request->user()
        );

    return redirect()->route(
        'workspace.chat.show',
        $conversation->id
    );
}

public function show(Request $request, int $conversation): View
{
    $workspace = $this->workspaceService->resolveActive(
        $request->user()
    );

    $activeConversation = $this->chatService->findForWorkspace(
        $workspace,
        $conversation
    );

    return view('chat.chatpage', [
        'conversations' => $this->chatService->conversations($workspace),
        'activeConversation' => $activeConversation,
        'messages' => $this->chatService->messages($activeConversation),
    ]);
}

public function sendMessage(
    Request $request,
    int $conversation
): RedirectResponse {
    $workspace = $this->workspaceService->resolveActive(
        $request->user()
    );

    $activeConversation = $this->chatService->findForWorkspace(
        $workspace,
        $conversation
    );

    $validated = $request->validate([
        'message' => ['required', 'string', 'max:5000'],
    ]);

    $this->chatService->sendMessage(
        $activeConversation,
        $request->user(),
        $validated['message'],
    );

    return redirect()->route(
        'workspace.chat.show',
        $activeConversation->id
    );
}
}