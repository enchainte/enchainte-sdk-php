<?php

namespace Enchainte\Message\Domain;

use Enchainte\Message\Domain\Exception\InvalidMessageException;
use Enchainte\Shared\Domain\HashAlgorithm;
use Enchainte\Shared\Infrastructure\Hashing\Blake2b;
use InvalidArgumentException;


final class Message
{
    private const HASH_LENGTH = 64;
    private const HEX_REGEX = "/^[0-9a-fA-F]+$/";
    private const UNSIGNED_CHAR = "C*";

    private $hash;
    private $hashAlgorithm;

    // TODO validate that bytes is a valid byte array
    public function __construct(array $bytes, HashAlgorithm $hashAlgorithm)
    {
        $this->hashAlgorithm = $hashAlgorithm;

        $hash = $this->hashAlgorithm->hash($this->bytes2Hex($bytes));
        // TODO from byte str 2 hex
        $this->hash = bin2hex($hash);
    }

    // in hex format
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
    private function bytes2Hex(array $bytes)
    {
        $chars = array_map("chr", $bytes);
        $bin = join($chars);
        return bin2hex($bin);
    }




    private function hex2Bytes(string $hash): array
    {
        return array_map('hexdec', str_split($hash, 2));
    }

    private function string2Bytes(string $hash): string
    {
        return unpack(self::UNSIGNED_CHAR, $this->hash);
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