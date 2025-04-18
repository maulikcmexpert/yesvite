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

        $dummyPublicKey = openssl_pkey_get_private($privateKey);

        if (empty($privateKey)) {
            throw new \RuntimeException('Apple private key is not set.');
        }
        $keyDetails = openssl_pkey_get_details($dummyPublicKey);
        $publicKeyPem = $keyDetails['key'];
        $signer = new Sha256(new MultibyteStringConverter());

        $this->config = Configuration::forAsymmetricSigner(
            $signer,
            InMemory::plainText($privateKey),
            InMemory::plainText($publicKeyPem)
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
        ->permittedFor('https://appleid.apple.com') // Audience
        ->relatedTo(env('APPLE_CLIENT_ID')) // Subject
        ->getToken($this->config->signer(), $this->config->signingKey());

        return $token->toString();
    }
}
