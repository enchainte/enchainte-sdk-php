<?php

use Enchainte\Message\Application\Find\MessageReceipt;
use Enchainte\Message\Application\Verify\Verifier as MessageVerifier;
use Enchainte\Message\Application\Write\Writer;
use Enchainte\Proof\Application\Find\Finder as ProofFinder;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;
use Enchainte\Message\Application\Find\Finder as MessageFinder;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Infrastructure\Guzzle\GuzzleHttp;

final class EnchainteClient
{
    private $findProofService;
    private $verifyProofService;
    private $findMessageService;
    private $verifyMessageService;
    private $writerMessageService;
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        // Add dependencies
        $httpClient = new GuzzleHttp();
        // TODO decouple Hashing library

        // Get config params
        $sdkConfig = new Config($httpClient);

        // Add all service
        $this->findProofService = new ProofFinder($httpClient, $sdkConfig);
        $this->verifyProofService = new ProofVerifier();

        $this->findMessageService = new MessageFinder($httpClient, $sdkConfig);
        $this->verifyMessageService = new MessageVerifier($this->findProofService, $this->verifyProofService);
        $this->writerMessageService = new Writer($httpClient, $sdkConfig);
    }
    // TODO WaitMessageReceipt

    public function sendMessage(string $message, callable $resolve, callable $reject): bool
    {
        return $this->writerMessageService->sendMessage($message, $resolve, $reject, $this->apiKey);
    }

    // TODO
    public function verifyMessage(array $messages): bool
    {
        return $this->verifyMessageService->verifyMessages($messages, $this->apiKey);
    }

    public function getMessages(array $hashes): MessageReceipt
    {
        return $this->findMessageService->getMessages($hashes, $this->apiKey);
    }

    // TODO
    public function verifyProof(array $leaves, array $nodes, string $depth, string $bitmap): bool
    {
        return $this->verifyProofService->verifyProof($leaves, $nodes, $depth, $bitmap);
    }

    public function getProof(array $messages): Proof
    {
        return $this->findProofService->getProof($messages, $this->apiKey);
    }
}
