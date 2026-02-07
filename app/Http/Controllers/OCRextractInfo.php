<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\OcrService;
use App\Services\AiService; // Importamos el nuevo servicio
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class OCRextractInfo extends Controller
{
    public function extract(Request $request, Vehicle $vehicle, OcrService $ocr, AiService $ai)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        try {
            $disk = 'public';
            $path = $request->file('foto')->store('receipts', $disk);
            $fullPath = storage_path('app/public/' . $path);

            $rawText = $ocr->extractText($fullPath);

            if (empty(trim($rawText))) {
                return back()->withErrors(['ocr' => 'El OCR no detectó texto legible.']);
            }

            $smartData = $ai->parseReceiptText($rawText);

            $ocrData = [
                'vehiculo_id'   => $vehicle->id,
                'fecha'         => isset($smartData['fecha'])
                                    ? Carbon::parse($smartData['fecha'])->format('Y-m-d')
                                    : now()->format('Y-m-d'),
                'taller_nombre' => $smartData['taller_nombre'] ?? 'Desconocido',
                'precio'        => (float) ($smartData['precio'] ?? 0),
                'tipo_servicio' => $smartData['tipo_servicio'] ?? 'Mantenimiento General',
                'observaciones' => $smartData['observaciones'] ?? null,
                'image_path'    => $path,
                'foto_disk'     => $disk,
            ];

            return view('vehicles.repair', compact('vehicle', 'ocrData'));

        } catch (\Exception $e) {
            Log::error("Error en proceso OCR/IA: " . $e->getMessage());
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