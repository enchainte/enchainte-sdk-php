<?php


namespace Enchainte\Proof\Domain\Exception;

use InvalidArgumentException;

final class InvalidProofArgumentException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct("invalid argument provided: only hexadecimal string accepted", 400);
    }
}