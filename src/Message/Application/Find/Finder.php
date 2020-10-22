<?php

namespace Enchainte\Message\Application\Find;

use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Message\Domain\Message;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Finder
{
    private const HOST_PARAM = 'SDK_HOST';
    private const ENDPOINT_PARAM = 'SDK_FETCH_ENDPOINT';

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

    public function getMessages(array $bytesArray): array
    {
        // create Message instances
        // validate each one with isValid() method
        $messages = [];
        foreach ($bytesArray as $bytes) {
            $messages[] = new Message($bytes, $this->hashAlgorithm);
        }
        // stringify message array
        $hashes = [];
        foreach ($messages as $message) {
            $hashes[] = $message->hash();
        }

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

        $responses = $this->httpClient->post($url, $headers, $data);

        $messageReceipts = [];
        foreach ($responses as $response){

            $messageReceipts[] =  new MessageReceipt(
                $response["root"],
                $response["message"],
                $response["tx_hash"],
                $response["status"],
                $response["error"]
            );
        }

        return $messageReceipts;
    }
}
