<?php


namespace Enchainte\Shared\Application;


interface BlockchainClient
{
    public function validateRoot(array $root): bool;
}