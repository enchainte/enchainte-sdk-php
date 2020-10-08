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

    public function post(string $url, array $headers, string $data): bool
    {
        $response = $this->client->request("POST", $url, [
            "headers" => $headers,
            "json" => $data
        ]);

        // Return true if success else false
        // TODO ask what does response return
        if ($response != ok){
            return false;
        }
        return true;
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