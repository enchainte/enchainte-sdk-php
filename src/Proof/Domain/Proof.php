<?php

namespace Enchainte\Proof\Domain;


use Enchainte\Shared\Infrastructure\Hashing\Blake2b;

final class Proof
{
    private $leaves;
    private $nodes;
    private $depth;
    private $bitmap;
    private $hashAlgorithm;

    public function __construct(array $leaves, array $nodes, string $depth, string $bitmap)
    {
        $this->leaves = $leaves;
        $this->nodes = $nodes;
        $this->depth = $depth;
        $this->bitmap = $bitmap;

        $this->hashAlgorithm = new Blake2b();
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

    public function mergeLeavesAndHash(array $leave1, array $leave2): string
    {
        return $this->hashAlgorithm->hash(array_merge($leave1, $leave2));
    }

    private function hex2bytes(string $hexStr): array
    {
        return array_map('hexdec', str_split($hexStr, 2));
    }
}
