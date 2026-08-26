<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Chat\WorkspaceChatService;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MobileChatController extends Controller
{
    public function __construct(
        private readonly WorkspaceChatService $chatService,
        private readonly WorkspaceService $workspaceService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        $conversation = $this->chatService->conversations($workspace)->first()
            ?? $this->chatService->ensureDefaultConversation(
                $workspace,
                $request->user()
            );

        $messages = $this->chatService->messages($conversation);

        return response()->json([
            'conversation' => [
                'id' => $conversation->id,
                'title' => $conversation->title,
                'created_at' => $conversation->created_at?->toISOString(),
                'updated_at' => $conversation->updated_at?->toISOString(),
            ],

            'messages' => $messages
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'message' => $message->message,
                        'user' => [
                            'id' => $message->user?->id,
                            'name' => $message->user?->name,
                            'avatar_url' => $message->user?->avatar
                                ? asset('storage/' . $message->user->avatar)
                                : null,
                        ],
                        'created_at' => $message->created_at?->toISOString(),
                    ];
                })
                ->values(),
        ]);
    }

    public function sendMessage(
        Request $request
    ): JsonResponse {
        $workspace = $this->workspaceService->resolveActive(
            $request->user()
        );

        $conversation = $this->chatService->conversations($workspace)->first()
            ?? $this->chatService->ensureDefaultConversation(
                $workspace,
                $request->user()
            );

        $validated = $request->validate([
            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $message = $this->chatService->sendMessage(
            $conversation,
            $request->user(),
            $validated['message'],
        );

        $message->load('user');

        return response()->json([
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'user' => [
                    'id' => $message->user?->id,
                    'name' => $message->user?->name,
                    'avatar_url' => $message->user?->avatar
                        ? asset('storage/' . $message->user->avatar)
                        : null,
                ],
                'created_at' => $message->created_at?->toISOString(),
            ],
        ], 201);
    }
}