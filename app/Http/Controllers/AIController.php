<?php

namespace App\Http\Controllers;

use App\Services\Knowledge\KnowledgeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

final class AIController extends Controller
{
    public function __construct(
        private readonly KnowledgeService $knowledgeService,
    ) {
    }

    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $answer = $this->knowledgeService->ask(
                $request->user(),
                $validated['message']
            );
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'answer' => 'Terjadi kesalahan saat memproses pertanyaan Anda.',
                'sources' => [],
            ], 500);
        }

        return response()->json($answer->toArray());
    }
}