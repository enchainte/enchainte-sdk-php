<?php

namespace Enchainte\Tests\src\Message\Domain;

use Enchainte\Message\Domain\Exception\InvalidMessageArgumentException;
use Enchainte\Message\Domain\Message;
use Enchainte\Shared\Domain\HashAlgorithm;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    /** @test
     * @group unit
     */
    public function it_should_create_a_Message_instance(): void
    {
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);
        $message = new Message([101, 110, 99, 104, 97, 105, 110, 116, 101], $hashAlgorithm);

        $this->assertInstanceOf(Message::class, $message);
    }

      /** @test
     * @group unit
     */
    public function it_should_return_an_exception_when_an_invalid_argument_is_provided(): void
    {
        $this->expectException(InvalidMessageArgumentException::class);

        $hashAlgorithm = $this->createMock(HashAlgorithm::class);
        $message = new Message(["1", 110, 99, 104, 97, 105, 110, 116, 101], $hashAlgorithm);
    }

    /** @test
     * @group unit
     */
    public function it_should_return_the_hash_property(): void
    {
        $hashAlgorithm = $this->createMock(HashAlgorithm::class);
        $message = new Message([101, 110, 99, 104, 97, 105, 110, 116, 101], $hashAlgorithm);
        print_r($message->hash());

        $this->assertInstanceOf(Message::class, $message);
        $this->assertNotNull($message->hash());
    }
}