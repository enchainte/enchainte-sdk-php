<?php

namespace Enchainte\Shared\Infrastructure\Guzzle;

use Enchainte\Shared\Application\HttpClient;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

final class GuzzleHttp implements HttpClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function post(string $url, array $headers, array $data): array
    {
        try {
            $response = $this->client->request("POST", $url, [
                "headers" => $headers,
                "json" => $data
            ]);

        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $jsonResponse = (string) $response->getBody();
            throw new Exception($jsonResponse);
        }

        $content = (string) $response->getBody();

        return json_decode($content, true);
    }

    public function get(string $url, array $headers): array
    {
        try {
            $response = $this->client->request("GET", $url, [
                "headers" => $headers,
            ]);
        } catch (BadResponseException $exception) {
            $response = $exception->getResponse();
            $jsonResponse = (string) $response->getBody();
            throw new Exception($jsonResponse);
        }

        $content = (string)$response->getBody();
        return json_decode($content, true);
    }
}