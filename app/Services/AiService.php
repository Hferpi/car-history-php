<?php

namespace App\Services;

use OpenAI;

class AiService
{
    public function parseReceiptText(string $text): array
    {
        $client = OpenAI::factory()
            ->withApiKey(env('GROQ_API_KEY'))
            ->withBaseUri('https://api.groq.com/openai/v1')
            ->make();

        $prompt = "Analiza este texto extraído por OCR de una factura de taller mecánico.
Extrae los datos siguiendo estas reglas críticas:

1. **fecha**: Busca la fecha de emisión o creación (formato YYYY-MM-DD). Ignora fechas de vencimiento futuras.
2. **taller_nombre**: Extrae el nombre comercial del taller. Evita nombres genéricos como 'TALLER DE EJEMPLO' o 'PROGRAMA DEMOSTRATIVO' si aparece otro nombre real.
3. **precio**: Busca el 'Total Importe' o 'Total Factura'. Es un número decimal separa por numeros si tiene esapcios que suele estar al final o cerca de palabras como 'Total', 'Importe' o 'Total Factura'.
4. **tipo_servicio**: Haz un resumen breve de los conceptos (ej: 'Revisión de niveles, frenos y correa').
5. **observaciones**: Incluye el kilometraje si aparece (ej: 'Kms: 12900') y cualquier anotación sobre piezas.

Texto OCR:
'{$text}'

Responde estrictamente en formato JSON válido:";

        $result = $client->chat()->create([
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => 'Eres un extractor de datos profesional que solo responde en JSON.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'response_format' => ['type' => 'json_object']
        ]);

        return json_decode($result->choices[0]->message->content, true) ?? [];
    }
}
