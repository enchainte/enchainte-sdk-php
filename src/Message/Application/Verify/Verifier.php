<?php

namespace Enchainte\Message\Application\Verify;

use Enchainte\Proof\Application\Find\Finder;
use Enchainte\Proof\Application\Verify\Verifier as ProofVerifier;

final class Verifier
{
    private $proofVerifier;
    private $proofFinder;

    public function __construct(Finder $proofFinder, ProofVerifier $proofVerifier)
    {
        $this->proofFinder = $proofFinder;
        $this->proofVerifier = $proofVerifier;
    }

    public function verifyMessages(array $messages, string $token): bool
    {
        $proof = $this->proofFinder->getProof($messages, $token);

        return $this->proofVerifier->verifyProof($proof->leaves(), $proof->nodes(), $proof->depth(), $proof->bitmap());
    }
}
