<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class AppleTokenService
{
    /**
     * Generate Apple client_secret for token exchange.
     *
     * @return string
     */
    public function generate()
    {
        try {

            $privateKeyPath = storage_path('app/AuthKey_' . config('services.apple.key_id') . '.p8');

            if (!file_exists($privateKeyPath)) {
                throw new \Exception("Apple private key file not found at: " . $privateKeyPath);
            }

            $privateKey = file_get_contents($privateKeyPath);

            // Build JWT payload
            $payload = [
                'iss' => config('services.apple.team_id'),       // Your Apple Team ID
                'iat' => time(),                                  // Issued at
                'exp' => time() + (86400 * 180),                  // Expiration (max 180 days)
                'aud' => 'https://appleid.apple.com',             // Audience
                'sub' => config('services.apple.client_id'),      // Your Service ID (client_id)
            ];

            // Generate JWT
            $jwt = JWT::encode($payload, $privateKey, 'ES256', config('services.apple.key_id'));

            return $jwt;

        } catch (\Exception $e) {
            Log::error('AppleTokenService Error: ' . $e->getMessage());
            return null;
        }
    }
}
