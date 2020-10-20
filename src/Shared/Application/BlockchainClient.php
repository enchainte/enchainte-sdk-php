<?php


namespace Enchainte\Shared\Application;


interface BlockchainClient
{
    public function validateRoot(string $root): bool;
}