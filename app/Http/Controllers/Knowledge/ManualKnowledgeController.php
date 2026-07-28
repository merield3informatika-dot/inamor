<?php

namespace App\Http\Controllers\Knowledge;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeFeedback;
use App\Models\ManualKnowledge;
use App\Services\Knowledge\ManualKnowledgeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ManualKnowledgeController extends Controller
{
    public function __construct(
        protected ManualKnowledgeService $manualKnowledgeService,
    ) {
    }

    /**
     * List Manual Knowledge
     */
    public function index(Request $request): View
    {
        $knowledges = $this->manualKnowledgeService
            ->getWorkspaceKnowledge(
                $request->user()
            );

        return view('knowledge.manual.index', [
            'knowledges' => $knowledges,
        ]);
    }

    /**
     * Create Manual Knowledge from Feedback
     */
    public function create(
        Request $request,
        KnowledgeFeedback $feedback,
    ): View {

        return view('knowledge.manual.create', [
            'feedback' => $feedback,
        ]);
    }

    /**
     * Store Manual Knowledge
     */
    public function store(
        Request $request,
        KnowledgeFeedback $feedback,
    ): RedirectResponse {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $this->manualKnowledgeService->create(
            user: $request->user(),
            title: $validated['title'],
            content: $validated['content'],
            feedback: $feedback,
        );

        return redirect()
            ->route('knowledge.manual.index')
            ->with(
                'success',
                'Manual knowledge berhasil ditambahkan.'
            );
    }

    /**
     * Show Manual Knowledge
     */
    public function show(
        Request $request,
        ManualKnowledge $knowledge,
    ): View {

        return view('knowledge.manual.show', [
            'knowledge' => $knowledge,
        ]);
    }

    /**
     * Edit Manual Knowledge
     */
    public function edit(
        Request $request,
        ManualKnowledge $knowledge,
    ): View {

        return view('knowledge.manual.edit', [
            'knowledge' => $knowledge,
        ]);
    }

    /**
     * Update Manual Knowledge
     */
    public function update(
        Request $request,
        ManualKnowledge $knowledge,
    ): RedirectResponse {

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $this->manualKnowledgeService->update(
            $knowledge,
            $validated
        );

        return redirect()
            ->route('knowledge.manual.index')
            ->with(
                'success',
                'Manual knowledge berhasil diperbarui.'
            );
    }

    /**
     * Delete Manual Knowledge
     */
    public function destroy(
        ManualKnowledge $knowledge,
    ): RedirectResponse {

        $this->manualKnowledgeService->delete(
            $knowledge
        );

        return redirect()
            ->route('knowledge.manual.index')
            ->with(
                'success',
                'Manual knowledge berhasil dihapus.'
            );
    }
    public function createManual(): View
{
    return view('knowledge.manual.create');
}

public function storeManual(
    Request $request,
): RedirectResponse {

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
    ]);

    $this->manualKnowledgeService->create(
        user: $request->user(),
        title: $validated['title'],
        content: $validated['content'],
    );

    return redirect()
        ->route('knowledge.manual.index')
        ->with(
            'success',
            'Manual knowledge berhasil ditambahkan.'
        );
}
}