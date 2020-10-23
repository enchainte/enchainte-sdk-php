<?php

namespace Enchainte\Message\Domain;

use Enchainte\Message\Domain\Exception\InvalidMessageArgumentException;
use Enchainte\Shared\Domain\HashAlgorithm;


final class Message
{
    private $hash;
    private $hashAlgorithm;

    public function __construct(array $bytes, HashAlgorithm $hashAlgorithm)
    {
        $this->isValid($bytes);
        $this->hashAlgorithm = $hashAlgorithm;

        $hash = $this->hashAlgorithm->hash($this->bytes2Hex($bytes));
        $this->hash = bin2hex($hash);
    }

    public function hash(): string
    {
        return $this->hash;
    }

    private function isValid(array $bytes): void
    {
        foreach ($bytes as $byte) {
            if (!is_int($byte) || $byte < 0 || $byte > 255) {
                throw new InvalidMessageArgumentException();
            }
        }
    }

    private function bytes2Hex(array $bytes)
    {
        $chars = array_map("chr", $bytes);
        $bin = join($chars);
        return bin2hex($bin);
    }

}