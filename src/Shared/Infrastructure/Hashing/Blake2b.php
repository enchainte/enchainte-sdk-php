<?php

namespace Enchainte\Shared\Infrastructure\Hashing;

use deemru\Blake2b as deemruBlake2b;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Blake2b implements HashAlgorithm
{
    private $blake2b;

    public function __construct()
    {
        $this->blake2b = new deemruBlake2b();
    }

    public function hash($message): string
    {
        return $this->blake2b->hash($message);
    }
}