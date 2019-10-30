<?php

namespace Coderello\FaviconGenerator;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class RealFaviconGeneratorClient
{
    protected const BASE_URL = 'https://realfavicongenerator.net/api/';

    protected $http;

    public function __construct(Client $http)
    {
        $this->http = $http;
    }

    public function favicon(array $payload = [])
    {
        return json_decode(
            $this->http
                ->post(self::BASE_URL.'/favicon', [
                    RequestOptions::JSON => $payload,
                    RequestOptions::HTTP_ERRORS => false,
                ])
                ->getBody()
                ->getContents(),
            true
        );
    }

    public function versions(array $payload = [])
    {
        return json_decode(
            $this->http
                ->get(self::BASE_URL.'/versions', [
                    RequestOptions::QUERY => $payload,
                    RequestOptions::HTTP_ERRORS => false,
                ])
                ->getBody()
                ->getContents(),
            true
        );
    }
}
