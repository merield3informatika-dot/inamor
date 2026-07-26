<?php

namespace App\Repositories;

use App\Models\Document;
use App\Models\DocumentContent;
use Illuminate\Support\Collection;

final class DocumentRepository
{
    public function create(array $data): Document
    {
        return Document::create($data);
    }

    public function update(Document $document, array $data): Document
    {
        $document->update($data);

        return $document->refresh();
    }

    public function delete(Document $document): bool
    {
        return (bool) $document->delete();
    }

    public function getByWorkspace(int $workspaceId): Collection
    {
        return Document::query()
            ->where('workspace_id', $workspaceId)
            ->latest()
            ->get();
    }

    /**
     * Pre-filters documents at the database level to candidates that
     * contain at least one keyword in their title or body. No scoring
     * happens here - it is purely a DB-level candidate fetch.
     *
     * @param array<int, string> $keywords
     */
    public function searchDocuments(int $workspaceId, array $keywords): Collection
    {
        if (empty($keywords)) {
            return collect();
        }

        return Document::query()
            ->with('content')
            ->where('workspace_id', $workspaceId)
            ->where('status', 'ready')
            ->where(function ($query) use ($keywords): void {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', "%{$keyword}%")
                        ->orWhereHas('content', function ($contentQuery) use ($keyword): void {
                            $contentQuery->where('raw_text', 'like', "%{$keyword}%");
                        });
                }
            })
            ->get();
    }

    public function getDocumentContents(int $documentId): ?DocumentContent
    {
        return DocumentContent::query()
            ->where('document_id', $documentId)
            ->first();
    }

    /**
     * @param array{raw_text: string, page_count: int} $data
     */
    public function createContent(Document $document, array $data): DocumentContent
    {
        return $document->content()->create($data);
    }

    public function markAsReady(Document $document): Document
    {
        return $this->update($document, ['status' => 'ready']);
    }

    public function markAsFailed(Document $document): Document
    {
        return $this->update($document, ['status' => 'failed']);
    }
}
