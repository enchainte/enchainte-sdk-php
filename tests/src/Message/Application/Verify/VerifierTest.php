<?php

namespace Enchainte\Tests\src\Message\Application\Verify;

use Enchainte\Message\Application\Verify\Verifier;
use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Shared\Application\Config;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpProofStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3SuccessfulValidationStub;
use Enchainte\Tests\src\Shared\Infrastructure\Web3\Web3UnsuccessfulValidationStub;
use PHPUnit\Framework\TestCase;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;

class VerifierTest extends TestCase
{
    const TOKEN = "";
    // TODO change to bytes
    const MESSAGES_DATA = ["123456789abcde", "123456789abcdf"];

    /** @test */
    public function it_should_return_true_when_a_message_is_valid(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);

        $proofFinder = new Finder($httpClient, $config);

        $blockchainClient = new Web3SuccessfulValidationStub();
        $proofVerifier = new ProofVerifier($blockchainClient);

        $messageVerifier = new Verifier($proofFinder, $proofVerifier);

        $this->assertTrue($messageVerifier->verifyMessages(self::MESSAGES_DATA, self::TOKEN));
    }

    /** @test */
    public function it_should_return_false_when_a_message_is_valid(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);

        $proofFinder = new Finder($httpClient, $config);

        $blockchainClient = new Web3UnsuccessfulValidationStub();
        $proofVerifier = new ProofVerifier($blockchainClient);

        $messageVerifier = new Verifier($proofFinder, $proofVerifier);

        $this->assertFalse($messageVerifier->verifyMessages(self::MESSAGES_DATA, self::TOKEN));
    }

}