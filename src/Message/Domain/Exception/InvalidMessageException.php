<?php


namespace Enchainte\Message\Domain\Exception;

use InvalidArgumentException;

final class InvalidMessageException extends InvalidArgumentException
{
    public function __construct(string $hash)
    {
        parent::__construct(sprintf('invalid message provided', $hash), 400);
    }
}
