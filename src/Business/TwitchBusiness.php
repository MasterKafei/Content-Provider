<?php

namespace App\Business;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchBusiness
{
    public function __construct(
        private readonly HttpClientInterface $twitchAuthentication,
    )
    {

    }

    public function getAccessToken(): string|null
    {
        return $this->twitchAuthentication->request(Request::METHOD_POST, '')->toArray()['access_token'] ?? null;
    }
}
