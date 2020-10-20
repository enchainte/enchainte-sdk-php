<?php


namespace Enchainte\Tests\src\Shared\Infrastructure\Guzzle;


use Enchainte\Shared\Application\HttpClient;

class GuzzleHttpWriterSuccessStub implements HttpClient
{
    public function post(string $url, array $headers, string $data): array
    {
        return [
          "success" => "true",
        ];
    }

    public function get(string $url, array $headers): array
    {
    }
}