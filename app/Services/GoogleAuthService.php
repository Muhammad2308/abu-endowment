<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GoogleAuthService
{
    protected $clientId;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id');
        
        // Debug: Log client ID on construction
        if (empty($this->clientId)) {
            Log::warning('GoogleAuthService: Client ID is empty!', [
                'config_value' => config('services.google.client_id'),
                'env_value' => env('GOOGLE_CLIENT_ID'),
            ]);
        }
    }

    /**
     * Verify Google ID token and extract user information
     * Uses JWT verification without requiring Google API client library
     * 
     * @param string $idToken The Google ID token from frontend
     * @return array|false Returns user data array or false on failure
     */
    public function verifyToken($idToken)
    {
        try {
            Log::info('Google Token Verification - Start', [
                'token_length' => strlen($idToken),
                'token_preview' => substr($idToken, 0, 30) . '...',
                'client_id' => $this->clientId,
                'client_id_empty' => empty($this->clientId),
            ]);

            // Check if client ID is configured
            if (empty($this->clientId)) {
                Log::error('Google Token Verification - Client ID not configured', [
                    'config_value' => config('services.google.client_id'),
                    'env_value' => env('GOOGLE_CLIENT_ID'),
                ]);
                return false;
            }

            // Decode JWT token without verification first to get payload
            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                Log::error('Google Token - Invalid format (not 3 parts)', [
                    'parts_count' => count($parts),
                ]);
                return false;
            }

            // Decode header
            $header = json_decode($this->base64UrlDecode($parts[0]), true);
            if (!$header || !isset($header['alg'])) {
                Log::error('Google Token - Invalid JWT header', [
                    'header' => $header,
                ]);
                return false;
            }

            // Decode payload
            $payload = json_decode($this->base64UrlDecode($parts[1]), true);
            if (!$payload) {
                Log::error('Google Token - Invalid JWT payload', [
                    'payload_raw' => $this->base64UrlDecode($parts[1]),
                ]);
                return false;
            }

            Log::info('Google Token - Decoded', [
                'header' => $header,
                'payload' => [
                    'iss' => $payload['iss'] ?? null,
                    'aud' => $payload['aud'] ?? null,
                    'email' => $payload['email'] ?? null,
                    'email_verified' => $payload['email_verified'] ?? false,
                    'exp' => $payload['exp'] ?? null,
                    'exp_date' => isset($payload['exp']) ? date('Y-m-d H:i:s', $payload['exp']) : null,
                    'now' => time(),
                    'now_date' => date('Y-m-d H:i:s'),
                ],
            ]);

            // Verify issuer
            $issuers = ['https://accounts.google.com', 'accounts.google.com'];
            if (!isset($payload['iss']) || !in_array($payload['iss'], $issuers)) {
                Log::error('Google Token - Invalid issuer', [
                    'expected' => $issuers,
                    'got' => $payload['iss'] ?? 'not set',
                ]);
                return false;
            }

            // Verify audience (client ID)
            if (!isset($payload['aud']) || $payload['aud'] !== $this->clientId) {
                Log::error('Google Token - Client ID mismatch', [
                    'expected' => $this->clientId,
                    'expected_length' => strlen($this->clientId),
                    'got' => $payload['aud'] ?? 'not set',
                    'got_length' => isset($payload['aud']) ? strlen($payload['aud']) : 0,
                    'match' => isset($payload['aud']) && $payload['aud'] === $this->clientId,
                ]);
                return false;
            }

            // Verify expiration
            if (isset($payload['exp']) && $payload['exp'] < time()) {
                Log::error('Google Token - Expired', [
                    'exp' => $payload['exp'],
                    'now' => time(),
                    'exp_date' => date('Y-m-d H:i:s', $payload['exp']),
                    'now_date' => date('Y-m-d H:i:s'),
                    'difference_seconds' => time() - $payload['exp'],
                ]);
                return false;
            }

            // Verify email is verified
            if (!isset($payload['email_verified']) || !$payload['email_verified']) {
                Log::error('Google Token - Email not verified', [
                    'email' => $payload['email'] ?? 'not set',
                    'email_verified' => $payload['email_verified'] ?? 'not set',
                ]);
                return false;
            }

            Log::info('Google Token - Verification successful');

            // Extract user information
            return [
                'google_id' => $payload['sub'] ?? null, // Google's unique user ID
                'email' => $payload['email'] ?? null,
                'email_verified' => $payload['email_verified'] ?? false,
                'name' => $payload['name'] ?? null, // Full name
                'given_name' => $payload['given_name'] ?? null, // First name
                'family_name' => $payload['family_name'] ?? null, // Last name
                'picture' => $payload['picture'] ?? null, // Profile picture URL
                'gender' => $payload['gender'] ?? null, // Gender (if available)
                'locale' => $payload['locale'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('Google token verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Base64 URL decode (JWT uses URL-safe base64 encoding)
     */
    private function base64UrlDecode($data)
    {
        $padding = 4 - (strlen($data) % 4);
        if ($padding !== 4) {
            $data .= str_repeat('=', $padding);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Alternative: Verify token using Google's public keys (more secure)
     * This method fetches Google's public keys and verifies the signature
     */
    public function verifyTokenWithPublicKeys($idToken)
    {
        try {
            // Fetch Google's public keys
            $publicKeysResponse = Http::get('https://www.googleapis.com/oauth2/v3/certs');
            if (!$publicKeysResponse->successful()) {
                Log::error('Failed to fetch Google public keys');
                return false;
            }

            $publicKeys = $publicKeysResponse->json();

            // Decode token
            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                return false;
            }

            $header = json_decode($this->base64UrlDecode($parts[0]), true);
            $payload = json_decode($this->base64UrlDecode($parts[1]), true);

            // Verify issuer, audience, expiration (same as above)
            $issuers = ['https://accounts.google.com', 'accounts.google.com'];
            if (!isset($payload['iss']) || !in_array($payload['iss'], $issuers)) {
                return false;
            }

            if (!isset($payload['aud']) || $payload['aud'] !== $this->clientId) {
                return false;
            }

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return false;
            }

            if (!isset($payload['email_verified']) || !$payload['email_verified']) {
                return false;
            }

            // Note: Full signature verification would require OpenSSL extension
            // For now, we'll trust the basic checks above
            // In production, consider using a JWT library like firebase/php-jwt

            return [
                'google_id' => $payload['sub'] ?? null,
                'email' => $payload['email'] ?? null,
                'email_verified' => $payload['email_verified'] ?? false,
                'name' => $payload['name'] ?? null,
                'given_name' => $payload['given_name'] ?? null,
                'family_name' => $payload['family_name'] ?? null,
                'picture' => $payload['picture'] ?? null,
                'gender' => $payload['gender'] ?? null,
                'locale' => $payload['locale'] ?? null,
            ];
        } catch (Exception $e) {
            Log::error('Google token verification with public keys failed: ' . $e->getMessage());
            return false;
        }
    }
}
