<?php

namespace Enchainte\Message\Domain;


final class Deferred
{
    private $resolve;
    private $reject;

    public function __construct(callable $resolve, callable $reject)
    {
        $this->resolve = $resolve;
        $this->reject = $reject;
    }

    public function resolve(): callable
    {
        return $this->resolve;
    }

    public function reject(): callable
    {
        return $this->reject;
    }
}