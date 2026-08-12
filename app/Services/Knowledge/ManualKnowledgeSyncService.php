<?php

namespace App\Services\Knowledge;

use App\Models\ManualKnowledge;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryAliasRepository;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryRepository;

class ManualKnowledgeSyncService
{
    public function __construct(
        protected KnowledgeMemoryRepository $memoryRepository,
        protected KnowledgeMemoryAliasRepository $aliasRepository,
    ) {
    }

    /**
     * Sinkronisasi Insert / Update ke Knowledge Memory
     */
    public function sync(ManualKnowledge $manual): void
    {
        
        // Cari apakah memori untuk manual knowledge ini sudah ada via repository
        $memory = $this->memoryRepository->findBySource(
            $manual->workspace_id,
            'manual',
            $manual->id
        );

        // Siapkan kontrak data sinkronisasi
        $data = [
            'workspace_id' => $manual->workspace_id,
            'source_type'  => 'manual',
            'source_id'    => $manual->id,
            'title'        => $manual->title,
            'knowledge'    => $manual->content,
            'confidence'   => 100,
            'status'       => $manual->status === 'draft' ? 'archived' : 'active',
        ];

        // Update jika sudah ada, Create jika belum ada
       if ($memory) {

    $this->memoryRepository->update($memory, $data);

} else {

    $this->memoryRepository->create($data);

}
    }

    /**
     * Sinkronisasi Delete Knowledge Memory beserta seluruh aliasnya
     */
    public function remove(ManualKnowledge $manual): void
    {
        // Ambil memori via repository
        $memories = $this->memoryRepository->getBySource(
            $manual->workspace_id,
            'manual',
            $manual->id
        );

        foreach ($memories as $memory) {
            // Hapus semua alias dari memory ini terlebih dahulu
            $this->aliasRepository->deleteByMemory($memory->id);
            
            // Hapus memory
            $this->memoryRepository->delete($memory);
        }
    }
}