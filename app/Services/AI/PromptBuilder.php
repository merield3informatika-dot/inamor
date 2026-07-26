<?php

namespace App\Services\AI;

final class PromptBuilder
{
    private readonly string $systemRole;
    private readonly string $fallbackAnswer;

    public function __construct()
    {
        $this->systemRole = (string) config('knowledge.prompt.system_role', 'You are an AI Knowledge Assistant.');
        $this->fallbackAnswer = (string) config('knowledge.prompt.fallback_answer', 'Informasi tidak ditemukan pada dokumen.');
    }

    public function build(string $context, string $question): string
    {
        return <<<PROMPT
{$this->systemRole}

{$this->rules()}

========================
CONTEXT
========================

{$context}

========================
QUESTION
========================

{$question}
PROMPT;
    }

    private function rules(): string
    {
        return implode("\n", [
            '- Answer ONLY using the information provided in the context above.',
            '- Never hallucinate or invent information that is not present in the context.',
            '- If multiple documents are relevant, synthesize the answer using all of them.',
            '- If the information does not exist in the context, reply exactly: "' . $this->fallbackAnswer . '"',
        ]);
    }
}