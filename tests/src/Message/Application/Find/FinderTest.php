<?php


namespace Enchainte\Tests\src\Message\Application\Find;


use Enchainte\Message\Application\Find\Finder;
use Enchainte\Message\Application\Find\MessageReceipt;
use Enchainte\Shared\Application\Config;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpMessageReceiptStub;
use PHPUnit\Framework\TestCase;

final class FinderTest extends TestCase
{
    const TOKEN = "";
    const MESSAGE_DATA = "123456789abcde";

    /** @test */
    public function it_should_return_a_message_receipt_instance()
    {
        $httpClient = new GuzzleHttpMessageReceiptStub();
        $config = $this->createMock(Config::class);

        $messageFinder = new Finder($httpClient, $config);

        $messageReceipt = $messageFinder->getMessages([], self::TOKEN);

        $this->assertInstanceOf(MessageReceipt::class, $messageReceipt);
    }


}