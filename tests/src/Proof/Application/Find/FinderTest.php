<?php


namespace Enchainte\Tests\src\Proof\Application\Find;


use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\Config;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpProofStub;
use PHPUnit\Framework\TestCase;

final class FinderTest extends TestCase
{
    const TOKEN = "";
    // TODO change to bytes
    const MESSAGES_DATA = ["123456789abcde", "123456789abcdf"];

    /** @test */
    public function it_should_return_a_Proof_instance(): void
    {
        $httpClient = new GuzzleHttpProofStub();
        $config = $this->createMock(Config::class);

        $proofProvider = new Finder($httpClient, $config);
        $proof = $proofProvider->getProof(self::MESSAGES_DATA, self::TOKEN);

        $this->assertInstanceOf(Proof::class, $proof);
    }
}