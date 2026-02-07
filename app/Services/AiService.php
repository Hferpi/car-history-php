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

        $prompt = "Analiza este texto OCR de un recibo de taller de coche.
        Extrae la información y devuelve estrictamente un objeto JSON.
        Si un dato no existe, usa null.

        Campos:
        - fecha (formato YYYY-MM-DD)
        - taller_nombre (nombre del establecimiento)
        - precio (total final como número)
        - tipo_servicio (resumen de la reparación)
        - observaciones (detalles extra como piezas o km)

        Texto: '{$text}'";

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