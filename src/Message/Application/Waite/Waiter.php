<?php

namespace Enchainte\Message\Application\Waite;

use Enchainte\Message\Application\Find\Finder;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Waiter
{
    private const WAIT_MESSAGE_INTERVAL = "SDK_WAIT_MESSAGE_INTERVAL_DEFAULT";
    private const WAIT_MESSAGE_INTERVAL_FACTOR = "SDK_WAIT_MESSAGE_INTERVAL_FACTOR";

    private $messageFinder;
    private $hashAlgorithm;
    private $config;

    public function __construct(Finder $messageFinder, Config $config)
    {
        $this->messageFinder = $messageFinder;
        $this->config = $config;
    }

    public function waitMessageReceipt(array $bytesArray): array
    {
        $completed = false;
        $attempts = 0;

        do {
            $messageReceipts = $this->messageFinder->getMessages($bytesArray);
            foreach ($messageReceipts as $messageReceipt) {
                if ($messageReceipt->status() === "success" || $messageReceipt->status() === "error") {
                    $completed = true;
                } else {
                    $completed = false;
                    break;
                }
            }

            if ($completed) {
                break;
            }

            usleep(
                $this->config->params()[self::WAIT_MESSAGE_INTERVAL] +
                $attempts * $this->config->params()[self::WAIT_MESSAGE_INTERVAL_FACTOR]
            );

            $attempts += 1;
        } while(!$completed);

        return $messageReceipts;
    }

}