<?php

namespace Enchainte\Message\Application\Write;

use Enchainte\Shared\Application\Config;
use Enchainte\Message\Domain\Deferred;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Message\Domain\Message;

final class Writer
{
    const HOST_PARAM = 'SDK_HOST';
    const ENDPOINT_PARAM = 'SDK_PROOF_ENDPOINT';

    private $httpClient;
    private $tasks = [];
    private $config;

    // TODO singleton
    // TODO send every x time

    public function __construct(HttpClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    public function sendMessage(string $message, callable $resolve, callable $reject, string $token): bool
    {
        $message = new Message($message);

        $ok = $this->push($message, $resolve, $reject);

        // TODO resolve one thread problem
    }

    private function push(Message $message, callable $resolve, callable $reject): bool
    {
        $deferred = new Deferred($resolve, $reject);
        $this->tasks[$message->hash()] = $deferred;
        // Return the Promise-like callback from the getPromise Deferred method
    }

    // http request
    private function send(string $token): void
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
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ];
            // Create new Message with messages array as argument
            $data = json_encode(["hashes" => $hashes]);
            // Execute the write method of the API Service.
            $response = $this->httpClient->post($data);
            // Execute the callback for every Deferred entity sent (resolve if the write was successful or reject otherwise).
            foreach ($currentTasks as $deferred) {
                if ($response === true) {
                    $deferred->resolve();
                } else {
                    $deferred->reject();
                }
            }
        }
    }

}