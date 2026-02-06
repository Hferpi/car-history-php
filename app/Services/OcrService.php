<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function extractText(string $imagePath): string
    {
        return (new TesseractOCR($imagePath))
            ->lang('spa')
            ->run();
    }
}
