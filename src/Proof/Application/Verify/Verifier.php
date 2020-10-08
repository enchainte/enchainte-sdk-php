<?php

namespace Enchainte\Proof\Application\Verify;

use Enchainte\Proof\Domain\Proof;

final class Verifier
{
    public function verifyProof(array $leaves, array $nodes, string $depth, string $bitmap): bool
    {

        $root = $this->calculateRoot(new Proof($leaves, $nodes, $depth, $bitmap));
        // TODO
        // Call validateRoot from Web3 Service with the root attribute of the proof
        // If both executions return true, the proof is valid, otherwise, it’s not
    }

    // verify
    private function calculateRoot(Proof $proof): string
    {
        $leavesIteration = 0;
        $nodesIteration = 0;
        $bitmapIteration = 0;
        $currentBit = 0;
        $stack = [];

        while ($nodesIteration < count($proof->nodesBytes()) || $leavesIteration < count($proof->leavesBytes())) {
            $actualDepth = $proof->depthBytes()[$nodesIteration + $leavesIteration];
            $is_leaf = ($proof->bitmapBytes()[$bitmapIteration] & (1 << (7 - ($currentBit % 8)))) < 1;
            $currentBit += 1;
            if ($currentBit % 8 == 0) {
                $bitmapIteration += 1;
            }

            if ($is_leaf) {
                $actualHash = $proof->leavesBytes()[$leavesIteration];
                $leavesIteration += 1;
            } else {
                $actualHash = $proof->nodesBytes()[$nodesIteration];
                $nodesIteration += 1;
            }

            while (!empty($stack) && $stack[count($stack) - 1][1] === $actualDepth) {
                $lastHash = array_pop($stack);
                $actualHash = $proof->mergeLeavesAndHash($lastHash[0], $actualHash);
                $actualDepth -= 1;
            }
            $stack[] = [$actualHash, $actualDepth];
        }

        return $stack[0][0];
    }
}