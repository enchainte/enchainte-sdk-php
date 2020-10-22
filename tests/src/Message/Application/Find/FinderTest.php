<?php


namespace Enchainte\Tests\src\Message\Application\Find;


use Enchainte\Message\Application\Find\Finder;
use Enchainte\Message\Application\Find\MessageReceipt;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpExceptionStub;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpMessageReceiptStub;
use Exception;
use PHPUnit\Framework\TestCase;

final class FinderTest extends TestCase
{
    const API_KEY = "";

    /** @test
     * @group unit
     */
    public function it_should_return_a_message_receipt_instance(): void
    {
        $httpClient = new GuzzleHttpMessageReceiptStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $messageFinder = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);

        $messageReceipts = $messageFinder->getMessages(
            [
                [101, 110, 99, 104, 97, 105, 110, 116, 101],
            ]
        );

        $this->assertIsArray($messageReceipts);
        $this->assertContainsOnlyInstancesOf(MessageReceipt::class, $messageReceipts);
    }

    /** @test
     * @group unit
     */
    public function it_should_return_an_exception_when_something_went_wrong(): void
    {
        $this->expectException(Exception::class);
        $httpClient = new GuzzleHttpExceptionStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);

        $messageFinder = new Finder($httpClient, $config, $hashAlgorithm, self::API_KEY);

        $messageReceipts = $messageFinder->getMessages(
            [
                [101, 110, 99, 104, 97, 105, 110, 116, 101],
            ]
        );
    }

}