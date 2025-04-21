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
            // Convert \n in env string to actual new lines
            $privateKey = str_replace("\\n", "\n", env('APPLE_PRIVATE_KEY'));

            $payload = [
                'iss' => config('services.apple.team_id'),
                'iat' => time(),
                'exp' => time() + (86400 * 180),
                'aud' => 'https://appleid.apple.com',
                'sub' => config('services.apple.client_id'),
            ];

            $jwt = JWT::encode($payload, $privateKey, 'ES256', config('services.apple.key_id'));

            return $jwt;

        } catch (\Exception $e) {
            Log::error('AppleTokenService Error: ' . $e->getMessage());
            return null;
        }
    }
}
