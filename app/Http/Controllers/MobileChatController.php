<?php

namespace App\Http\Controllers;

use App\Services\Chat\WorkspaceChatService;
use App\Services\WorkspaceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class MobileChatController extends Controller
{
    public function __construct(
        private readonly WorkspaceChatService $chatService,
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    /**
     * Mobile chat home.
     */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $conversation = $this->chatService->conversations($workspace)->first()
            ?? $this->chatService->ensureDefaultConversation(
                $workspace,
                $user,
            );

        return redirect()->route(
            'mobile.chat.show',
            $conversation->id,
        );
    }

    /**
     * Mobile conversation.
     */
    public function show(
        Request $request,
        int $conversation,
    ): View {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $activeConversation = $this->chatService->findForWorkspace(
            $workspace,
            $conversation,
        );

        return view('mobile.chat.show', [
            'user' => $user,
            'workspace' => $workspace,
            'conversations' => $this->chatService->conversations($workspace),
            'activeConversation' => $activeConversation,
            'messages' => $this->chatService->messages(
                $activeConversation,
            ),
        ]);
    }

    /**
     * Send a mobile chat message.
     */
    public function sendMessage(
        Request $request,
        int $conversation,
    ): RedirectResponse {
        $user = $request->user();

        $workspace = $this->workspaceService->resolveActive($user);

        $activeConversation = $this->chatService->findForWorkspace(
            $workspace,
            $conversation,
        );

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $this->chatService->sendMessage(
            $activeConversation,
            $user,
            $validated['message'],
        );

        return redirect()->route(
            'mobile.chat.show',
            $activeConversation->id,
        );
    }
}