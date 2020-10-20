<?php

namespace Enchainte\Tests\src\Proof\Application\Find;

use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpProofStub;
use PHPUnit\Framework\TestCase;

final class FinderTest extends TestCase
{
    const API_KEY = "uwtIk-iBhdkYjMdMgCGP0EywI4F8vsfuQjIIN7Z8mEzPpc4XbW2EfhqrxrZG2Uez";
    const MESSAGES_DATA = [
        [101, 110, 99, 104, 97, 105, 110, 116, 101],
        [101, 110, 99, 104, 97, 105, 110, 116, 100]
    ];

    /** @test */
    public function it_should_return_a_Proof_instance(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $proofProvider = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);
        $proof = $proofProvider->getProof(self::MESSAGES_DATA);

        $this->assertInstanceOf(Proof::class, $proof);
    }
}