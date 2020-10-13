<?php

namespace Enchainte\Tests\src\Shared\Infrastructure\Guzzle;

use Enchainte\Shared\Application\HttpClient;

final class GuzzleHttpMessageReceiptStub implements HttpClient
{
    public function post(string $url, array $headers, string $data)
    {
        return json_encode([
            "root" => "0f5c62817a529e0610d1fbf5c999bd53188f7f6958e4fb3aadd4a451e34bdc64",
            "message" => "5ac706bdef87529b22c08646b74cb98baf310a46bd21ee420814b04c71fa42b1",
            "tx_hash" => "0xa46a26ba75af77fb778e88e202fca34875d6b1b0fc717bb00082b913ac08037b",
            "status" => "success",
            "error" => 0
        ]);
    }

    public function get(string $url, array $headers): array
    {
        return [];
    }
}