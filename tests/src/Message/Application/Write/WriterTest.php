<?php

namespace Enchainte\Tests\src\Message\Application\Write;

use Enchainte\Message\Application\Write\Writer;
use Enchainte\Shared\Application\Config;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpWriterFailStub;
use Enchainte\Tests\src\Shared\Infrastructure\Guzzle\GuzzleHttpWriterSuccessStub;
use PHPUnit\Framework\TestCase;

final class WriterTest extends TestCase
{
    const API_KEY = "uwtIk-iBhdkYjMdMgCGP0EywI4F8vsfuQjIIN7Z8mEzPpc4XbW2EfhqrxrZG2Uez";

    /** @test */
    public function it_should_successfully_write_a_message(): void
    {
        $httpClient = new GuzzleHttpWriterSuccessStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = new Blake2b();

        $writer  = new Writer($httpClient, $config, $hashAlgorithm, self::API_KEY);
        $result = $writer->sendMessage([101, 110, 99, 104, 97, 105, 110, 116, 101]);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_should_fail_write_a_message(): void
    {
        $httpClient = new GuzzleHttpWriterFailStub();
        $config = $this->createMock(Config::class);
        $hashAlgorithm = new Blake2b();

        $writer  = new Writer($httpClient, $config, $hashAlgorithm, self::API_KEY);
        $result = $writer->sendMessage([101, 110, 99, 104, 97, 105, 110, 116, 101]);

        $this->assertFalse($result);
    }

}