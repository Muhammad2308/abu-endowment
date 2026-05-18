<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SquadService
{
    protected string $secretKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.squad.secret_key') ?: '';
        $this->baseUrl = config('services.squad.base_url', 'https://api-d.squadco.com');
    }

    public function verifyTransaction(string $reference): array
    {
        if (empty($this->secretKey)) {
            return [
                'success' => false,
                'error' => 'Squad secret key is not configured.',
                'data' => null,
            ];
        }

        try {
            $response = Http::withToken($this->secretKey)
                ->acceptJson()
                ->timeout(30)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if ($response->failed()) {
                Log::error('Squad verify transaction failed', [
                    'reference' => $reference,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'error' => 'Squad API request failed.',
                    'data' => $response->json(),
                ];
            }

            $payload = $response->json();
            return [
                'success' => true,
                'data' => $payload['data'] ?? $payload,
                'raw' => $payload,
            ];
        } catch (\Exception $e) {
            Log::error('Squad verify transaction exception', [
                'reference' => $reference,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
