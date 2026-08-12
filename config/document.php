<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Extraction Settings
    |--------------------------------------------------------------------------
    */
    'extraction' => [

        'pdf' => [

            // Jumlah karakter (tanpa whitespace) minimum agar PDF dianggap
            // "text-based". Di bawah ini, PDF dianggap hasil scan → OCR fallback.
            'min_text_length' => (int) env('DOCUMENT_PDF_MIN_TEXT_LENGTH', 50),

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | OCR Settings
    |--------------------------------------------------------------------------
    */
    'ocr' => [

        'enabled' => (bool) env('DOCUMENT_OCR_ENABLED', false),

        // Path absolut ke tesseract.exe. WAJIB diisi jika OCR diaktifkan.
        'binary_path' => env('DOCUMENT_OCR_BINARY_PATH', ''),

        'language' => env('DOCUMENT_OCR_LANGUAGE', 'ind'),

        'psm' => (int) env('DOCUMENT_OCR_PSM', 3),

        'timeout' => (int) env('DOCUMENT_OCR_TIMEOUT', 120),

        // DPI untuk merender halaman PDF menjadi gambar sebelum OCR.
        'pdf_render_dpi' => (int) env('DOCUMENT_OCR_PDF_RENDER_DPI', 200),

    ],

    /*
    |--------------------------------------------------------------------------
    | Upload Settings
    |--------------------------------------------------------------------------
    */
    'upload' => [

        'max_size_kb' => (int) env('DOCUMENT_MAX_SIZE_KB', 20480),

    ],

];