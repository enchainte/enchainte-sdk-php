<?php

namespace Enchainte\Message\Application\Write;

use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Message\Domain\Message;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Writer
{
    private const HOST_PARAM = 'SDK_HOST';
    private const ENDPOINT_PARAM = 'SDK_PROOF_ENDPOINT';

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

        // TODO find a way for asynchronism

        $this->apiKey = $apiKey;
    }

//    public function sendMessage(string $message, callable $resolve, callable $reject, string $token): bool
    public function sendMessage(array $bytes): bool
    {
        $message = new Message($bytes, $this->hashAlgorithm);
        $this->push($message);
        return $this->send();
    }

//    private function push(Message $message, callable $resolve, callable $reject): void
    private function push(Message $message): void
    {
//        $deferred = new Deferred($resolve, $reject);
        // TODO value for the Assoc?
        $this->tasks[$message->hash()] = '$deferred';
        // Return the Promise-like callback from the getPromise Deferred method
    }

    private function send(): bool
    {
        // Check if there are any messages in the tasks map attribute. If not, don’t do anything else
        if (!empty($this->tasks)) {
            // Make a local copy of the tasks map attribute to get a static version of it
            $currentTasks = $this->tasks;
            // Clear the tasks map attribute
            $this->tasks = [];
            // Get all messages to be sent
            $hashes = [];
            foreach ($currentTasks as $hash => $deferred) {
                $hashes[] = $hash;
            }

            $url = sprintf(
                "https://%s/%s",
                $this->config->params()[self::HOST_PARAM],
                $this->config->params()[self::ENDPOINT_PARAM]
            );
            $headers = [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept'        => 'application/json',
            ];
            // Create new Message with messages array as argument
            $data = json_encode(["hashes" => $hashes]);
            // Execute the write method of the API Service.
            $response = $this->httpClient->post($url, $headers, $data);

            if ($response['success'] === "true") {
                return true;
            }
            return false;
            // Execute the callback for every Deferred entity sent (resolve if the write was successful or reject otherwise).
//            foreach ($currentTasks as $deferred) {
//                if ($response["success"] === "true") {
//                // TODO call_user_func??
//                     $deferred->resolve();
//                } else {
//                    call_user_func($deferred->reject());
//                }
//            }
        }
    }

}