<?php

namespace Enchainte\Tests\src\Message\Application\Verify;

use Enchainte\Message\Application\Verify\Verifier;
use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpProofStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3SuccessfulValidationStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3UnsuccessfulValidationStub;
use PHPUnit\Framework\TestCase;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;

final class VerifierTest extends TestCase
{
    const API_KEY = "uwtIk-iBhdkYjMdMgCGP0EywI4F8vsfuQjIIN7Z8mEzPpc4XbW2EfhqrxrZG2Uez";
    const MESSAGES_DATA = [
        [101, 110, 99, 104, 97, 105, 110, 116, 101],
        [101, 110, 99, 104, 97, 105, 110, 116, 100]
    ];

    /** @test */
    public function it_should_return_true_when_a_message_is_valid(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $proofFinder = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);

        $blockchainClient = new Web3SuccessfulValidationStub();
        $proofVerifier = new ProofVerifier($blockchainClient, $hashAlgorithm);

        $messageVerifier = new Verifier($proofFinder, $proofVerifier, self::API_KEY);

        $this->assertTrue($messageVerifier->verifyMessages(self::MESSAGES_DATA));
    }

    /** @test */
    public function it_should_return_false_when_a_message_is_valid(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $proofFinder = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);

        $blockchainClient = new Web3UnsuccessfulValidationStub();
        $proofVerifier = new ProofVerifier($blockchainClient, $hashAlgorithm);

        $messageVerifier = new Verifier($proofFinder, $proofVerifier, self::API_KEY);

        $this->assertFalse($messageVerifier->verifyMessages(self::MESSAGES_DATA));
    }

}