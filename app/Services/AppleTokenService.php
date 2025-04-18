<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Ecdsa\Sha256;
use Lcobucci\JWT\Signer\Ecdsa\MultibyteStringConverter;
use Lcobucci\JWT\Signer\Key\InMemory;

class AppleTokenService
{
    public function generate(): string
    {
        $privateKey = str_replace('\\n', "\n", env('APPLE_PRIVATE_KEY'));

        $signer = new Sha256(new MultibyteStringConverter());

        $config = Configuration::forAsymmetricSigner(
            $signer,
            InMemory::plainText($privateKey),
            InMemory::empty()
        );

        $now = CarbonImmutable::now();

        $token = $config->builder()
            ->issuedBy(env('APPLE_TEAM_ID'))
            ->issuedAt($now)
            ->expiresAt($now->addMonths(6))
            ->withHeader('kid', env('APPLE_KEY_ID'))
            ->withClaim('aud', 'https://appleid.apple.com')
            ->withClaim('sub', env('APPLE_CLIENT_ID'))
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }
}
