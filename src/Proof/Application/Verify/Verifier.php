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

    public function calculateRoot(Proof $proof): string
    {
        $leavesIteration = 0;
        $nodesIteration = 0;
        $stack = [];

        while ($nodesIteration < count($proof->nodesBytes()) || $leavesIteration < count($proof->leavesBytes())) {
            $actualDepth = $proof->depthBytes()[$nodesIteration + $leavesIteration];

            if (($proof->bitmapBytes()[floor($nodesIteration + $leavesIteration) / 8] & (1 << (7 - (($nodesIteration + $leavesIteration) % 8)))) > 0) {
                $actualHash = $proof->nodesBytes()[$nodesIteration];
                $nodesIteration += 1;
            } else {
                $actualHash = $proof->leavesBytes()[$leavesIteration];
                $leavesIteration += 1;
            }

            while (count($stack) > 0 && $stack[count($stack) - 1][1] == $actualDepth) {
                $lastHash = array_pop($stack);

                if (!$lastHash) {
                    throw new \Exception("Verify: Stack got empty before capturing its value");
                }
                $actualHash = $proof->mergeLeavesAndHash($lastHash[0], $actualHash);

                $actualDepth -= 1;
            }
            $stack[] = [$actualHash, $actualDepth];
        }
        return $this->bytes2Hex($stack[0][0]);
    }

    private function bytes2Hex(array $bytes): string
    {
        $chars = array_map("chr", $bytes);
        $bin = join($chars);
        return bin2hex($bin);
    }
}