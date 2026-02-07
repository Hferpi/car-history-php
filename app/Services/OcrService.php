<?php
namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Support\Facades\Log;

class OcrService
{
    public function extractText(string $path): string
    {
        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($path);

            // Pre-procesamiento para maximizar lectura de precios y fechas
            $image->greyscale()->contrast(15);

            $tempPath = storage_path('app/temp_' . uniqid() . '.png');
            $image->save($tempPath);

            $ocr = new TesseractOCR($tempPath);

            // IMPORTANTE: Si estás en Windows, descomenta la línea de abajo y pon tu ruta
            // $ocr->executable('C:\Program Files\Tesseract-OCR\tesseract.exe');

            $text = $ocr->lang('spa')->run();

            if (file_exists($tempPath)) unlink($tempPath);

            return $text;
        } catch (\Exception $e) {
            Log::error("Fallo crítico OCR: " . $e->getMessage());
            throw new \Exception("El motor OCR no respondió correctamente.");
        }
    }
}