<?php

namespace Enchainte\Proof\Application\Find;

use Enchainte\Shared\Application\Config;
use Enchainte\Message\Domain\Message;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Finder
{
    const HOST_PARAM = 'SDK_HOST';
    const ENDPOINT_PARAM = 'SDK_PROOF_ENDPOINT';

    private $httpClient;
    private $config;
    private $hashAlgorithm;

    public function __construct(HttpClient $httpClient, Config $config, HashAlgorithm $hashAlgorithm)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->hashAlgorithm = $hashAlgorithm;
    }

    public function getProof(array $messages, string $token): Proof
    {
        $hash = [];
        foreach ($messages as $messageData) {
            $hash[] = (new Message($messageData, $this->hashAlgorithm))->hash();
        }

        sort($hash);
        $url = sprintf(
            "https://%s/%s",
            $this->config->params()[self::HOST_PARAM],
            $this->config->params()[self::ENDPOINT_PARAM]
        );
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];
        $data = json_encode(["hashes" => $hash]);
        $response = $this->httpClient->post($url, $headers, $data);
        $response = json_decode($response, true);

        return new Proof(
            $response["messages"],
            $response["nodes"],
            $response["depth"],
            $response["bitmap"],
            $this->hashAlgorithm
        );
    }
}