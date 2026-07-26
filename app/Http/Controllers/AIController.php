<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class AIController extends Controller
{
    public function __construct(
        protected AIService $aiService,
        protected DocumentService $documentService,
    ) {}

    public function chat(Request $request)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

    $result = $this->documentService->buildContext(
    $request->user(),
    $request->message
);

$reply = $this->aiService->chat(
    $request->message,
    $result['context']
);

$reply .= "\n\n📄 Sumber:\n";

foreach ($result['sources'] as $source) {
    $reply .= "- {$source}\n";
}

        return response()->json([
            'reply' => $reply,
        ]);
    }
}