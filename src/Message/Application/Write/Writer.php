<?php

namespace Enchainte\Message\Application\Write;

use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Message\Domain\Message;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Writer
{
    private const HOST_PARAM = 'SDK_HOST';
    private const ENDPOINT_PARAM = 'SDK_WRITE_ENDPOINT';

    private $httpClient;
    private $config;
    private $hashAlgorithm;
    private $tasks = [];
    private $apiKey;

    public function __construct(HttpClient $httpClient, Config $config, HashAlgorithm $hashAlgorithm, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->hashAlgorithm = $hashAlgorithm;

        $this->apiKey = $apiKey;
    }

    public function sendMessage(array $bytes): bool
    {
        $message = new Message($bytes, $this->hashAlgorithm);
        $this->push($message);

        return $this->send();
    }

    private function push(Message $message): void
    {
        $this->tasks[$message->hash()] = '$deferred';
    }

    private function send(): bool
    {
        if (!empty($this->tasks)) {
            $currentTasks = $this->tasks;
            $this->tasks = [];
            $hashes = [];
            foreach ($currentTasks as $hash => $deferred) {
                $hashes[] = $hash;
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
            $response = $this->httpClient->post($url, $headers, $data);

            if ($response['success'] === true) {
                return true;
            }
            return false;
        }
    }

}