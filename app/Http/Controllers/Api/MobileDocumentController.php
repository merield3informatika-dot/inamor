<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class MobileDocumentController extends Controller
{
    public function __construct(
        protected DocumentService $documentService,
    ) {
    }

    /**
     * List documents from active workspace.
     */
    public function index(
        Request $request,
    ): JsonResponse {

        $documents = $this->documentService
            ->getDocuments(
                $request->user(),
            )
            ->load('content');

        return response()->json([
            'documents' => $documents
                ->map(function (Document $document) {

                    return [
                        'id' => $document->id,
                        'title' => $document->title,
                        'file_name' => $document->file_name,
                        'mime_type' => $document->mime_type,
                        'file_size' => $document->file_size,
                        'status' => $document->status,
                        'created_at' => $document->created_at,

                        'content' => $document->content
                            ? [
                                'page_count' =>
                                    $document->content->page_count,

                                'extraction_method' =>
                                    $document->content->extraction_method,

                                'ocr_used' =>
                                    $document->content->ocr_used,

                                'confidence' =>
                                    $document->content->confidence,
                            ]
                            : null,
                    ];
                })
                ->values(),
        ]);
    }


    /**
     * Get single document for mobile preview.
     */
    public function show(
        Request $request,
        Document $document,
    ): JsonResponse {

        $document =
            $this->documentService
                ->getDocumentForPreview(
                    $request->user(),
                    $document,
                );

        return response()->json([
            'document' => [
                'id' => $document->id,
                'title' => $document->title,
                'file_name' => $document->file_name,
                'mime_type' => $document->mime_type,
                'file_size' => $document->file_size,
                'status' => $document->status,
                'created_at' => $document->created_at,

                'content' => $document->content
                    ? [
                        'raw_text' =>
                            $document->content->raw_text,

                        'page_count' =>
                            $document->content->page_count,

                        'extraction_method' =>
                            $document->content->extraction_method,

                        'ocr_used' =>
                            $document->content->ocr_used,

                        'confidence' =>
                            $document->content->confidence,

                        'metadata' =>
                            $document->content->metadata,
                    ]
                    : null,

                'file_url' =>
                    url(
                        "/api/mobile/documents/{$document->id}/file"
                    ),
            ],
        ]);
    }


    /**
     * Stream original document file.
     */
    public function file(
        Request $request,
        Document $document,
    ): BinaryFileResponse {

        $document =
            $this->documentService
                ->getDocumentForPreview(
                    $request->user(),
                    $document,
                );

        $disk =
            Storage::disk('local');


        abort_unless(
            $disk->exists(
                $document->file_path,
            ),
            404,
            'Document file not found.',
        );


        $absolutePath =
            $disk->path(
                $document->file_path,
            );


        return response()->file(
            $absolutePath,
            [
                'Content-Type' =>
                    $document->mime_type,

                'Content-Disposition' =>
                    'inline; filename="' .
                    addslashes(
                        $document->file_name,
                    ) .
                    '"',
            ],
        );
    }
}