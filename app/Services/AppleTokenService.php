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
                $teamId = '7RU5Y6V7KP';
                $clientId = 'yesvite.web';
                $keyId = 'K78KWT7UX6';
                $privateKey = file_get_contents(storage_path('AuthKey_K78KWT7UX6.p8'));

                $token = [
                    'iss' => $teamId,
                    'iat' => time(),
                    'exp' => time() + 86400 * 180,
                    'aud' => 'https://appleid.apple.com',
                    'sub' => $clientId,
                ];

                $jwt = JWT::encode($token, $privateKey, 'ES256', $keyId);
            } catch (\Exception $e) {
                Log::error('AppleTokenService Error: ' . $e->getMessage());
                return null;
            }
        }

}
