<?php

namespace Enchainte\Message\Application\Find;


use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Application\HttpClient;
use Enchainte\Shared\Domain\Message;

final class Finder
{
    const HOST_PARAM = 'SDK_HOST';
    const ENDPOINT_PARAM = 'SDK_FETCH_ENDPOINT';

    private $httpClient;
    private $config;

    public function __construct(HttpClient $httpClient, Config $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    public function getMessages(array $messagesHash, string $token): MessageReceipt
    {
        // create Message instances
        // validate each one with isValid() method
        $messages = [];
        foreach ($messagesHash as $hash) {
            $messages[] = new Message($hash);
        }
        // stringify message array
        $messagesHash = [];
        foreach ($messages as $message) {
            $messagesHash[] = $message->hash();
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
        $data = json_encode($messagesHash);

        $response = $this->httpClient->post($url, $headers, $data);
        // return MessageReceipt
        $response = json_decode($response, true);

        return new MessageReceipt(
            $response["root"],
            $response["message"],
            $response["tx_hash"],
            $response["status"],
            $response["error"]
        );
    }
}
