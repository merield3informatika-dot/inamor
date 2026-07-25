<?php

namespace App\Http\Controllers;

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
            ->getDocuments($request->user()),
    ]);
}

    public function create()
    {
        return view('documents.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'document' => ['required', 'file', 'mimes:pdf', 'max:20480'], // 20MB
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
}