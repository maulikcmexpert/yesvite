<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use Exception;
class AppleTokenService
{
    /**
     * Generate Apple client_secret for token exchange.
     *
     * @return string
     */
    public function generate()
    {
        // Path to your .p8 private key file
        $keyFilePath = env('APPLE_PRIVATE_KEY'); // Get from .env

        // Check if the private key file exists
        if (!file_exists($keyFilePath)) {
            Log::error('Apple private key file not found at: ' . $keyFilePath);
            throw new Exception('Apple private key file not found');
        }

        // Load the private key from the file
        $privateKey = file_get_contents($keyFilePath);

        // Apple-specific credentials - you need to replace these with your actual values
        $teamId = env('APPLE_TEAM_ID');          // Your Apple Developer Team ID
        $clientId = env('APPLE_CLIENT_ID');      // Your Apple Service ID (App Identifier)
        $keyId = env('APPLE_KEY_ID');            // Your Apple Key ID for the private key
        $expirationTime = time() + 86400;        // Expiration time for the client secret (1 day)

        // Payload for JWT
        $payload = [
            'iss' => $teamId,         // Issuer (your team ID)
            'iat' => time(),          // Issued at (current time)
            'exp' => $expirationTime, // Expiration time (1 day)
            'aud' => 'https://appleid.apple.com', // Audience (Apple)
            'sub' => $clientId        // Subject (Your client ID)
        ];

        // Generate the JWT using the private key
        try {
            $jwt = JWT::encode($payload, $privateKey, 'ES256', $keyId); // 'ES256' is the algorithm
        } catch (Exception $e) {
            Log::error('Error generating JWT for Apple: ' . $e->getMessage());
            throw new Exception('Error generating JWT for Apple');
        }

        // Return the generated client secret
        return $jwt;
    }
}
