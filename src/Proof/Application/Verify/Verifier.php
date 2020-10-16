<?php

namespace Enchainte\Proof\Application\Verify;

use Enchainte\Proof\Domain\Proof;
use Enchainte\Shared\Application\BlockchainClient;
use Enchainte\Shared\Domain\HashAlgorithm;

final class Verifier
{
    private $blockchainClient;
    private $hashAlgorithm;

    public function __construct(BlockchainClient $blockchainClient, HashAlgorithm $hashAlgorithm)
    {
        $this->blockchainClient = $blockchainClient;
        $this->hashAlgorithm = $hashAlgorithm;
    }

    public function verifyProof(array $leaves, array $nodes, string $depth, string $bitmap): bool
    {

        $root = $this->calculateRoot(new Proof($leaves, $nodes, $depth, $bitmap, $this->hashAlgorithm));

        // Call validateRoot from Web3 Service with the root attribute of the proof
        return $this->blockchainClient->validateRoot($root);
        // If both executions return true, the proof is valid, otherwise, it’s not
    }

    // verify
    private function calculateRoot(Proof $proof): array
    {
        $leavesIteration = 0;
        $nodesIteration = 0;
        $bitmapIteration = 0;
        $currentBit = 0;
        $stack = [];

        while ($nodesIteration < count($proof->nodesBytes()) || $leavesIteration < count($proof->leavesBytes())) {
            $actualDepth = $proof->depthBytes()[$nodesIteration + $leavesIteration];
            $isLeaf = ($proof->bitmapBytes()[$bitmapIteration] & (1 << (7 - ($currentBit % 8)))) < 1;
            $currentBit += 1;
            if ($currentBit % 8 === 0) {
                $bitmapIteration += 1;
            }

            if ($isLeaf) {
                $actualHash = $proof->leavesBytes()[$leavesIteration];
                $leavesIteration += 1;
            } else {
                $actualHash = $proof->nodesBytes()[$nodesIteration];
                $nodesIteration += 1;
            }

            while (count($stack) > 0 && $stack[count($stack) - 1][1] === $actualDepth) {

                $lastHash = array_pop($stack);
                $actualHash = $proof->mergeLeavesAndHash($lastHash[0], $actualHash);
                $actualDepth -= 1;
            }
            $stack[] = [$actualHash, $actualDepth];
        }

        return $stack[0][0];
    }
}