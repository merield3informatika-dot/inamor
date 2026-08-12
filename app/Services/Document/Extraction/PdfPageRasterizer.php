<?php

namespace App\Services\Document\Extraction;

use App\Exceptions\Document\ExtractionFailedException;
use Illuminate\Support\Str;
use ImagickException;

final class PdfPageRasterizer
{
    /**
     * Render a single PDF page to a temporary PNG file and return its path.
     * Caller is responsible for deleting the returned file.
     */
    public function rasterizePage(string $pdfPath, int $pageIndex): string
    {
        if (! extension_loaded('imagick')) {

            throw new ExtractionFailedException(
                'Ekstensi PHP "imagick" tidak tersedia di server. Diperlukan untuk memproses PDF hasil scan.'
            );

        }

        $dpi = (int) config('document.ocr.pdf_render_dpi', 200);

        $tempDir = storage_path('app/private/tmp');

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir . DIRECTORY_SEPARATOR . Str::uuid() . '.png';

        try {

            $imagick = new \Imagick();

            $imagick->setResolution($dpi, $dpi);

            $imagick->readImage($pdfPath . '[' . $pageIndex . ']');

            $imagick->setImageBackgroundColor('white');

            $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

            $imagick->setImageFormat('png');

            $imagick->writeImage($tempPath);

            $imagick->clear();

            $imagick->destroy();

            return $tempPath;

        } catch (ImagickException $e) {

            throw new ExtractionFailedException(
                'Gagal merender halaman PDF menjadi gambar. Pastikan Ghostscript terinstall dan dikenali oleh ImageMagick.',
                previous: $e
            );

        }
    }
}