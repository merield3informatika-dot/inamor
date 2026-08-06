<?php

namespace App\Services\KnowledgeMemory;

use App\DataTransferObjects\KnowledgeSearchResult;
use App\Models\KnowledgeMemory;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryAliasRepository;
use App\Repositories\KnowledgeMemory\KnowledgeMemoryRepository;
use App\Services\Retrieval\QuestionNormalizer;
use App\Services\KnowledgeMemory\HighlightBuilder;

final class KnowledgeSearchService
{
    private readonly float $weightAliasExact;
    private readonly float $weightAliasPartial;
    private readonly float $weightTitle;
    private readonly float $weightBody;
    private readonly float $ambiguityGapThreshold;
    private readonly float $minCoverageShort;
    private readonly float $minCoverageLong;
    private readonly int $shortQueryLimit;
    private readonly int $minConfidence;
    private readonly int $maxCandidates;

    public function __construct(
        private readonly KnowledgeMemoryRepository $memoryRepository,
        private readonly KnowledgeMemoryAliasRepository $aliasRepository,
        private readonly QuestionNormalizer $normalizer,
        private readonly HighlightBuilder $highlightBuilder,
    ) {
        // Beban bobot dipusatkan di config
        $this->weightAliasExact = (float) config('knowledge.search.weights.alias_exact', 100.0);
        $this->weightAliasPartial = (float) config('knowledge.search.weights.alias_partial', 50.0);
        $this->weightTitle = (float) config('knowledge.search.weights.title', 30.0);
        $this->weightBody = (float) config('knowledge.search.weights.body', 10.0);
        
        // Celah margin untuk mendeteksi keraguan sistem (1.2 = Rank 1 harus unggul 20%)
        $this->ambiguityGapThreshold = (float) config('knowledge.search.ambiguity_gap', 1.2); 
        
        // Syarat minimal % keyword yang wajib ada di dalam dokumen
        $this->minCoverageShort = (float) config('knowledge.search.minimum_coverage_short', 1.0); // 100%
        $this->minCoverageLong = (float) config('knowledge.search.minimum_coverage_long', 0.75); // 75%
        $this->shortQueryLimit = (int) config('knowledge.search.short_query_limit', 3);
        
        $this->minConfidence = (int) config('knowledge.search.minimum_confidence', 35);
        $this->maxCandidates = (int) config('knowledge.search.max_candidates', 100);
    }

    public function search(
        int $workspaceId,
        string $question,
    ): ?KnowledgeSearchResult {

        $normalized = $this->normalizer->normalize($question);

        /*
        |--------------------------------------------------------------------------
        | Phase 0: Exact NLP Bypass
        |--------------------------------------------------------------------------
        | Jika pertanyaan user tepat sama dengan Alias yang dibuat AI, 
        | dokumen langsung dikembalikan. Tidak perlu ada skoring.
        */
        $exactMemory = $this->memoryRepository->findExactAliasMatch($workspaceId, $normalized);
        
        if ($exactMemory !== null) {
            return $this->buildResult(
                memory: $exactMemory, 
                score: (int) $this->weightAliasExact, 
                confidence: 100, 
                matchedKeywords: [$normalized], 
                matchedAliases: [$normalized]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Phase 1: Candidate Retrieval
        |--------------------------------------------------------------------------
        */
        $keywords = $this->normalizer->extractKeywords($normalized)->values()->all();
        $totalKeywords = count($keywords);

        if ($totalKeywords === 0) {
            return null;
        }

        $candidates = $this->memoryRepository->searchByKeywords(
            $workspaceId, 
            $keywords, 
            $this->maxCandidates
        );

        if ($candidates->isEmpty()) {
            return null;
        }

        // Tentukan jumlah keyword wajib match (Misal: 2 dari 2, atau 3 dari 4)
        $coverageThreshold = $totalKeywords <= $this->shortQueryLimit 
            ? $this->minCoverageShort 
            : $this->minCoverageLong;
            
        $requiredMatches = (int) ceil($totalKeywords * $coverageThreshold);

        /*
        |--------------------------------------------------------------------------
        | Phase 2: Simple & Strict Scoring
        |--------------------------------------------------------------------------
        */
        $rankings = [];

        foreach ($candidates as $memory) {
            $score = 0.0;
            $matchedCount = 0;
            $matchedKeywords = [];
            $matchedAliases = [];

            $titleLower = mb_strtolower($memory->title);
            $knowledgeLower = mb_strtolower($memory->knowledge);

            foreach ($keywords as $keyword) {
                $keywordLower = mb_strtolower($keyword);
                $isKeywordMatched = false;
                $highestScoreForThisKeyword = 0.0;

                // 1. Cek Alias
                foreach ($memory->aliases as $aliasObj) {
                    $aliasLower = mb_strtolower($aliasObj->alias);
                    
                    if ($aliasLower === $keywordLower) {
                        $highestScoreForThisKeyword = max($highestScoreForThisKeyword, $this->weightAliasExact);
                        $isKeywordMatched = true;
                        $matchedAliases[$aliasObj->alias] = $aliasObj->alias;
                    } elseif ($this->hasWordMatch($aliasLower, $keywordLower)) {
                        $highestScoreForThisKeyword = max($highestScoreForThisKeyword, $this->weightAliasPartial);
                        $isKeywordMatched = true;
                        $matchedAliases[$aliasObj->alias] = $aliasObj->alias;
                    }
                }

                // 2. Cek Title
                if ($this->hasWordMatch($titleLower, $keywordLower)) {
                    $highestScoreForThisKeyword = max($highestScoreForThisKeyword, $this->weightTitle);
                    $isKeywordMatched = true;
                }

                // 3. Cek Body
                if ($this->hasWordMatch($knowledgeLower, $keywordLower)) {
                    $highestScoreForThisKeyword = max($highestScoreForThisKeyword, $this->weightBody);
                    $isKeywordMatched = true;
                }

                // Jika kata ditemukan, tambahkan skor terbaiknya (tidak ditumpuk)
                if ($isKeywordMatched) {
                    $matchedCount++;
                    $score += $highestScoreForThisKeyword;
                    $matchedKeywords[$keyword] = $keyword;
                }
            }

            // FILTER MUTLAK: Jika dokumen "Syarat Kehadiran" hanya match kata "Syarat" (1 dari 2 wajib), BUNUH dokumen ini.
            if ($matchedCount < $requiredMatches) {
                continue;
            }

            if ($score > 0) {
                $rankings[] = [
                    'memory' => $memory,
                    'score' => $score,
                    'matchedKeywords' => array_values($matchedKeywords),
                    'matchedAliases' => array_values($matchedAliases),
                ];
            }
        }

        if (empty($rankings)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Phase 3: Ambiguity Detection (False Positive Killer)
        |--------------------------------------------------------------------------
        | Mencegah sistem asal pilih jika query terlalu generik (contoh: "jadwal").
        | Jika Dokumen A dan B sama-sama memiliki kata "jadwal" di judul, skor mereka 
        | akan sama, Gap = 1.0. Sistem akan membuangnya karena dianggap ambigu.
        */
        usort($rankings, fn ($a, $b) => $b['score'] <=> $a['score']);

        $winner = $rankings[0];

        if (count($rankings) > 1) {
            $runnerUp = $rankings[1];
            
            if ($runnerUp['score'] > 0) {
                $gap = $winner['score'] / $runnerUp['score'];
                
                if ($gap < $this->ambiguityGapThreshold) {
                    return null; // Pertanyaan terlalu ambigu, serahkan ke Fallback (AI atau "Tidak ditemukan")
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Phase 4: Confidence Check
        |--------------------------------------------------------------------------
        */
        $expectedMaxScore = $totalKeywords * max($this->weightTitle, $this->weightAliasPartial);
        
        $confidence = $expectedMaxScore > 0 
            ? min(100, (int) round(($winner['score'] / $expectedMaxScore) * 100))
            : 0;

        if ($confidence < $this->minConfidence) {
            return null;
        }

        return $this->buildResult(
            memory: $winner['memory'],
            score: (int) $winner['score'],
            confidence: $confidence,
            matchedKeywords: $winner['matchedKeywords'],
            matchedAliases: $winner['matchedAliases']
        );
    }

    /**
     * Memastikan keyword ditemukan sebagai kata yang utuh, bukan potongan.
     * Menggunakan Regex Boundary (\b) yang ringan, cepat, dan aman dari false match (pkl vs ipkl).
     */
    private function hasWordMatch(string $text, string $keyword): bool
    {
        $quoted = preg_quote($keyword, '/');
        
        return preg_match('/\b' . $quoted . '\b/iu', $text) === 1;
    }

    private function buildResult(
        KnowledgeMemory $memory,
        int $score,
        int $confidence,
        array $matchedKeywords,
        array $matchedAliases
    ): KnowledgeSearchResult {
        return new KnowledgeSearchResult(
            memory: $memory,
            score: $score,
            confidence: $confidence,
            matchedKeywords: $matchedKeywords,
            matchedAliases: $matchedAliases,
            highlight: $this->highlightBuilder->build(
                $memory->knowledge,
                $matchedKeywords
            ),
            pageNumber: $memory->page_number,
        );
    }
}