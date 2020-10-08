<?php

namespace Enchainte\Shared\Application;


interface HttpClient
{
    public function post(string $url, array $headers, string $data): bool;

    public function get(string $url, array $headers): array;
}