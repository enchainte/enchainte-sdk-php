<?php

require __DIR__ . '/vendor/autoload.php';

use Enchainte\Message\Application\Find\MessageReceipt;
use Enchainte\Message\Application\Verify\Verifier as MessageVerifier;
use Enchainte\Message\Application\Waite\Waiter;
use Enchainte\Message\Application\Write\Writer;
use Enchainte\Proof\Application\Find\Finder as ProofFinder;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;
use Enchainte\Message\Application\Find\Finder as MessageFinder;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Infrastructure\Blockchain\Web3;
use Enchainte\Shared\Infrastructure\Guzzle\GuzzleHttp;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;

final class EnchainteClient
{
    // TODO remove unused libraries from composer.json? amphp & spatie
    private $findProofService;
    private $verifyProofService;
    private $findMessageService;
    private $verifyMessageService;
    private $writerMessageService;
    private $apiKey;
    private $waitMessageReceiptService;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;

        // Add dependencies
        $httpClient = new GuzzleHttp();
        $hashAlgorithm = new Blake2b();

        // Get config params
        $sdkConfig = new Config($httpClient);
        $blockchainClient = new Web3($sdkConfig);
        $this->findProofService = new ProofFinder($httpClient, $sdkConfig, $hashAlgorithm, $apiKey);
        $this->verifyProofService = new ProofVerifier($blockchainClient, $hashAlgorithm);

        // Add all service
        $this->findMessageService = new MessageFinder($httpClient, $sdkConfig, $hashAlgorithm, $apiKey);
        $this->verifyMessageService = new MessageVerifier($this->findProofService, $this->verifyProofService, $apiKey);
        $this->writerMessageService = new Writer($httpClient, $sdkConfig, $hashAlgorithm, $apiKey);
        $this->waitMessageReceiptService = new Waiter($this->findMessageService, $sdkConfig);
    }

//    public function sendMessage(string $message, callable $resolve, callable $reject)
    public function sendMessage(array $bytes): bool
    {
        return $this->writerMessageService->sendMessage($bytes);
    }

    public function verifyMessage(array $messages): bool
    {
        return $this->verifyMessageService->verifyMessages($messages);
    }

    public function getMessages(array $hashes): array
    {
        return $this->findMessageService->getMessages($hashes);
    }

    public function waitMessageReceipt(array $bytesArray): array
    {
        return $this->waitMessageReceiptService->waitMessageReceipt($bytesArray);
    }

    public function verifyProof(array $leaves, array $nodes, string $depth, string $bitmap): bool
    {
        return $this->verifyProofService->verifyProof($leaves, $nodes, $depth, $bitmap);
    }

    public function getProof(array $bytesArray): ?array
    {
        return $this->findProofService->getProof($bytesArray);
    }

    //helper methods
    public function hex2Bytes(string $hash): array
    {
        return array_map('hexdec', str_split($hash, 2));
    }

    public function string2Bytes(string $hash): array
    {
        return array_values(unpack("C*", $hash));
    }

    public function bytes2String(array $bytes): string
    {
        $chars = array_map("chr", $bytes);
        return  join($chars);
    }

    public function bytes2Hex(array $bytes): string
    {
        $chars = array_map("chr", $bytes);
        $bin = join($chars);

        return bin2hex($bin);
    }

}

// TODO make utils param 2 bytes converter
// TODO cuando no encuentra lo solicitado, excepcion o null?
