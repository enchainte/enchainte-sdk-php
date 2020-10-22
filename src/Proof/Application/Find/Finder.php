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
    private $apiKey;

    public function __construct(HttpClient $httpClient, Config $config, HashAlgorithm $hashAlgorithm, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->hashAlgorithm = $hashAlgorithm;
        $this->apiKey = $apiKey;
    }

    public function getProof(array $bytesArray): ?Proof
    {
        $hashes = [];
        foreach ($bytesArray as $bytes) {
            $hashes[] = (new Message($bytes, $this->hashAlgorithm))->hash();
        }

        sort($hashes);

        $url = sprintf(
            "%s%s",
            $this->config->params()[self::HOST_PARAM],
            $this->config->params()[self::ENDPOINT_PARAM]
        );
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type'  => 'application/json',
        ];
        $data = ["hashes" => $hashes];
        $response = $this->httpClient->post($url, $headers, $data);

        return new Proof(
            $hashes,
            $response["nodes"],
            $response["depth"],
            $response["bitmap"],
            $this->hashAlgorithm
        );
    }
}