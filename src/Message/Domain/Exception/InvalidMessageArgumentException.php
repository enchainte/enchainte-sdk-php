<?php


namespace Enchainte\Message\Domain\Exception;

use InvalidArgumentException;

final class InvalidMessageArgumentException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("invalid argument provided: only byte array accepted ", 400);
    }
}