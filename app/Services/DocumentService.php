<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use App\Repositories\DocumentRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentService
{
    public function __construct(
        protected DocumentRepository $documentRepository,
        protected DocumentProcessorService $documentProcessorService,
    ) {}

    public function getDocuments(User $user)
    {
        $workspace = $user->workspaceMemberships()
            ->first()
            ->workspace;

        return $this->documentRepository
            ->getByWorkspace($workspace->id);
    }

    public function upload(
        User $user,
        string $title,
        UploadedFile $file
    ): Document {
        return DB::transaction(function () use ($user, $title, $file) {

            $workspace = $user->workspaceMemberships()
                ->first()
                ->workspace;

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs(
                "documents/{$workspace->id}",
                $fileName,
                'local'
            );

            $document = $this->documentRepository->create([
                'workspace_id' => $workspace->id,
                'uploaded_by'  => $user->id,
                'title'        => $title,
                'file_name'    => $fileName,
                'file_path'    => $path,
                'mime_type'    => $file->getMimeType(),
                'file_size'    => $file->getSize(),
                'status'       => 'pending',
            ]);

            // Proses dokumen (extract text + simpan ke document_contents)
            $this->documentProcessorService->process($document);

            return $document;
        });
    }
}