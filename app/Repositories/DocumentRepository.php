<?php

namespace App\Repositories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Collection;

class DocumentRepository
{
    public function create(array $data): Document
    {
        return Document::create($data);
    }

    public function getByWorkspace(int $workspaceId): Collection
    {
        return Document::where('workspace_id', $workspaceId)
            ->latest()
            ->get();
    }

  public function searchRelevantDocuments(
    int $workspaceId,
    string $question,
    int $limit = 3
): array {

    $keywords = collect(
        preg_split('/\s+/', strtolower($question))
    )
        ->filter(fn ($word) => mb_strlen($word) >= 3)
        ->values();

    $documents = Document::with('content')
        ->where('workspace_id', $workspaceId)
        ->get()
        ->filter(function ($document) use ($keywords) {

            if (!$document->content?->raw_text) {
                return false;
            }

            $text = strtolower($document->content->raw_text);

            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return true;
                }
            }

            return false;
        })
        ->take($limit);

    $context = '';
    $sources = [];

    foreach ($documents as $document) {

        $context .= "Document: {$document->title}\n";
        $context .= $document->content->raw_text;
        $context .= "\n\n----------------------------------------\n\n";

        $sources[] = $document->title;
    }

    return [
        'context' => trim($context),
        'sources' => $sources,
    ];
}
}