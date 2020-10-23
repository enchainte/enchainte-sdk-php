<?php

namespace Enchainte\Proof\Domain;

use Enchainte\Proof\Domain\Exception\InvalidProofArgumentException;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Proof
{
    private const HASH_LENGTH = 64;
    private const HEX_REGEX = "/^[0-9a-fA-F]+$/";

    private $leaves;
    private $nodes;
    private $depth;
    private $bitmap;
    private $hashAlgorithm;

    public function __construct(array $leaves, array $nodes, string $depth, string $bitmap, HashAlgorithm $hashAlgorithm)
    {
        $this->areValid($leaves);
        $this->areValid($nodes);
        $this->isValid($depth);
        $this->isValid($bitmap);

        $this->leaves = $leaves;
        $this->nodes = $nodes;
        $this->depth = $depth;
        $this->bitmap = $bitmap;

        $this->hashAlgorithm = $hashAlgorithm;
    }

    public function leaves(): array
    {
        return $this->leaves;
    }

    public function nodes(): array
    {
        return $this->nodes;
    }

    public function depth(): string
    {
        return $this->depth;
    }

    public function bitmap(): string
    {
        return $this->bitmap;
    }

    public function leavesBytes(): array
    {
        $leavesBytes = [];
        foreach ($this->leaves as $leaf) {
            $leavesBytes[] = $this->hex2bytes($leaf);
        }
        return $leavesBytes;
    }

    public function nodesBytes(): array
    {
        $nodesBytes = [];
        foreach ($this->nodes as $node) {
            $nodesBytes[] = $this->hex2bytes($node);
        }
        return $nodesBytes;
    }

    public function depthBytes(): array
    {
        return $this->hex2bytes($this->depth);
    }

    public function bitmapBytes(): array
    {
        return $this->hex2bytes($this->bitmap);
    }

    public function mergeAndHash(?array $bytes1, ?array $bytes2): array
    {
        $bytes1 = $this->bytes2String($bytes1);
        $bytes2 =  $this->bytes2String($bytes2);
        $hash = bin2hex($this->hashAlgorithm->hash($bytes1 . $bytes2));

        return $this->hex2bytes($hash);
    }

    private function hex2bytes(string $hexStr): array
    {
        return array_map('hexdec', str_split($hexStr, 2));
    }

    private function bytes2String(array $bytes): string
    {
        $chars = array_map("chr", $bytes);
        return  join($chars);
    }

    private function areValid($values): void
    {
        foreach ($values as $value) {
            if (!$this->isHex($value) || strlen($value) !== self::HASH_LENGTH) {
                throw new InvalidProofArgumentException();
            }
        }
    }

    private function isValid($value): void
    {
        if (!$this->isHex($value)) {
            throw new InvalidProofArgumentException();
        }
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
