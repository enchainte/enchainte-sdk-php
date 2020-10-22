<?php

namespace Enchainte\Shared\Application;


interface HttpClient
{
    public function post(string $url, array $headers, array $data): array;

    public function get(string $url, array $headers): array;
}