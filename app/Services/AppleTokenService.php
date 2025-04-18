<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Ecdsa\MultibyteStringConverter;
use Lcobucci\JWT\Signer\Key\InMemory;

class AppleTokenService
{
    protected Configuration $config;

    public function __construct()
    {
        $privateKey = env('APPLE_PRIVATE_KEY');
        $dummyPublicKey = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7z4lZ9n6A1X5zD6g6iX2' . PHP_EOL . '-----END PUBLIC KEY-----';

        if (empty($privateKey)) {
            throw new \RuntimeException('Apple private key is not set.');
        }

        $signer = new Sha256(new MultibyteStringConverter());

        $this->config = Configuration::forAsymmetricSigner(
            $signer,
            InMemory::plainText($privateKey),
            InMemory::plainText($dummyPublicKey)
        );
    }

    public function generate(): string
    {
        $now = CarbonImmutable::now();

        $token = $this->config->builder()
            ->issuedBy(env('APPLE_TEAM_ID')) // Team ID
            ->issuedAt($now)
            ->expiresAt($now->addMonths(6))
            ->withHeader('kid', env('APPLE_KEY_ID')) // Key ID
            ->withClaim('aud', 'https://appleid.apple.com')
            ->withClaim('sub', env('APPLE_CLIENT_ID')) // Service ID
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
