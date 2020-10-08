<?php


namespace Enchainte\Shared\Domain;


interface HashAlgorithm
{
    public function hash($message): string;
}