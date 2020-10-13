<?php


namespace Enchainte\Tests\src\Message\Domain;


use Enchainte\Message\Domain\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    /** @test  */
    public function it_should_create_a_Message_instance(): void
    {
        $message = new Message("Hello, world!");

        $this->assertInstanceOf(Message::class, $message);
    }

    /** @test  */
    public function it_should_return_the_hash_property(): void
    {
        $message = new Message("Hello, world!");

        $this->assertInstanceOf(Message::class, $message);
        $this->assertNotNull($message->hash());
    }
}