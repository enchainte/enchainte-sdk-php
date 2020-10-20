<?php

namespace Enchainte\Shared\Infrastructure\Guzzle;

use Enchainte\Shared\Application\HttpClient;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

final class GuzzleHttp implements HttpClient
{
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function post(string $url, array $headers, string $data): array
    {
        try {
            $response = $this->client->request("POST", $url, [
                "headers" => $headers,
                "json" => $data
            ]);
        } catch (RequestException $exception) {
            $msg = sprintf("error during request to %s: %s\n",$url, $exception->getMessage());
            throw new Exception($msg);
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
        } catch (RequestException $exception) {
            $msg = sprintf("error during request to %s: %s\n",$url, $exception->getMessage());
            throw new Exception($msg);
        }

        $content = (string)$response->getBody();
        return json_decode($content, true);
    }
}