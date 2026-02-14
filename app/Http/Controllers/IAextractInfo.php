<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
//use App\Services\OcrService; prueba
//use App\Services\AiService; prueba
use App\Services\GeminiService; // NUEVO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class IAextractInfo extends Controller
{

public function extract(Request $request, Vehicle $vehicle, GeminiService $gemini)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        try {
            $disk = 'public';
            $path = $request->file('foto')->store('receipts', $disk);
            $fullPath = storage_path('app/public/' . $path);

            //Gemini procesa la imagen directamente
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

