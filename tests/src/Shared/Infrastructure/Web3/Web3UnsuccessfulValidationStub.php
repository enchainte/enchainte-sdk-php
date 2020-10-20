<?php

namespace Enchainte\Tests\src\Shared\Infrastructure\Web3;

use Enchainte\Shared\Application\BlockchainClient;

final class Web3UnsuccessfulValidationStub implements BlockchainClient
{

    public function validateRoot(string $root): bool
    {
        return false;
    }
}