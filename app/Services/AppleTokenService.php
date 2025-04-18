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
        $privateKey =" -----BEGIN PRIVATE KEY-----\nMIGTAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBHkwdwIBAQQgGooFxSUMUT+tW2lWwols0QisOsAvc3IYgPzHtmGC0fOgCgYIKoZIzj0DAQehRANCAARVPwZyulCjrOGW4bk55Ghv9RQMl2NaeFthrncNDr8oFN1uhfuqWuyF3AB1trpgDVwIP0TyBfj49SL4hM67MslS\n-----END PRIVATE KEY-----";

        if (empty($privateKey)) {
            throw new \RuntimeException('Apple private key is not set.');
        }

        // Replace literal '\n' with actual newline characters
        $privateKey = str_replace('\n', "\n", $privateKey);

        $signer = new Sha256(new MultibyteStringConverter());

        // $this->config = Configuration::forAsymmetricSigner(
        //     $signer,
        //     InMemory::plainText($privateKey),
        //     InMemory::plainText('') // Provide an empty public key if not required
        // );
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
?>
