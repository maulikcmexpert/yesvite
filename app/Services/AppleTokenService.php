<?php
namespace App\Services;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Ecdsa\MultibyteStringConverter;
use Lcobucci\JWT\Signer\Key\InMemory;

class AppleTokenService
{
    protected Configuration $config;

    public function __construct()
    {
        $privateKey = config('services.apple.private_key');

        // if (empty($privateKey)) {
        //     throw new \RuntimeException('Apple private key is not set.');
        // }

        $signer = new Sha256(new MultibyteStringConverter());

        // $this->config = Configuration::forAsymmetricSigner(
        //     $signer,
        //     InMemory::plainText($privateKey),
        //     InMemory::empty() // No public key required for signing
        // );
    }

    public function generate(): string
    {
        $now = new \DateTimeImmutable();

        $token = $this->config->builder()
            ->issuedBy(env('APPLE_TEAM_ID')) // Team ID
            ->issuedAt($now)
            ->expiresAt($now->modify('+6 months'))
            ->withHeader('kid', env('APPLE_KEY_ID')) // Key ID
            ->withClaim('aud', 'https://appleid.apple.com')
            ->withClaim('sub', env('APPLE_CLIENT_ID')) // Service ID
            ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
