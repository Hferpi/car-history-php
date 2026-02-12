<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $apiKey;
    // Usamos Gemini 3 Flash Preview, que aparece en tu lista como disponible
    protected $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent";

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function parseInvoice($imagePath)
    {
        try {
            if (!file_exists($imagePath)) return ['error' => 'Archivo no encontrado'];

            $imageData = base64_encode(file_get_contents($imagePath));
            $client = new Client(['verify' => false]);

            $url = $this->apiUrl . "?key=" . trim($this->apiKey);

            $response = $client->post($url, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    "contents" => [
                        [
                            "parts" => [
                                ["text" => "Analiza esta factura y extrae en JSON: fecha (YYYY-MM-DD), taller_nombre, precio (numérico), tipo_servicio, observaciones. Responde SOLO el objeto JSON."],
                                [
                                    "inline_data" => [
                                        "mime_type" => "image/jpeg",
                                        "data" => $imageData
                                    ]
                                ]
                            ]
                        ]
                    ],
                    // Gemini 3 maneja mejor la configuración de respuesta
                    "generationConfig" => [
                        "response_mime_type" => "application/json"
                    ]
                ]
            ]);

            $body = json_decode($response->getBody(), true);

            if (isset($body['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $body['candidates'][0]['content']['parts'][0]['text'];
                return json_decode(trim($text), true) ?? ['error' => 'Error parseando JSON', 'raw' => $text];
            }

            return ['error' => 'Respuesta vacía', 'debug' => $body];

        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
