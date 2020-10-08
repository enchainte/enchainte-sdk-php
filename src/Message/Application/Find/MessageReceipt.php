<?php

namespace Enchainte\Message\Application\Find;


final class MessageReceipt
{
    private $root;
    private $message;
    private $txHash;
    private $status;
    private $error;

    public function __construct(string $root, string $message, string $txHash, string $status, int $error)
    {
        $this->root = $root;
        $this->message = $message;
        $this->txHash = $txHash;
        $this->status = $status;
        $this->error = $error;
    }

    public function root(): string
    {
        return $this->root;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function txHash(): string
    {
        return $this->txHash;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function error(): int
    {
        return $this->error;
    }
}