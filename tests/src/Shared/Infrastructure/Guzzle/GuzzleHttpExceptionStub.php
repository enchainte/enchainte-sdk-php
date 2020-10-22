<?php

namespace Enchainte\Tests\src\Shared\Infrastructure\Guzzle;

use Enchainte\Shared\Application\HttpClient;
use Exception;

final class GuzzleHttpExceptionStub implements HttpClient
{
    public function post(string $url, array $headers, array $data): array
    {
        throw new Exception("something went wrong");
    }

    public function get(string $url, array $headers): array
    {
        throw new Exception("something went wrong");
    }
}