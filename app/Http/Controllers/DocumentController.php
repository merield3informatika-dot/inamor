<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        protected DocumentService $documentService
    ) {}

    public function index(Request $request)
    {
        return view('documents.index', [
            'documents' => $this->documentService
                ->getDocuments($request->user())
                // Load relasi content agar metadata tersedia di index UI
                ->load('content'),
        ]);
    }

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $maxSizeKb = (int) config('document.upload.max_size_kb', 20480);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'document' => [
                'required',
                'file',
                'max:' . $maxSizeKb,
                'mimes:pdf,docx,xlsx,xls,csv,pptx,txt,jpg,jpeg,png,webp',
            ],
        ]);

        $this->documentService->upload(
            $request->user(),
            $request->string('title')->toString(),
            $request->file('document')
        );

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Request $request, Document $document)
    {
        $document = $this->documentService->getDocumentForPreview($request->user(), $document);

        return view('documents.show', [
            'document' => $document
        ]);
    }

    public function archive(Request $request, Document $document)
    {
        $this->documentService->archive($request->user(), $document);

        return back()->with('success', 'Document archived successfully. It will no longer be used by the AI.');
    }

    public function restore(Request $request, Document $document)
    {
        $this->documentService->restore($request->user(), $document);

        return back()->with('success', 'Document restored! AI can now retrieve knowledge from it.');
    }

    public function destroy(Request $request, Document $document)
    {
        $this->documentService->delete($request->user(), $document);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document and its extracted knowledge have been permanently deleted.');
    }
}