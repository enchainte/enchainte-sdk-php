<?php


namespace Enchainte\Tests\src\Shared\Infrastructure\Guzzle;


use Enchainte\Shared\Application\HttpClient;

final class GuzzleHttpWriterFailStub implements HttpClient
{

    public function post(string $url, array $headers, string $data): array
    {
        return [
          "success" => "false"
        ];
    }

    public function get(string $url, array $headers): array
    {
    }
}