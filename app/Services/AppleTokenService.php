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
                $privateKey = str_replace("\\n", "\n", env('APPLE_PRIVATE_KEY'));

                $teamId = config('services.apple.team_id');
                $clientId = config('services.apple.client_id');
                $keyId = config('services.apple.key_id');

                $payload = [
                    'iss' => $teamId,
                    'iat' => time(),
                    'exp' => time() + (86400 * 180),
                    'aud' => 'https://appleid.apple.com',
                    'sub' => $clientId,
                ];

                $headers = [
                    'kid' => $keyId,
                    'alg' => 'ES256',
                ];

                return JWT::encode($payload, $privateKey, 'ES256', null, $headers);
            } catch (\Exception $e) {
                Log::error('AppleTokenService Error: ' . $e->getMessage());
                return null;
            }
        }

}
