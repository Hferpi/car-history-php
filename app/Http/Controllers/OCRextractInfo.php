<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
//use App\Services\OcrService; prueba
//use App\Services\AiService; prueba
use App\Services\GeminiService; // NUEVO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OCRextractInfo extends Controller
{
    // public function extract(Request $request, Vehicle $vehicle, OcrService $ocr, AiService $ai)
    // {
    //     $request->validate([
    //         'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240'
    //     ]);

    //     try {
    //         $disk = 'public';
    //         $path = $request->file('foto')->store('receipts', $disk);
    //         $fullPath = storage_path('app/public/' . $path);

    //         $rawText = $ocr->extractText($fullPath);

    //         if (empty(trim($rawText))) {
    //             return back()->withErrors(['ocr' => 'El OCR no detectó texto legible.']);
    //         }

    //         $smartData = $ai->parseReceiptText($rawText);


    //         $precio = $smartData['precio'];
    //         if (is_string($precio)) {
    //             $precio = str_replace([',', '€'], ['.', ''], $precio);
    //         }
    //         $rawPrice = $smartData['precio'] ?? 0;

    //         // Limpieza profesional de strings de moneda
    //         // 1. Quitamos espacios y símbolos de Euro
    //         $cleanPrice = str_replace([' ', '€'], '', $rawPrice);
    //         // 2. Si tiene coma y no tiene punto, cambiamos coma por punto
    //         if (strpos($cleanPrice, ',') !== false && strpos($cleanPrice, '.') === false) {
    //             $cleanPrice = str_replace(',', '.', $cleanPrice);
    //         }
    //         // 3. Si tiene puntos de miles y coma decimal (ej: 1.250,50)
    //         else if (strpos($cleanPrice, ',') !== false && strpos($cleanPrice, '.') !== false) {
    //             $cleanPrice = str_replace('.', '', $cleanPrice);
    //             $cleanPrice = str_replace(',', '.', $cleanPrice);
    //         }

    //         $ocrData = [
    //             'vehiculo_id'   => $vehicle->id,
    //             'fecha'         => isset($smartData['fecha']) ? Carbon::parse($smartData['fecha'])->format('Y-m-d') : now()->format('Y-m-d'),
    //             'taller_nombre' => $smartData['taller_nombre'] ?? 'Desconocido',
    //             'precio'        => (float) $cleanPrice,
    //             'tipo_servicio' => $smartData['tipo_servicio'] ?? 'Mantenimiento General',
    //             'observaciones' => $smartData['observaciones'] ?? null,
    //             'image_path'    => $path,
    //             'foto_disk'     => $disk,
    //         ];

    //         return view('vehicles.repair', compact('vehicle', 'ocrData'));
    //     } catch (\Exception $e) {
    //         Log::error("Error en proceso OCR/IA: " . $e->getMessage());
    //         return back()->withErrors(['ocr' => 'Error técnico: ' . $e->getMessage()]);
    //     }
    // }

//-------------------------------------------------------------------------------------------------------

public function extract(Request $request, Vehicle $vehicle, GeminiService $gemini)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        try {
            $disk = 'public';
            $path = $request->file('foto')->store('receipts', $disk);
            $fullPath = storage_path('app/public/' . $path);

            // NUEVO: Gemini procesa la imagen directamente
            $smartData = $gemini->parseInvoice($fullPath);

            if (empty($smartData)) {
                return back()->withErrors(['ocr' => 'Gemini no pudo extraer datos de la imagen.']);
            }

            // Limpieza del precio (Gemini suele devolverlo ya limpio, pero por seguridad):
            $precio = $smartData['precio'] ?? 0;
            if (is_string($precio)) {
                $precio = (float) str_replace([',', '€', ' '], ['.', '', ''], $precio);
            }

            $ocrData = [
                'vehiculo_id'   => $vehicle->id,
                'fecha'         => isset($smartData['fecha']) ? Carbon::parse($smartData['fecha'])->format('Y-m-d') : now()->format('Y-m-d'),
                'taller_nombre' => $smartData['taller_nombre'] ?? 'Desconocido',
                'precio'        => $precio,
                'tipo_servicio' => $smartData['tipo_servicio'] ?? 'Mantenimiento General',
                'observaciones' => $smartData['observaciones'] ?? null,
                'image_path'    => $path,
                'foto_disk'     => $disk,
            ];

            // Retornamos a la misma vista de siempre, que ya sabe pintar $ocrData
            return view('vehicles.repair', compact('vehicle', 'ocrData'));

        } catch (\Exception $e) {
            Log::error("Error con Gemini: " . $e->getMessage());
            return back()->withErrors(['ocr' => 'Error técnico: ' . $e->getMessage()]);
        }
    }


    private function formatDate($date): string
    {
        try {
            return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : now()->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return now()->format('Y-m-d H:i:s');
        }
    }
}

