<?php

namespace App\Services;

use Google\Client;
use Google\Service\AndroidPublisher;

class GooglePlayService
{
    protected $client;
    protected $androidPublisher;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-play-service-account.json'));
        $this->client->addScope(AndroidPublisher::ANDROIDPUBLISHER);

        $this->androidPublisher = new AndroidPublisher($this->client);
    }

    public function verifySubscription($packageName, $subscriptionId, $token)
    {
        dd(
            $packageName,
            $subscriptionId,
            $token
        );
        try {
            $subscription = $this->androidPublisher->purchases_subscriptions->get($packageName, $subscriptionId, $token);
            return $subscription;
        } catch (\Exception $e) {
            return null;
        }
    }
}
