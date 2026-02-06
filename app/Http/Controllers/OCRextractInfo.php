<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\OcrService;
use Illuminate\Http\Request;

class OCRextractInfo extends Controller
{
    public function extract(Request $request, Vehicle $vehicle, OcrService $ocr)
    {
        // Validar que sea una imagen
        $request->validate([
            'foto' => 'required|image|max:5120'
        ]);

        // Guardar la foto
        $path = $request->file('foto')->store('receipts', 'public');

        // Extraer texto con OCR
        $text = $ocr->extractText(storage_path('app/public/' . $path));

        // Procesar texto para sacar campos (mejorado)
        $ocrData = [
            'precio' => $this->extractPrecio($text),
            'fecha' => $this->extractFecha($text),
            'taller_nombre' => $this->extractTaller($text),
            'tipo_servicio' => $this->extractTipoServicio($text),
            'raw_text' => $text,
            'observaciones' => null,
            'image_path' => $path,
        ];

        // Retornar a la vista con los datos OCR
        return view('vehicles.repair', compact('vehicle', 'ocrData'));
    }

    private function extractPrecio(string $text): ?float
    {
        // Normalizar el texto para facilitar la búsqueda
        $normalizedText = strtoupper(str_replace([',', ' ', "\n", "\r"], ['.', '_', ' ', ' '], $text));

        // Patrones de búsqueda para precios comunes
        $patterns = [
            // Total, Importe, Precio, Monto
            '/TOTAL[=:_\s]*([\d.,]+\s*€?)/i',
            '/IMPORTE[=:_\s]*([\d.,]+\s*€?)/i',
            '/PRECIO[=:_\s]*([\d.,]+\s*€?)/i',
            '/MONTO[=:_\s]*([\d.,]+\s*€?)/i',
            '/TOTAL\s*[:\-–=]?\s*(\d{1,3}(?:[.,]?\d{3})*(?:[.,]\d{2})\s*€?)/i',

            // Patrones generales de números con decimales
            '/(\d{1,3}[.,]?\d{3}[.,]\d{2})\s*€?/',
            '/(\d+[.,]\d{2})\s*€?/',

            // Buscar posibles totales al final del documento
            '/(\d{1,3}(?:[.,]?\d{3})*(?:[.,]\d{2}))\s*(?:EUR|€|EUROS)?$/mi',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $text, $matches)) {
                foreach ($matches[1] as $match) {
                    $cleaned = preg_replace('/[^\d.,]/', '', $match);

                    // Formatear el número correctamente (manejar puntos y comas como separadores)
                    $number = $this->formatNumber($cleaned);

                    if ($number > 0) { // Asumimos que un precio válido debe ser mayor que 0
                        return $number;
                    }
                }
            }
        }

        // Si no encontró nada con patrones específicos, buscar cualquier número que parezca precio
        $numbers = [];
        preg_match_all('/\b\d{1,3}(?:[.,]?\d{3})*(?:[.,]\d{2})\b/', $text, $matches);

        foreach ($matches[0] as $num) {
            $formatted = $this->formatNumber($num);
            if ($formatted > 0) {
                $numbers[] = $formatted;
            }
        }

        // Si hay varios números, tomar el mayor (probablemente sea el total)
        if (!empty($numbers)) {
            return max($numbers);
        }

        return null;
    }

    private function formatNumber(string $number): float
    {

        // Contar puntos y comas para determinar el formato
        $dots = substr_count($number, '.');
        $commas = substr_count($number, ',');

        if ($dots === 1 && $commas === 0) {
            // Formato americano: 1234.56
            return (float) str_replace(',', '', $number);
        } elseif ($commas === 1 && $dots === 0) {
            // Formato sin separador de miles: 1234,56
            return (float) str_replace('.', '', $number);
        } elseif ($dots >= 1 && $commas === 1) {
            // Formato con separador de miles y decimal: 1.234,56
            return (float) str_replace([',', '.'], ['', ''], $number);
        } elseif ($commas >= 1 && $dots === 1) {
            // Formato con separador de miles y decimal: 1,234.56
            return (float) str_replace(',', '', $number);
        }

        // Si solo hay dígitos
        if (is_numeric($number)) {
            return (float) $number;
        }

        return 0;
    }

    private function extractFecha(string $text): ?string
    {
        // Patrones para diferentes formatos de fecha
        $patterns = [
            '/(\d{2}\/\d{2}\/\d{4})/',      // dd/mm/yyyy
            '/(\d{2}-\d{2}-\d{4})/',       // dd-mm-yyyy
            '/(\d{2}\.\d{2}\.\d{4})/',     // dd.mm.yyyy
            '/(\d{1,2}\/\d{1,2}\/\d{4})/', // d/m/yyyy o dd/m/yyyy o d/mm/yyyy
            '/(\d{4}-\d{2}-\d{2})/',       // yyyy-mm-dd (ISO)
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $date = $matches[1];

                // Validar que sea una fecha real
                if ($this->isValidDate($date)) {
                    return $date;
                }
            }
        }

        return null;
    }

    private function isValidDate(string $date): bool
    {
        // Convertir diferentes formatos a un formato estándar para validación
        $formats = ['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d'];

        foreach ($formats as $format) {
            $d = \DateTime::createFromFormat($format, $date);
            if ($d && $d->format($format) === $date) {
                return true;
            }
        }

        return false;
    }

    private function extractTaller(string $text): ?string
    {
        // Patrones más flexibles para encontrar el nombre del taller
        $patterns = [
            '/RAZÓN SOCIAL[=:_\s]*(.+)/i',
            '/NOMBRE COMERCIAL[=:_\s]*(.+)/i',
            '/EMPRESA[=:_\s]*(.+)/i',
            '/TALLER[=:_\s]*(.+)/i',
            '/PROVEEDOR[=:_\s]*(.+)/i',
            '/NIF[:\s]*[A-Z]?\d{1,8}[A-Z]?\s*\n(.+)/i', // Después de NIF/CIF
            '/CIF[:\s]*[A-Z]?\d{1,8}[A-Z]?\s*\n(.+)/i', // Después de CIF/NIF
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $result = trim($matches[1]);
                // Limpiar líneas adicionales y caracteres especiales
                $result = preg_split('/[\r\n]+/', $result)[0];
                return trim($result, " \t\n\r\0\x0B-–_");
            }
        }

        // Si no encontró con patrones específicos, tomar la primera línea que parezca un nombre
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $line = trim($line);
            if (strlen($line) > 5 && !is_numeric(str_replace(['.', ',', '-', ' '], '', $line))) {
                return $line;
            }
        }

        return null;
    }

    private function extractTipoServicio(string $text): ?string
    {
        // Patrones para encontrar el tipo de servicio
        $patterns = [
            '/SERVICIO[=:_\s]*(.+)/i',
            '/CONCEPTO[=:_\s]*(.+)/i',
            '/DESCRIPCIÓN[=:_\s]*(.+)/i',
            '/TRABAJO REALIZADO[=:_\s]*(.+)/i',
            '/TIPO DE SERVICIO[=:_\s]*(.+)/i',
            '/MANTENIMIENTO[=:_\s]*(.+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }

        // Si no encontró con patrones específicos, buscar líneas después de palabras clave
        $keywords = ['REPARACIÓN', 'MANTENIMIENTO', 'SERVICIO', 'TRABAJO'];
        $lines = explode("\n", $text);

        for ($i = 0; $i < count($lines); $i++) {
            $line = strtoupper(trim($lines[$i]));
            foreach ($keywords as $keyword) {
                if (strpos($line, $keyword) !== false) {
                    // Devolver la siguiente línea si existe
                    if (isset($lines[$i + 1]) && trim($lines[$i + 1]) !== '') {
                        return trim($lines[$i + 1]);
                    }
                }
            }
        }

        return null;
    }
}
