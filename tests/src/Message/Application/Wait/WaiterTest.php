<?php

namespace Enchainte\Tests\src\Message\Application\Wait;


use Enchainte\Message\Application\Find\Finder;
use Enchainte\Message\Application\Find\MessageReceipt;
use Enchainte\Message\Application\Waite\Waiter;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpMessageReceiptStub;
use PHPUnit\Framework\TestCase;

class WaiterTest extends TestCase
{
    const API_KEY = "";

    /** @test
     * @group unit
     */
    public function it_should_return_an_array_of_MessageReceipts_when_search_is_completed(): void
    {
        $httpClient = new GuzzleHttpMessageReceiptStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $messageFinder = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);
        $waiter = new Waiter($messageFinder, $config);

        $messageReceipts = $waiter->waitMessageReceipt(
            [
                [101, 110, 99, 104, 97, 105, 110, 116, 101],
            ]
        );

        $this->assertIsArray($messageReceipts);
        $this->assertContainsOnlyInstancesOf(MessageReceipt::class, $messageReceipts);
    }


}