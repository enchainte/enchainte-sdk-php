<?php

namespace Enchainte\Message\Domain;

use Enchainte\Message\Domain\Exception\InvalidMessageException;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use InvalidArgumentException;


final class Message
{
    const HASH_LENGTH = 64;
    const HEX_REGEX = "/^[0-9a-fA-F]+$/";
    const UNSIGNED_CHAR = "C*";

    private $hash;
    private $hashAlgorithm;

    // TODO should receive bytes instead of hex string
    public function __construct(string $hash, HashAlgorithm $hashAlgorithm)
    {
        $this->hashAlgorithm = $hashAlgorithm;

        $this->hash = $this->fromHex($hash);

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
//        $bytes = array_map('hexdec', str_split($hash, 2));
        return $this->hashAlgorithm->hash($hash);
    }

    private function fromString(string $hash): string
    {
        $bytes = unpack(self::UNSIGNED_CHAR, $this->hash);
        return $this->hashAlgorithm->hash($bytes);
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