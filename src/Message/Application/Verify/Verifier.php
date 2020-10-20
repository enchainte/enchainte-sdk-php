<?php

namespace Enchainte\Message\Application\Verify;

use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;

final class Verifier
{
    private $proofVerifier;
    private $proofFinder;
    private $apiKey;

    public function __construct(Finder $proofFinder, ProofVerifier $proofVerifier, string $apiKey)
    {
        $this->proofFinder = $proofFinder;
        $this->proofVerifier = $proofVerifier;
        $this->apiKey = $apiKey;
    }

    public function verifyMessages(array $messages): bool
    {
        $proof = $this->proofFinder->getProof($messages);

        return $this->proofVerifier->verifyProof($proof->leaves(), $proof->nodes(), $proof->depth(), $proof->bitmap());
    }
}
