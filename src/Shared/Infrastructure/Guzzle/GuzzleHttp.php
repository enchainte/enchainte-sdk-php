<?php

namespace Enchainte\Shared\Infrastructure\Guzzle;

use Enchainte\Shared\Application\HttpClient;
use GuzzleHttp\Client;

final class GuzzleHttp implements HttpClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function post(string $url, array $headers, string $data)
    {
        return $this->client->request("POST", $url, [
            "headers" => $headers,
            "json" => $data
        ]);
    }

    public function get(string $url, array $headers): array
    {
        $response = $this->client->request("GET", $url, [
            "headers" => $headers,
        ]);
        // return for each one in the request response
        return json_decode($response, true);
    }
}