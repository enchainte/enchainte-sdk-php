<?php


namespace Enchainte\Tests\src\Shared\Infrastructure\Guzzle;


use Enchainte\Shared\Application\HttpClient;

final class GuzzleHttpProofStub implements HttpClient
{

    public function post(string $url, array $headers, string $data): array
    {
        return [
            "messages" => [
                '82aae7e86eb51f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
                '92aae7e86eb51f61a620831320475d9d61cbd52749dbf18fa942b1b97f50aee9',
                ],
            "nodes" => [
                '285f570a90100fb94d5608b25d9e2b74bb58f068d495190f469aac5ef7ecf3c5',
                '8f0194b0986e0ea2d6e24df52f1fb3d44e421bce224383f7805f38dc772b3489',
                ],
            "depth" => '01030302',
            "bitmap" => 'a0'
        ];
    }

    public function get(string $url, array $headers): array
    {
        return [];
    }
}