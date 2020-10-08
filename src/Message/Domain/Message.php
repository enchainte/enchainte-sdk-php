<?php

namespace Enchainte\Shared\Domain;

use Enchainte\Shared\Domain\Exception\InvalidMessageException;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use InvalidArgumentException;


final class Message
{
    const HASH_LENGTH = 64;
    const HEX_REGEX = "/^[0-9a-fA-F]+$/";
    const UNSIGNED_CHAR = "C*";

    private $hash;
    private $hashAlgorithm;

    public function __construct($hash)
    {
        $this->hashAlgorithm = new Blake2b();

        switch ($hash){
            case is_string($hash):
                $this->hash = $this->fromString($hash);
                break;
            case $this->isHex($hash);
                $this->hash = $this->fromHex($hash);
                break;
            default:
//                $this->hash = $this->from($hash);
                throw new InvalidArgumentException("invalid argument type");
        }
    }

    public function hash(): string
    {
        return $this->hash;
    }

    private function isValid(string $hash): void
    {
        if ($hash !== self::HASH_LENGTH || !isHex($hash)){
            throw new InvalidMessageException($hash);
        }
    }

    // TODO
    public function from(mixed $data)
    {
    }

    // TODO
    public function fromHash()
    {
    }

    private function fromHex(string $hash): string
    {
        $bytes = array_map('hexdec', str_split($hash, 2));
        return $this->hashAlgorithm->hash($bytes);
    }

    private function fromString(string $hash): string
    {
        $bytes = unpack(self::UNSIGNED_CHAR, $this->hash);
        return $this->hashAlgorithm->hash($bytes);
    }

    // TODO
    public function fromUint8Array()
    {
    }

    // TODO
    public function sort()
    {
    }

    private function isHex(string $hash): bool
    {
        $regexResult = preg_match(self::HEX_REGEX, $hash);
        if ($regexResult !== 1){
            return false;
        }
        return true;
    }

}