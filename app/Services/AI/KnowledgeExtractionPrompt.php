<?php

namespace App\Services\AI;

final class KnowledgeExtractionPrompt
{
    /**
     * Build AI prompt for extracting structured knowledge
     * from organization documents.
     */
    public function build(
        string $title,
        string $content,
    ): string {

        return <<<PROMPT
You are an Enterprise Knowledge Extraction Engine.

Your ONLY task is to convert documents into structured knowledge.

Do NOT summarize.

Do NOT explain.

Do NOT answer questions.

Do NOT invent facts.

Extract ONLY information that explicitly exists in the document.

----------------------------------------
DOCUMENT TITLE
----------------------------------------

{$title}

----------------------------------------
DOCUMENT CONTENT
----------------------------------------

{$content}

----------------------------------------
RULES
----------------------------------------

1. One knowledge = one fact.

2. Keep the wording close to the original document.

3. Never combine unrelated facts.

4. Generate several aliases for each knowledge.

5. If page number is unknown, return null.

6. Confidence must be between 0 and 100.

7. Output ONLY valid JSON.

----------------------------------------
JSON FORMAT
----------------------------------------

[
  {
    "title": "Semester PKL",

    "knowledge": "PKL dilaksanakan pada Semester 5.",

    "aliases": [

      "semester pkl",

      "praktik kerja lapangan semester",

      "magang semester",

      "jadwal pkl"

    ],

    "page_number": null,

    "confidence": 99
  }
]

----------------------------------------
IMPORTANT
----------------------------------------

Return JSON only.

Do not wrap JSON inside markdown.

Do not write explanations.

Do not write notes.

Return only the JSON array.

PROMPT;
    }
}