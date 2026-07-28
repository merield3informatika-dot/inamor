<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeFeedback;
use App\Services\Workspace\WorkspaceResolver;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class KnowledgeFeedbackController extends Controller
{
    public function __construct(
        private readonly WorkspaceResolver $workspaceResolver,
    ) {
    }
public function show(
    Request $request,
    KnowledgeFeedback $feedback
): View {
    $workspaceId = $this->workspaceResolver->resolveId(
        $request->user()
    );

    abort_if(
        $feedback->workspace_id !== $workspaceId,
        403
    );

    return view('knowledge.feedback.show', [
        'feedback' => $feedback,
    ]);
}
    public function index(Request $request): View
    {
        $workspaceId = $this->workspaceResolver->resolveId(
            $request->user()
        );

        $feedbacks = KnowledgeFeedback::query()
            ->where('workspace_id', $workspaceId)
            ->orderByDesc('asked_count')
            ->latest()
            ->paginate(15);

        return view('knowledge.feedback.index', [
            'feedbacks' => $feedbacks,
        ]);
    }
}